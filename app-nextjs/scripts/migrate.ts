/**
 * One-off migration: legacy MySQL dump (data/vhudi.sql) -> Firebase Firestore.
 *
 * Usage:
 *   1. Fill in FIREBASE_* env vars (see .env.example) — a service account.
 *   2. npm run migrate           # migrates all 5 tables
 *
 * Only the 5 tables the app uses are migrated:
 *   doctors -> practices, price -> lenses, addon -> addons,
 *   prescription -> orders, addon_clients -> orderAddons, transaction -> payments
 *
 * The document IDs preserve the legacy primary keys so cross-references
 * (practiceId = doctors.pk, orderId = prescription.db_order) keep working.
 */
import { readFileSync } from "node:fs";
import { fileURLToPath } from "node:url";
import { dirname, join } from "node:path";
import { cert, getApps, initializeApp } from "firebase-admin/app";
import { getFirestore } from "firebase-admin/firestore";

const __dirname = dirname(fileURLToPath(import.meta.url));
const SQL_PATH = join(__dirname, "..", "data", "vhudi.sql");

/* --------------------------- tiny SQL INSERT parser -------------------------- */

/** Extract every VALUES row tuple for a given table's INSERT statements. */
function extractRows(sql: string, table: string): string[][] {
  const rows: string[][] = [];
  // Match: INSERT INTO `table` (...) VALUES (...),(...);
  // Match: INSERT INTO `table` (col, col, ...) VALUES (...),(...);
  // NOTE: the column list can contain the letter "V" (e.g. Item_Vision), so we
  // explicitly consume the parenthesised column list rather than [^V]*.
  const re = new RegExp(
    "INSERT INTO `" + table + "`\\s*\\([^)]*\\)\\s*VALUES\\s*([\\s\\S]*?);",
    "gi"
  );
  let m: RegExpExecArray | null;
  while ((m = re.exec(sql))) {
    rows.push(...parseTuples(m[1]));
  }
  return rows;
}

/** Parse "(a,'b',c),(d,'e',f)" into [[a,b,c],[d,e,f]] respecting quotes. */
function parseTuples(block: string): string[][] {
  const tuples: string[][] = [];
  let i = 0;
  const n = block.length;
  while (i < n) {
    if (block[i] !== "(") {
      i++;
      continue;
    }
    i++; // skip (
    const fields: string[] = [];
    let cur = "";
    let inStr = false;
    while (i < n) {
      const ch = block[i];
      if (inStr) {
        if (ch === "\\") {
          cur += block[i + 1] ?? "";
          i += 2;
          continue;
        }
        if (ch === "'") {
          // handle '' escape
          if (block[i + 1] === "'") {
            cur += "'";
            i += 2;
            continue;
          }
          inStr = false;
          i++;
          continue;
        }
        cur += ch;
        i++;
        continue;
      }
      if (ch === "'") {
        inStr = true;
        i++;
        continue;
      }
      if (ch === ",") {
        fields.push(cur.trim());
        cur = "";
        i++;
        continue;
      }
      if (ch === ")") {
        fields.push(cur.trim());
        i++;
        tuples.push(fields.map(unquoteNull));
        break;
      }
      cur += ch;
      i++;
    }
  }
  return tuples;
}

function unquoteNull(v: string): string {
  return v === "NULL" ? "" : v;
}

function num(v: string): number {
  const n = parseFloat(v);
  return Number.isFinite(n) ? n : 0;
}

/* ------------------------------- firebase init ------------------------------- */

function initFirestore() {
  if (!getApps().length) {
    const projectId = process.env.FIREBASE_PROJECT_ID;
    const clientEmail = process.env.FIREBASE_CLIENT_EMAIL;
    const privateKey = process.env.FIREBASE_PRIVATE_KEY?.replace(/\\n/g, "\n");
    if (!projectId || !clientEmail || !privateKey) {
      throw new Error("Missing FIREBASE_* env vars (see .env.example).");
    }
    initializeApp({ credential: cert({ projectId, clientEmail, privateKey }) });
  }
  return getFirestore();
}

/* ------------------------------- batched writer ------------------------------ */

async function writeAll(
  db: FirebaseFirestore.Firestore,
  collection: string,
  docs: { id?: string; data: Record<string, unknown> }[]
) {
  let batch = db.batch();
  let count = 0;
  for (const d of docs) {
    const ref = d.id
      ? db.collection(collection).doc(d.id)
      : db.collection(collection).doc();
    batch.set(ref, d.data);
    count++;
    if (count % 400 === 0) {
      await batch.commit();
      batch = db.batch();
    }
  }
  await batch.commit();
  console.log(`  ${collection}: wrote ${docs.length} docs`);
}

/* ---------------------------------- mappers ---------------------------------- */

async function main() {
  const sql = readFileSync(SQL_PATH, "utf8");
  const db = initFirestore();

  // doctors -> practices
  // columns: pk, surname, names, `cell no`, email, id, surname_intial, initial
  const practices = extractRows(sql, "doctors").map((r) => ({
    id: r[0],
    data: {
      surname: r[1] ?? "",
      names: r[2] ?? "",
      cellNo: r[3] ?? "",
      email: r[4] ?? "",
      idNumber: r[5] ?? "",
      surnameInitial: r[6] ?? "",
    },
  }));

  // price -> lenses
  // columns: Item_No, Item_Type, Item_Vision, Item_Price
  const lenses = extractRows(sql, "price").map((r) => ({
    id: r[0],
    data: {
      itemType: r[1] ?? "",
      itemVision: r[2] ?? "",
      itemPrice: num(r[3]),
    },
  }));

  // addon -> addons
  // columns: addon_No, addon_Type, addon_Price
  const addons = extractRows(sql, "addon").map((r) => ({
    id: r[0],
    data: {
      addonType: r[1] ?? "",
      addonPrice: num(r[2]),
    },
  }));

  // addon_clients -> orderAddons
  // columns: id, order_id, description, price
  const orderAddons = extractRows(sql, "addon_clients").map((r) => ({
    id: r[0],
    data: {
      orderId: r[1] ?? "",
      description: r[2] ?? "",
      price: num(r[3]),
    },
  }));

  // transaction -> payments  (columns: id, order_id, date, amount)
  const payments = extractRows(sql, "transaction").map((r) => ({
    id: r[0] || undefined,
    data: {
      orderId: r[1] ?? "",
      date: r[2] ?? "",
      amount: num(r[3]),
    },
  }));

  // prescription -> orders. The dump column order (see vhudi.sql CREATE TABLE):
  // 0 myCount, 1 invoice, 2 db_order, 3 db_practice, 4 db_patient, 5 db_lens,
  // 6 db_Vision, 7 lens_left, 8 vision_left, 9 db_rSPH, 10 db_rCLYL, 11 db_rAXIS,
  // 12 db_rP.D, 13 db_rSEG, 14 db_rRGD, 15 db_lSPH, 16 db_lCLYL, 17 db_lAXIS,
  // 18 db_lP.D, 19 db_lSEG, 20 db_lRGD, 21 db_TINT, 22 db_FrameD, 23 db_sInstruction,
  // 24 db_FrameType, 25 db_FramePrice, 26 db_Ref, 27 db_Progress, 28 db_date,
  // 29 LEFT, 30 RIGHT, 31 extra, 32 db_Paid, 33 db_Bal, 34 db_Total,
  // 35 db_Description, 36 db_Rstatus, 37 db_Lstatus
  const orders = extractRows(sql, "prescription").map((r) => ({
    // use dbOrder as the doc id when present, else fall back to myCount
    id: (r[2] && r[2].length ? r[2] : r[0]) || undefined,
    data: {
      myCount: num(r[0]),
      dbOrder: r[2] ?? "",
      practiceId: r[3] ?? "",
      patient: r[4] ?? "",
      lensRight: r[5] ?? "",
      lensLeft: r[7] ?? "",
      rSPH: r[9] ?? "",
      rCLYL: r[10] ?? "",
      rAXIS: r[11] ?? "",
      rPD: r[12] ?? "",
      rSEG: r[13] ?? "",
      rRGD: r[14] ?? "",
      lSPH: r[15] ?? "",
      lCLYL: r[16] ?? "",
      lAXIS: r[17] ?? "",
      lPD: r[18] ?? "",
      lSEG: r[19] ?? "",
      lRGD: r[20] ?? "",
      tint: r[21] ?? "",
      frameDetails: r[22] ?? "",
      specialInstruction: r[23] ?? "",
      frameType: r[24] ?? "",
      framePrice: num(r[25]),
      date: r[28] ?? "",
      priceRight: num(r[29]), // legacy LEFT
      priceLeft: num(r[30]), // legacy RIGHT
      tintPrice: "",
      rStatus: r[36] ?? "",
      lStatus: r[37] ?? "",
    },
  }));

  console.log("Migrating to Firestore...");
  await writeAll(db, "practices", practices);
  await writeAll(db, "lenses", lenses);
  await writeAll(db, "addons", addons);
  await writeAll(db, "orders", orders);
  await writeAll(db, "orderAddons", orderAddons);
  await writeAll(db, "payments", payments);
  console.log("Done.");
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});

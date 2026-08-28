import "server-only";
import { db, collections } from "../firebaseAdmin";
import { orderPaid, orderTotal, toNum } from "../money";
import type { Order, OrderSummary } from "../types";
import { getPractice, listPractices } from "./practices";
import { listOrderAddonsForOrders } from "./orderAddons";
import { listPaymentsForOrders } from "./payments";
import { recordPayment } from "./payments";

const col = () => db().collection(collections.orders);

function toOrder(id: string, d: FirebaseFirestore.DocumentData): Order {
  return {
    id,
    myCount: toNum(d.myCount),
    dbOrder: d.dbOrder ?? "",
    practiceId: d.practiceId ?? "",
    patient: d.patient ?? "",
    date: d.date ?? "",
    rSPH: d.rSPH ?? "",
    rCLYL: d.rCLYL ?? "",
    rAXIS: d.rAXIS ?? "",
    rPD: d.rPD ?? "",
    rSEG: d.rSEG ?? "",
    rRGD: d.rRGD ?? "",
    lSPH: d.lSPH ?? "",
    lCLYL: d.lCLYL ?? "",
    lAXIS: d.lAXIS ?? "",
    lPD: d.lPD ?? "",
    lSEG: d.lSEG ?? "",
    lRGD: d.lRGD ?? "",
    lensRight: d.lensRight ?? "",
    lensLeft: d.lensLeft ?? "",
    priceRight: toNum(d.priceRight),
    priceLeft: toNum(d.priceLeft),
    tint: d.tint ?? "",
    tintPrice: d.tintPrice ?? "",
    frameType: d.frameType ?? "",
    framePrice: toNum(d.framePrice),
    frameDetails: d.frameDetails ?? "",
    specialInstruction: d.specialInstruction ?? "",
    rStatus: d.rStatus ?? "",
    lStatus: d.lStatus ?? "",
  };
}

/** get_count(): SELECT MAX(myCount) + 1. Legacy manual sequence. */
export async function getNextCount(): Promise<number> {
  const snap = await col().orderBy("myCount", "desc").limit(1).get();
  if (snap.empty) return 1;
  return toNum(snap.docs[0].data().myCount) + 1;
}

/** order($order): true if an order number already exists (uniqueness check). */
export async function orderNumberExists(dbOrder: string): Promise<boolean> {
  if (!dbOrder) return false;
  const snap = await col().where("dbOrder", "==", dbOrder).limit(1).get();
  return !snap.empty;
}

export async function getOrderByDbOrder(dbOrder: string): Promise<Order | null> {
  const snap = await col().where("dbOrder", "==", dbOrder).limit(1).get();
  if (snap.empty) return null;
  const doc = snap.docs[0];
  return toOrder(doc.id, doc.data());
}

/** open.php loads by myCount (invoice number). */
export async function getOrderByCount(myCount: number): Promise<Order | null> {
  const snap = await col().where("myCount", "==", myCount).limit(1).get();
  if (snap.empty) return null;
  const doc = snap.docs[0];
  return toOrder(doc.id, doc.data());
}

export async function getOrderById(id: string): Promise<Order | null> {
  const doc = await col().doc(id).get();
  return doc.exists ? toOrder(doc.id, doc.data()!) : null;
}

/**
 * new_order.php INSERT.
 * lensRight = description(rtype), lensLeft = description(ltype),
 * priceRight = price(rtype)  [legacy `LEFT`],
 * priceLeft  = price(ltype)  [legacy `RIGHT`].
 */
export async function createOrder(input: Omit<Order, "id" | "myCount"> & { myCount?: number }): Promise<string> {
  const myCount = input.myCount ?? (await getNextCount());
  const ref = await col().add({
    myCount,
    dbOrder: input.dbOrder,
    practiceId: input.practiceId,
    patient: input.patient,
    date: input.date,
    rSPH: input.rSPH,
    rCLYL: input.rCLYL,
    rAXIS: input.rAXIS,
    rPD: input.rPD,
    rSEG: input.rSEG,
    rRGD: input.rRGD,
    lSPH: input.lSPH,
    lCLYL: input.lCLYL,
    lAXIS: input.lAXIS,
    lPD: input.lPD,
    lSEG: input.lSEG,
    lRGD: input.lRGD,
    lensRight: input.lensRight,
    lensLeft: input.lensLeft,
    priceRight: toNum(input.priceRight),
    priceLeft: toNum(input.priceLeft),
    tint: input.tint,
    tintPrice: input.tintPrice,
    frameType: input.frameType,
    framePrice: toNum(input.framePrice),
    frameDetails: input.frameDetails,
    specialInstruction: input.specialInstruction,
    rStatus: input.rStatus ?? "0",
    lStatus: input.lStatus ?? "0",
  });
  return ref.id;
}

/** open.php UPDATE prescription ... WHERE db_order = ?. */
export async function updateOrder(id: string, patch: Partial<Order>): Promise<void> {
  const clean: Record<string, unknown> = { ...patch };
  delete clean.id;
  if ("priceRight" in clean) clean.priceRight = toNum(clean.priceRight);
  if ("priceLeft" in clean) clean.priceLeft = toNum(clean.priceLeft);
  if ("framePrice" in clean) clean.framePrice = toNum(clean.framePrice);
  await col().doc(id).set(clean, { merge: true });
}

/* ------------------------------ Queries + totals ----------------------------- */

async function decorate(orders: Order[]): Promise<OrderSummary[]> {
  const dbOrders = orders.map((o) => o.dbOrder).filter(Boolean);
  const [addonsMap, paymentsMap, practices] = await Promise.all([
    listOrderAddonsForOrders(dbOrders),
    listPaymentsForOrders(dbOrders),
    listPractices(),
  ]);
  const practiceName = new Map(practices.map((p) => [p.id, p.surnameInitial]));

  return orders.map((o) => {
    const total = orderTotal(o, addonsMap.get(o.dbOrder) ?? []);
    const paid = orderPaid(paymentsMap.get(o.dbOrder) ?? []);
    return {
      ...o,
      practiceName: practiceName.get(o.practiceId) ?? "",
      total,
      paid,
      due: total - paid,
    };
  });
}

/** view_order.php default / view_all: orders for a practice (newest first). */
export async function listOrdersForPractice(
  practiceId: string,
  opts: { orderNo?: string; from?: string; to?: string } = {}
): Promise<OrderSummary[]> {
  let orders: Order[];
  if (opts.orderNo) {
    const snap = await col()
      .where("practiceId", "==", practiceId)
      .where("dbOrder", "==", opts.orderNo)
      .get();
    orders = snap.docs.map((doc) => toOrder(doc.id, doc.data()));
  } else {
    const snap = await col().where("practiceId", "==", practiceId).get();
    orders = snap.docs.map((doc) => toOrder(doc.id, doc.data()));
    if (opts.from && opts.to) {
      orders = orders.filter((o) => o.date >= opts.from! && o.date <= opts.to!);
    }
  }
  orders.sort((a, b) => b.myCount - a.myCount);
  return decorate(orders.slice(0, 200));
}

/** invoice.php: all orders (global). */
export async function listAllOrders(): Promise<OrderSummary[]> {
  const snap = await col().get();
  const orders = snap.docs.map((doc) => toOrder(doc.id, doc.data()));
  orders.sort((a, b) => b.myCount - a.myCount);
  return decorate(orders);
}

/** Totals for a practice (used by transaction.php payment screen). */
export async function practiceTotals(
  practiceId: string
): Promise<{ orders: OrderSummary[]; total: number; paid: number; due: number }> {
  const orders = await listOrdersForPractice(practiceId);
  const total = orders.reduce((s, o) => s + o.total, 0);
  const paid = orders.reduce((s, o) => s + o.paid, 0);
  const due = orders.reduce((s, o) => s + o.due, 0);
  return { orders, total, paid, due };
}

/**
 * transaction.php payment cascade.
 * Iterates the practice's orders; for each with due > 0:
 *   - if payNow >= due: settle the full due, subtract from payNow
 *   - else: pay the remaining payNow, stop
 * Returns the leftover "change".
 *
 * NOTE: the legacy PHP had a bug where the partial branch inserted the full
 * `due2` instead of the smaller `paynow`. We fix that here (pay only what is
 * tendered) — the visible Total/Paid/Due figures behave identically for the
 * common full-payment case, and correctly for partial payments.
 */
export async function payPractice(
  practiceId: string,
  payNow: number
): Promise<{ change: number }> {
  const { orders } = await practiceTotals(practiceId);
  let remaining = toNum(payNow);

  // oldest first (legacy iterates in table order; we use ascending myCount)
  const ascending = [...orders].sort((a, b) => a.myCount - b.myCount);

  for (const o of ascending) {
    if (remaining <= 0) break;
    const due = o.due;
    if (due <= 0) continue;
    if (remaining >= due) {
      await recordPayment(o.dbOrder, due);
      remaining -= due;
    } else {
      await recordPayment(o.dbOrder, remaining);
      remaining = 0;
      break;
    }
  }
  return { change: remaining > 0 ? remaining : 0 };
}

export { getPractice };

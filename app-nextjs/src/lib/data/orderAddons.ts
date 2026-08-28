import "server-only";
import { db, collections } from "../firebaseAdmin";
import { toNum } from "../money";
import type { OrderAddon } from "../types";

const col = () => db().collection(collections.orderAddons);

function toOrderAddon(id: string, d: FirebaseFirestore.DocumentData): OrderAddon {
  return {
    id,
    orderId: d.orderId ?? "",
    description: d.description ?? "",
    price: toNum(d.price),
  };
}

/** addons2.php list: SELECT * FROM addon_clients WHERE order_id = ? */
export async function listOrderAddons(orderId: string): Promise<OrderAddon[]> {
  const snap = await col().where("orderId", "==", orderId).get();
  return snap.docs.map((doc) => toOrderAddon(doc.id, doc.data()));
}

/** Batch fetch for many orders (used by list/totals pages). */
export async function listOrderAddonsForOrders(
  orderIds: string[]
): Promise<Map<string, OrderAddon[]>> {
  const map = new Map<string, OrderAddon[]>();
  orderIds.forEach((id) => map.set(id, []));
  // Firestore 'in' supports up to 30 values; chunk to be safe.
  const chunks: string[][] = [];
  for (let i = 0; i < orderIds.length; i += 30) chunks.push(orderIds.slice(i, i + 30));
  for (const chunk of chunks) {
    if (chunk.length === 0) continue;
    const snap = await col().where("orderId", "in", chunk).get();
    snap.docs.forEach((doc) => {
      const oa = toOrderAddon(doc.id, doc.data());
      const arr = map.get(oa.orderId) ?? [];
      arr.push(oa);
      map.set(oa.orderId, arr);
    });
  }
  return map;
}

/** addons2.php add: INSERT INTO addon_clients(order_id, description, price). */
export async function addOrderAddon(input: {
  orderId: string;
  description: string;
  price: number;
}): Promise<string> {
  const ref = await col().add({ ...input, price: toNum(input.price) });
  return ref.id;
}

/** addons2.php remove: DELETE FROM addon_clients WHERE id = ?. */
export async function removeOrderAddon(id: string): Promise<void> {
  await col().doc(id).delete();
}

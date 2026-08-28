import "server-only";
import { db, collections } from "../firebaseAdmin";
import { toNum } from "../money";
import type { Payment } from "../types";

const col = () => db().collection(collections.payments);

function toPayment(id: string, d: FirebaseFirestore.DocumentData): Payment {
  return {
    id,
    orderId: d.orderId ?? "",
    date: d.date ?? "",
    amount: toNum(d.amount),
  };
}

/** get_total_transaction($order): SELECT * FROM transaction WHERE order_id = ?. */
export async function listPayments(orderId: string): Promise<Payment[]> {
  const snap = await col().where("orderId", "==", orderId).get();
  return snap.docs.map((doc) => toPayment(doc.id, doc.data()));
}

export async function listPaymentsForOrders(
  orderIds: string[]
): Promise<Map<string, Payment[]>> {
  const map = new Map<string, Payment[]>();
  orderIds.forEach((id) => map.set(id, []));
  const chunks: string[][] = [];
  for (let i = 0; i < orderIds.length; i += 30) chunks.push(orderIds.slice(i, i + 30));
  for (const chunk of chunks) {
    if (chunk.length === 0) continue;
    const snap = await col().where("orderId", "in", chunk).get();
    snap.docs.forEach((doc) => {
      const p = toPayment(doc.id, doc.data());
      const arr = map.get(p.orderId) ?? [];
      arr.push(p);
      map.set(p.orderId, arr);
    });
  }
  return map;
}

/** payme($order, $amount): INSERT INTO transaction(order_id, date, amount). */
export async function recordPayment(orderId: string, amount: number): Promise<void> {
  await col().add({
    orderId,
    date: new Date().toISOString(),
    amount: toNum(amount),
  });
}

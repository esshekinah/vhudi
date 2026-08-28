/**
 * Money helpers that reproduce the EXACT calculations from the legacy PHP.
 *
 * Legacy references:
 *   get_total_cost($db_order)  = Σ (prescription.`LEFT` + prescription.`RIGHT`)
 *                                + Σ addon_clients.price
 *   get_total_transaction($o)  = Σ transaction.amount
 *   due                        = cost - paid
 *
 * In our model priceRight === legacy `LEFT`, priceLeft === legacy `RIGHT`,
 * so orderCost = priceRight + priceLeft + addons. Same arithmetic.
 */
import type { Order, OrderAddon, Payment } from "./types";

export function toNum(v: unknown): number {
  const n = typeof v === "number" ? v : parseFloat(String(v ?? ""));
  return Number.isFinite(n) ? n : 0;
}

/** Sum of the two lens prices on an order (legacy LEFT + RIGHT). */
export function lensCost(order: Pick<Order, "priceRight" | "priceLeft">): number {
  return toNum(order.priceRight) + toNum(order.priceLeft);
}

/** Total cost of an order = lens prices + attached addon prices. */
export function orderTotal(
  order: Pick<Order, "priceRight" | "priceLeft">,
  addons: OrderAddon[]
): number {
  return addons.reduce((sum, a) => sum + toNum(a.price), lensCost(order));
}

/** Total paid against an order. */
export function orderPaid(payments: Payment[]): number {
  return payments.reduce((sum, p) => sum + toNum(p.amount), 0);
}

export function formatR(amount: number): string {
  return "R" + (Math.round(amount * 100) / 100).toString();
}

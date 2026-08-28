import Link from "next/link";
import type { OrderSummary } from "@/lib/types";
import { formatR } from "@/lib/money";

/**
 * Shared orders table used by view_order.php and invoice.php.
 * `actionsFor` lets each page inject its own row actions.
 */
export function OrdersTable({
  rows,
  actionsFor,
}: {
  rows: OrderSummary[];
  actionsFor: (row: OrderSummary) => React.ReactNode;
}) {
  const total = rows.reduce((s, r) => s + r.total, 0);
  const paid = rows.reduce((s, r) => s + r.paid, 0);
  const due = rows.reduce((s, r) => s + r.due, 0);

  return (
    <table className="vhudi-table">
      <thead>
        <tr>
          <th>Invoice No</th>
          <th>Order No</th>
          <th>Practice</th>
          <th>Patient</th>
          <th>Date</th>
          <th>Status</th>
          <th>Total</th>
          <th>Paid</th>
          <th>Due</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        {rows.length === 0 && (
          <tr>
            <td colSpan={10}>No orders.</td>
          </tr>
        )}
        {rows.map((r) => (
          <tr key={r.id}>
            <td>0{r.myCount}</td>
            <td>{r.dbOrder}</td>
            <td>{r.practiceName}</td>
            <td>{r.patient}</td>
            <td>{r.date}</td>
            <td>{r.rStatus}</td>
            <td>{formatR(r.total)}</td>
            <td>{formatR(r.paid)}</td>
            <td>{formatR(r.due)}</td>
            <td>{actionsFor(r)}</td>
          </tr>
        ))}
      </tbody>
      <tfoot>
        <tr style={{ background: "green" }}>
          <td colSpan={6} className="text-right font-semibold">
            Totals
          </td>
          <td className="font-semibold">{formatR(total)}</td>
          <td className="font-semibold">{formatR(paid)}</td>
          <td className="font-semibold">{formatR(due)}</td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  );
}

export function LinkBtn({ href, children }: { href: string; children: React.ReactNode }) {
  return (
    <Link className="vhudi-btn mr-1 mb-1 inline-block text-xs" href={href}>
      {children}
    </Link>
  );
}

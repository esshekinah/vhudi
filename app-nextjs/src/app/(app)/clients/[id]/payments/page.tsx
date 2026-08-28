import { notFound } from "next/navigation";
import { getPractice } from "@/lib/data/practices";
import { practiceTotals } from "@/lib/data/orders";
import { formatR } from "@/lib/money";
import { payAction } from "./actions";

// transaction.php — payment screen with cascade.
export default async function PaymentsPage({
  params,
  searchParams,
}: {
  params: Promise<{ id: string }>;
  searchParams: Promise<{ change?: string }>;
}) {
  const { id } = await params;
  const { change } = await searchParams;
  const practice = await getPractice(id);
  if (!practice) notFound();

  const { total, paid, due } = await practiceTotals(id);

  return (
    <section className="max-w-2xl">
      <h2 className="mb-4 text-xl font-semibold">Transaction: {practice.surnameInitial}</h2>

      {change && Number(change) > 0 && (
        <p className="mb-4 text-center font-semibold">Your change is {formatR(Number(change))}</p>
      )}

      <form action={payAction} className="rounded bg-black/60 p-4">
        <input type="hidden" name="practiceId" value={id} />
        <table className="vhudi-table">
          <thead>
            <tr>
              <th>Total</th>
              <th>Paid</th>
              <th>Due</th>
              <th>Pay now</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{formatR(total)}</td>
              <td>{formatR(paid)}</td>
              <td>{formatR(due)}</td>
              <td>
                <input
                  className="vhudi-input"
                  type="number"
                  step="0.01"
                  name="paynow"
                  defaultValue={due.toFixed(2)}
                />
              </td>
              <td>
                <button className="vhudi-btn" type="submit">
                  Pay
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </section>
  );
}

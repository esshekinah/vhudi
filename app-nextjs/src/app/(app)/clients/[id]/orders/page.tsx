import { notFound } from "next/navigation";
import Link from "next/link";
import { getPractice } from "@/lib/data/practices";
import { listOrdersForPractice } from "@/lib/data/orders";
import { OrdersTable, LinkBtn } from "@/components/OrdersTable";

// view_order.php (default / search / sort / view_all all handled via query params)
export default async function ViewOrdersPage({
  params,
  searchParams,
}: {
  params: Promise<{ id: string }>;
  searchParams: Promise<{ order_no?: string; from?: string; to?: string }>;
}) {
  const { id } = await params;
  const { order_no, from, to } = await searchParams;
  const practice = await getPractice(id);
  if (!practice) notFound();

  const rows = await listOrdersForPractice(id, { orderNo: order_no, from, to });

  return (
    <section>
      <div className="mb-4 flex flex-wrap items-center gap-2">
        <h2 className="mr-4 text-xl font-semibold">Invoices: Dr {practice.surnameInitial}</h2>
        <Link className="vhudi-btn" href={`/clients/${id}/orders/new`}>
          New Order
        </Link>
        <Link className="vhudi-btn" href={`/clients/${id}/payments`}>
          Payments
        </Link>
      </div>

      <form method="get" className="no-print mb-4 flex flex-wrap items-end gap-2 rounded bg-black/60 p-4">
        <label className="text-sm">
          <span className="mb-1 block">Order No</span>
          <input className="vhudi-input" name="order_no" defaultValue={order_no ?? ""} />
        </label>
        <button className="vhudi-btn" type="submit">
          Search
        </button>
        <label className="text-sm">
          <span className="mb-1 block">From</span>
          <input className="vhudi-input" type="date" name="from" defaultValue={from ?? ""} />
        </label>
        <label className="text-sm">
          <span className="mb-1 block">To</span>
          <input className="vhudi-input" type="date" name="to" defaultValue={to ?? ""} />
        </label>
        <button className="vhudi-btn" type="submit">
          Sort
        </button>
        <Link className="vhudi-btn" href={`/clients/${id}/orders`}>
          View All
        </Link>
      </form>

      <OrdersTable
        rows={rows}
        actionsFor={(r) => (
          <>
            <LinkBtn href={`/clients/${id}/orders/${r.dbOrder}/addons`}>Addons</LinkBtn>
            <LinkBtn href={`/clients/${id}/orders/${r.dbOrder}/open`}>Open</LinkBtn>
            <LinkBtn href={`/print/${r.myCount}`}>Print</LinkBtn>
          </>
        )}
      />
    </section>
  );
}

import { listAllOrders } from "@/lib/data/orders";
import { OrdersTable, LinkBtn } from "@/components/OrdersTable";

// invoice.php — global list of all orders.
export default async function InvoicePage() {
  const rows = await listAllOrders();

  return (
    <section>
      <h2 className="mb-4 text-xl font-semibold">Invoices</h2>
      <OrdersTable
        rows={rows}
        actionsFor={(r) => (
          <>
            <LinkBtn href={`/clients/${r.practiceId}/payments`}>Pay</LinkBtn>
            <LinkBtn href={`/clients/${r.practiceId}/orders/${r.dbOrder}/addons`}>Addons</LinkBtn>
            <LinkBtn href={`/print/${r.myCount}`}>Print</LinkBtn>
          </>
        )}
      />
    </section>
  );
}

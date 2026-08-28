import { notFound, redirect } from "next/navigation";
import { isAuthenticated } from "@/lib/auth";
import { getOrderByCount } from "@/lib/data/orders";
import { getPractice } from "@/lib/data/practices";
import { listOrderAddons } from "@/lib/data/orderAddons";
import { listPayments } from "@/lib/data/payments";
import { orderPaid, orderTotal, formatR } from "@/lib/money";
import { PrintButton } from "./PrintButton";

/**
 * Replacement for clients/pdf/examples/pdf.php — renders the invoice as HTML
 * and lets the user print / save-as-PDF via the browser.
 */
export default async function PrintPage({ params }: { params: Promise<{ count: string }> }) {
  if (!(await isAuthenticated())) redirect("/login");
  const { count } = await params;
  const myCount = parseInt(count, 10);
  const order = await getOrderByCount(myCount);
  if (!order) notFound();

  const [practice, addons, payments] = await Promise.all([
    getPractice(order.practiceId),
    listOrderAddons(order.dbOrder),
    listPayments(order.dbOrder),
  ]);

  const total = orderTotal(order, addons);
  const paid = orderPaid(payments);
  const due = total - paid;

  const Row = ({ label, value }: { label: string; value: React.ReactNode }) => (
    <tr>
      <td className="w-1/3 py-1 pr-4 text-gray-600">{label}</td>
      <td className="py-1">{value}</td>
    </tr>
  );

  return (
    <div className="mx-auto max-w-2xl bg-white p-8 text-black">
      <PrintButton />

      <h1 className="mb-6 text-center text-2xl font-bold">Vhudi Optical Dispensing</h1>

      <table className="w-full text-sm">
        <tbody>
          <Row label="Invoice No" value={`#0${order.myCount}`} />
          <Row label="Order No" value={order.dbOrder} />
          <Row label="Date" value={order.date} />
          <Row label="Practice Name" value={practice?.surnameInitial ?? ""} />
          <Row label="Patient" value={order.patient} />
          <Row label="Frame Type" value={order.frameType} />
          <Row label="Frame Price" value={formatR(order.framePrice)} />
          <Row
            label="RE: Lens Type & Vision"
            value={`${order.lensRight} ${formatR(order.priceRight)}`}
          />
          <Row
            label="LE: Lens Type & Vision"
            value={`${order.lensLeft} ${formatR(order.priceLeft)}`}
          />
        </tbody>
      </table>

      <table className="mt-6 w-full border-collapse text-center text-sm">
        <thead>
          <tr className="bg-gray-200">
            <th className="p-1"></th>
            <th className="p-1">SPH</th>
            <th className="p-1">CLYL</th>
            <th className="p-1">AXIS</th>
            <th className="p-1">P.D</th>
            <th className="p-1">SEG</th>
            <th className="p-1">R.G.D/ADD</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td className="p-1 font-semibold">RE</td>
            <td className="p-1">{order.rSPH}</td>
            <td className="p-1">{order.rCLYL}</td>
            <td className="p-1">{order.rAXIS}</td>
            <td className="p-1">{order.rPD}</td>
            <td className="p-1">{order.rSEG}</td>
            <td className="p-1">{order.rRGD}</td>
          </tr>
          <tr>
            <td className="p-1 font-semibold">LE</td>
            <td className="p-1">{order.lSPH}</td>
            <td className="p-1">{order.lCLYL}</td>
            <td className="p-1">{order.lAXIS}</td>
            <td className="p-1">{order.lPD}</td>
            <td className="p-1">{order.lSEG}</td>
            <td className="p-1">{order.lRGD}</td>
          </tr>
        </tbody>
      </table>

      <table className="mt-6 w-full text-sm">
        <tbody>
          <Row
            label="Addons"
            value={
              addons.length === 0 ? (
                "None"
              ) : (
                <ul>
                  {addons.map((a) => (
                    <li key={a.id}>
                      {a.description} <b>{formatR(a.price)}</b>
                    </li>
                  ))}
                </ul>
              )
            }
          />
          <Row label="TINT" value={order.tint} />
          <Row label="Special Instructions" value={order.specialInstruction} />
        </tbody>
      </table>

      <table className="mt-6 w-full text-sm">
        <tbody>
          <Row label="Total" value={formatR(total)} />
          <Row label="Paid" value={formatR(paid)} />
          <Row label="Due" value={formatR(due)} />
        </tbody>
      </table>
    </div>
  );
}

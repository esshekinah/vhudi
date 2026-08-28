import Link from "next/link";
import { listAddons } from "@/lib/data/catalog";
import { listOrderAddons } from "@/lib/data/orderAddons";
import { formatR, toNum } from "@/lib/money";
import { addOrderAddonAction, removeOrderAddonAction } from "./actions";

// addons2.php
export default async function OrderAddonsPage({
  params,
}: {
  params: Promise<{ id: string; order: string }>;
}) {
  const { id, order } = await params;
  const orderId = decodeURIComponent(order);
  const [addons, attached] = await Promise.all([listAddons(), listOrderAddons(orderId)]);
  const total = attached.reduce((s, a) => s + toNum(a.price), 0);

  return (
    <section className="max-w-2xl">
      <h2 className="mb-4 text-xl font-semibold">Order No: {orderId}</h2>

      <form action={addOrderAddonAction} className="mb-4 flex items-end gap-2 rounded bg-black/60 p-4">
        <input type="hidden" name="orderId" value={orderId} />
        <input type="hidden" name="practiceId" value={id} />
        <label className="flex-1 text-sm">
          <span className="mb-1 block">Addon</span>
          <select className="vhudi-input" name="addon">
            {addons.map((a) => (
              <option key={a.id} value={a.id}>
                {a.addonType} — {formatR(a.addonPrice)}
              </option>
            ))}
          </select>
        </label>
        <button className="vhudi-btn" type="submit">
          Add
        </button>
      </form>

      <table className="vhudi-table">
        <thead>
          <tr>
            <th>Addons</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          {attached.length === 0 && (
            <tr>
              <td colSpan={2}>No addons on this order.</td>
            </tr>
          )}
          {attached.map((a) => (
            <tr key={a.id}>
              <td>
                {a.description} {formatR(toNum(a.price))}
              </td>
              <td>
                <form action={removeOrderAddonAction}>
                  <input type="hidden" name="remove_id" value={a.id} />
                  <input type="hidden" name="orderId" value={orderId} />
                  <input type="hidden" name="practiceId" value={id} />
                  <button className="vhudi-btn" type="submit">
                    Remove
                  </button>
                </form>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      <p className="mt-4 text-center font-semibold">Total: {formatR(total)}</p>
      <p className="mt-4 text-center">
        <Link className="vhudi-btn" href={`/clients/${id}/orders`}>
          Go to Orders
        </Link>
      </p>
    </section>
  );
}

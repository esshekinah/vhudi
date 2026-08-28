import { listAddons } from "@/lib/data/catalog";
import { formatR } from "@/lib/money";
import { ProductTabs } from "@/components/ProductTabs";
import { createAddonAction, deleteAddonAction } from "../actions";
import { AddonEditRow } from "./AddonEditRow";

// addons.php (+ inline edit from addons_update.php)
export default async function AddonsPage() {
  const addons = await listAddons();

  return (
    <section>
      <h2 className="mb-4 text-xl font-semibold">Addons</h2>
      <ProductTabs active="addons" />

      <form action={createAddonAction} className="no-print mb-4 flex flex-wrap items-end gap-2 rounded bg-black/60 p-4">
        <label className="flex-1 text-sm">
          <span className="mb-1 block">Addon Type</span>
          <input className="vhudi-input" name="addon" />
        </label>
        <label className="text-sm">
          <span className="mb-1 block">Price</span>
          <input className="vhudi-input" name="price" type="number" step="0.01" />
        </label>
        <button className="vhudi-btn" type="submit">
          Add
        </button>
      </form>

      <table className="vhudi-table">
        <thead>
          <tr>
            <th>Addon Type</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          {addons.length === 0 && (
            <tr>
              <td colSpan={3}>No addons yet.</td>
            </tr>
          )}
          {addons.map((a) => (
            <AddonEditRow
              key={a.id}
              addon={a}
              formatted={formatR(a.addonPrice)}
              deleteAction={deleteAddonAction}
            />
          ))}
        </tbody>
      </table>
    </section>
  );
}

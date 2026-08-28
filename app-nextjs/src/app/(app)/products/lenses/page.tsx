import { listLenses } from "@/lib/data/catalog";
import { formatR } from "@/lib/money";
import { ProductTabs } from "@/components/ProductTabs";
import { createLensAction, deleteLensAction } from "../actions";
import { LensEditRow } from "./LensEditRow";

// lense-and-vision.php (+ inline edit from lense-and-vision_update.php)
export default async function LensesPage() {
  const lenses = await listLenses();

  return (
    <section>
      <h2 className="mb-4 text-xl font-semibold">Lense and Vision</h2>
      <ProductTabs active="lenses" />

      <form action={createLensAction} className="no-print mb-4 flex flex-wrap items-end gap-2 rounded bg-black/60 p-4">
        <label className="flex-1 text-sm">
          <span className="mb-1 block">Item Type</span>
          <input className="vhudi-input" name="item_type" />
        </label>
        <label className="flex-1 text-sm">
          <span className="mb-1 block">Item Vision</span>
          <input className="vhudi-input" name="item_vision" />
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
            <th>Item Type</th>
            <th>Item Vision</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          {lenses.length === 0 && (
            <tr>
              <td colSpan={4}>No lenses yet.</td>
            </tr>
          )}
          {lenses.map((l) => (
            <LensEditRow
              key={l.id}
              lens={l}
              formatted={formatR(l.itemPrice)}
              deleteAction={deleteLensAction}
            />
          ))}
        </tbody>
      </table>
    </section>
  );
}

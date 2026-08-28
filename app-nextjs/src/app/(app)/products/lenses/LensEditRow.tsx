"use client";
import { useState } from "react";
import type { Lens } from "@/lib/types";
import { updateLensAction } from "../actions";

export function LensEditRow({
  lens,
  formatted,
  deleteAction,
}: {
  lens: Lens;
  formatted: string;
  deleteAction: (formData: FormData) => void;
}) {
  const [editing, setEditing] = useState(false);

  if (editing) {
    return (
      <tr>
        <td colSpan={4}>
          <form action={updateLensAction} className="flex flex-wrap items-end gap-2">
            <input type="hidden" name="id" value={lens.id} />
            <input className="vhudi-input flex-1" name="item_type" defaultValue={lens.itemType} />
            <input className="vhudi-input flex-1" name="item_vision" defaultValue={lens.itemVision} />
            <input
              className="vhudi-input"
              name="price"
              type="number"
              step="0.01"
              defaultValue={lens.itemPrice}
            />
            <button className="vhudi-btn" type="submit">
              Save
            </button>
            <button className="vhudi-btn" type="button" onClick={() => setEditing(false)}>
              Cancel
            </button>
          </form>
        </td>
      </tr>
    );
  }

  return (
    <tr>
      <td>{lens.itemType}</td>
      <td>{lens.itemVision}</td>
      <td>{formatted}</td>
      <td>
        <button className="vhudi-btn mr-1" type="button" onClick={() => setEditing(true)}>
          Edit
        </button>
        <form action={deleteAction} className="inline">
          <input type="hidden" name="id" value={lens.id} />
          <button className="vhudi-btn" type="submit">
            Remove
          </button>
        </form>
      </td>
    </tr>
  );
}

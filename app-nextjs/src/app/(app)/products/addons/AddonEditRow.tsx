"use client";
import { useState } from "react";
import type { Addon } from "@/lib/types";
import { updateAddonAction } from "../actions";

export function AddonEditRow({
  addon,
  formatted,
  deleteAction,
}: {
  addon: Addon;
  formatted: string;
  deleteAction: (formData: FormData) => void;
}) {
  const [editing, setEditing] = useState(false);

  if (editing) {
    return (
      <tr>
        <td colSpan={3}>
          <form action={updateAddonAction} className="flex flex-wrap items-end gap-2">
            <input type="hidden" name="id" value={addon.id} />
            <input className="vhudi-input flex-1" name="addon" defaultValue={addon.addonType} />
            <input
              className="vhudi-input"
              name="price"
              type="number"
              step="0.01"
              defaultValue={addon.addonPrice}
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
      <td>{addon.addonType}</td>
      <td>{formatted}</td>
      <td>
        <button className="vhudi-btn mr-1" type="button" onClick={() => setEditing(true)}>
          Edit
        </button>
        <form action={deleteAction} className="inline">
          <input type="hidden" name="id" value={addon.id} />
          <button className="vhudi-btn" type="submit">
            Remove
          </button>
        </form>
      </td>
    </tr>
  );
}

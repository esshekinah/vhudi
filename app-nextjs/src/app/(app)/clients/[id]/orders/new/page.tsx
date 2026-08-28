import { notFound } from "next/navigation";
import { getPractice } from "@/lib/data/practices";
import { listLenses } from "@/lib/data/catalog";
import { RxGrid } from "@/components/RxGrid";
import { createOrderAction } from "../actions";

// new_order.php
export default async function NewOrderPage({
  params,
  searchParams,
}: {
  params: Promise<{ id: string }>;
  searchParams: Promise<{ err?: string }>;
}) {
  const { id } = await params;
  const { err } = await searchParams;
  const [practice, lenses] = await Promise.all([getPractice(id), listLenses()]);
  if (!practice) notFound();

  const lensOptions = lenses.map((l) => (
    <option key={l.id} value={l.id}>
      {l.itemVision} {l.itemType}
    </option>
  ));

  return (
    <section className="max-w-3xl">
      <h2 className="mb-4 text-xl font-semibold">New Order: {practice.surnameInitial}</h2>
      {err === "order" && (
        <p className="mb-4 rounded bg-red-600 px-3 py-2 text-center">Enter a valid, unique order number</p>
      )}
      <form action={createOrderAction} className="space-y-4 rounded bg-black/60 p-6">
        <input type="hidden" name="practiceId" value={practice.id} />

        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="Order No" name="order" required />
          <Field label="Date" name="date" type="date" />
          <label className="block">
            <span className="mb-1 block text-sm">Practice Name</span>
            <input className="vhudi-input" value={practice.surnameInitial} disabled />
          </label>
          <Field label="Patient" name="patient" />
          <Field label="Frame Type" name="ftype" />
          <Field label="Frame Price" name="fprice" type="number" />
        </div>

        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <label className="block">
            <span className="mb-1 block text-sm">Lens Type &amp; Vision RE</span>
            <select className="vhudi-input" name="rtype">
              <option value="">— select —</option>
              {lensOptions}
            </select>
          </label>
          <label className="block">
            <span className="mb-1 block text-sm">Lens Type &amp; Vision LE</span>
            <select className="vhudi-input" name="ltype">
              <option value="">— select —</option>
              {lensOptions}
            </select>
          </label>
        </div>

        <RxGrid />

        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="Tint" name="tint" />
          <Field label="Tint Price" name="tprice" />
          <Field label="Frame Details" name="fdetails" />
          <Field label="Special Instructions" name="special_instruction" />
        </div>

        <button className="vhudi-btn" type="submit">
          Add
        </button>
      </form>
    </section>
  );
}

function Field({
  label,
  name,
  type = "text",
  required,
}: {
  label: string;
  name: string;
  type?: string;
  required?: boolean;
}) {
  return (
    <label className="block">
      <span className="mb-1 block text-sm">{label}</span>
      <input className="vhudi-input" name={name} type={type} required={required} />
    </label>
  );
}

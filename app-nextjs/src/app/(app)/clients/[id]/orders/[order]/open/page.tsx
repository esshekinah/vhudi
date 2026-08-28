import { notFound } from "next/navigation";
import { getOrderByDbOrder } from "@/lib/data/orders";
import { getPractice } from "@/lib/data/practices";
import { listLenses } from "@/lib/data/catalog";
import { RxGrid } from "@/components/RxGrid";
import { updateOrderAction } from "../../actions";

// open.php — edit an existing order.
export default async function OpenOrderPage({
  params,
}: {
  params: Promise<{ id: string; order: string }>;
}) {
  const { id, order } = await params;
  const dbOrder = decodeURIComponent(order);
  const [record, practice, lenses] = await Promise.all([
    getOrderByDbOrder(dbOrder),
    getPractice(id),
    listLenses(),
  ]);
  if (!record || !practice) notFound();

  const lensOptions = lenses.map((l) => (
    <option key={l.id} value={l.id}>
      {l.itemVision} {l.itemType}
    </option>
  ));

  return (
    <section className="max-w-3xl">
      <h2 className="mb-1 text-xl font-semibold">Order No: {record.dbOrder}</h2>
      <p className="mb-4 rounded bg-green-700 px-3 py-1 text-center">{record.patient}</p>

      <form action={updateOrderAction} className="space-y-4 rounded bg-black/60 p-6">
        <input type="hidden" name="practiceId" value={practice.id} />
        <input type="hidden" name="order" value={record.dbOrder} />

        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <label className="block">
            <span className="mb-1 block text-sm">Order No</span>
            <input className="vhudi-input" value={record.dbOrder} readOnly />
          </label>
          <Field label="Date" name="date" type="date" defaultValue={record.date} />
          <label className="block">
            <span className="mb-1 block text-sm">Practice Name</span>
            <input className="vhudi-input" value={practice.surnameInitial} readOnly />
          </label>
          <Field label="Patient" name="patient" defaultValue={record.patient} />
          <Field label="Frame Type" name="ftype" defaultValue={record.frameType} />
          <Field label="Frame Price" name="fprice" type="number" defaultValue={String(record.framePrice)} />
        </div>

        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <label className="block">
            <span className="mb-1 block text-sm">
              RE: Lens Type &amp; Vision (current: {record.lensRight || "—"})
            </span>
            <select className="vhudi-input" name="rtype" defaultValue="">
              <option value="">— keep current —</option>
              {lensOptions}
            </select>
          </label>
          <label className="block">
            <span className="mb-1 block text-sm">
              LE: Lens Type &amp; Vision (current: {record.lensLeft || "—"})
            </span>
            <select className="vhudi-input" name="ltype" defaultValue="">
              <option value="">— keep current —</option>
              {lensOptions}
            </select>
          </label>
        </div>

        <RxGrid order={record} />

        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="Tint" name="tint" defaultValue={record.tint} />
          <Field label="Frame Details" name="fdetails" defaultValue={record.frameDetails} />
          <Field
            label="Special Instructions"
            name="special_instruction"
            defaultValue={record.specialInstruction}
          />
        </div>

        <button className="vhudi-btn" type="submit">
          Update
        </button>
      </form>
    </section>
  );
}

function Field({
  label,
  name,
  type = "text",
  defaultValue,
}: {
  label: string;
  name: string;
  type?: string;
  defaultValue?: string;
}) {
  return (
    <label className="block">
      <span className="mb-1 block text-sm">{label}</span>
      <input className="vhudi-input" name={name} type={type} defaultValue={defaultValue} />
    </label>
  );
}

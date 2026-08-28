import { notFound } from "next/navigation";
import { getPractice } from "@/lib/data/practices";
import { updatePracticeAction } from "../../actions";

// update.php
export default async function EditClientPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;
  const practice = await getPractice(id);
  if (!practice) notFound();

  return (
    <section className="max-w-2xl">
      <h2 className="mb-4 text-xl font-semibold">Update Client</h2>
      <form action={updatePracticeAction} className="space-y-4 rounded bg-black/60 p-6">
        <input type="hidden" name="id" value={practice.id} />
        <Field label="Surname & Initials" name="surname" defaultValue={practice.surnameInitial} />
        <Field label="Names" name="names" defaultValue={practice.names} />
        <Field label="Cell Number" name="cell" defaultValue={practice.cellNo} />
        <Field label="Email" name="email" defaultValue={practice.email} />
        <button className="vhudi-btn" type="submit">
          Update Client
        </button>
      </form>
    </section>
  );
}

function Field({
  label,
  name,
  defaultValue,
}: {
  label: string;
  name: string;
  defaultValue?: string;
}) {
  return (
    <label className="block">
      <span className="mb-1 block text-sm">{label}</span>
      <input className="vhudi-input" name={name} type="text" defaultValue={defaultValue} />
    </label>
  );
}

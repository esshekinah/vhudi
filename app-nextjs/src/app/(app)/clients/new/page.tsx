import { createPracticeAction } from "../actions";

// new-client.php
export default function NewClientPage() {
  return (
    <section className="max-w-2xl">
      <h2 className="mb-4 text-xl font-semibold">New Client</h2>
      <form action={createPracticeAction} className="space-y-4 rounded bg-black/60 p-6">
        <Field label="Surname" name="surname" required />
        <Field label="Names" name="names" />
        <Field label="Initials" name="initials" />
        <Field label="Cell Number" name="cell" />
        <Field label="Email" name="email" />
        <button className="vhudi-btn" type="submit">
          Add Client
        </button>
      </form>
    </section>
  );
}

function Field({ label, name, required }: { label: string; name: string; required?: boolean }) {
  return (
    <label className="block">
      <span className="mb-1 block text-sm">{label}</span>
      <input className="vhudi-input" name={name} type="text" required={required} />
    </label>
  );
}

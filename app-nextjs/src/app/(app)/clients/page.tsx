import Link from "next/link";
import { listPractices } from "@/lib/data/practices";
import { deletePracticeAction } from "./actions";

// all-clients.php
export default async function ClientsPage() {
  const practices = await listPractices();

  return (
    <section>
      <div className="mb-4 flex items-center justify-between">
        <h2 className="text-xl font-semibold">All Clients</h2>
        <Link href="/clients/new" className="vhudi-btn">
          New Client
        </Link>
      </div>

      <table className="vhudi-table">
        <thead>
          <tr>
            <th>Surname</th>
            <th>Names</th>
            <th>Id Number</th>
            <th>Cell Number</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          {practices.length === 0 && (
            <tr>
              <td colSpan={6}>No clients yet.</td>
            </tr>
          )}
          {practices.map((p) => (
            <tr key={p.id}>
              <td>{p.surname}</td>
              <td>{p.names}</td>
              <td>{p.idNumber}</td>
              <td>{p.cellNo}</td>
              <td>{p.email}</td>
              <td>
                <div className="flex flex-wrap gap-2">
                  <Link className="vhudi-btn" href={`/clients/${p.id}/orders/new`}>
                    New Order
                  </Link>
                  <Link className="vhudi-btn" href={`/clients/${p.id}/orders`}>
                    View Orders
                  </Link>
                  <Link className="vhudi-btn" href={`/clients/${p.id}/edit`}>
                    Edit
                  </Link>
                  <form action={deletePracticeAction}>
                    <input type="hidden" name="id" value={p.id} />
                    <button className="vhudi-btn" type="submit">
                      Remove
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  );
}

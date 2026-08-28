import Link from "next/link";
import { logoutAction } from "@/app/(app)/actions";

const links = [
  { href: "/invoice", label: "Invoice" },
  { href: "/clients", label: "Clients" },
  { href: "/products/lenses", label: "Products" },
];

export function Nav() {
  return (
    <nav className="no-print bg-black/70 border-b border-white/10">
      <div className="mx-auto max-w-6xl flex items-center gap-2 px-4 py-2 text-sm">
        {links.map((l) => (
          <Link
            key={l.href}
            href={l.href}
            className="px-3 py-2 text-white hover:text-vhudi-accent"
          >
            {l.label}
          </Link>
        ))}
        <div className="ml-auto">
          <form action={logoutAction}>
            <button type="submit" className="px-3 py-2 text-white hover:text-vhudi-accent">
              Logout
            </button>
          </form>
        </div>
      </div>
    </nav>
  );
}

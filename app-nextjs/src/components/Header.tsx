import Link from "next/link";

export function Header() {
  return (
    <header className="no-print bg-black text-white">
      <div className="mx-auto max-w-6xl px-4 py-5">
        <h1 className="text-2xl font-bold">
          <Link href="/clients">Vhudi Optical Dispensing</Link>
        </h1>
      </div>
    </header>
  );
}

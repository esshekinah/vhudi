import Link from "next/link";

export function ProductTabs({ active }: { active: "lenses" | "addons" }) {
  const cls = (k: string) =>
    `vhudi-btn ${active === k ? "" : "opacity-60"}`;
  return (
    <div className="no-print mb-4 flex gap-2">
      <Link className={cls("lenses")} href="/products/lenses">
        Lense and Vision
      </Link>
      <Link className={cls("addons")} href="/products/addons">
        Addons
      </Link>
    </div>
  );
}

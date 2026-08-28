import { redirect } from "next/navigation";
import { isAuthenticated } from "@/lib/auth";
import { Header } from "@/components/Header";
import { Nav } from "@/components/Nav";

export default async function AppLayout({ children }: { children: React.ReactNode }) {
  if (!(await isAuthenticated())) redirect("/login");
  return (
    <div>
      <Header />
      <Nav />
      <main className="mx-auto max-w-6xl px-4 py-6">{children}</main>
    </div>
  );
}

import { redirect } from "next/navigation";
import { isAuthenticated } from "@/lib/auth";

// index.php: if logged in -> clients, else -> login
export default async function Home() {
  if (await isAuthenticated()) redirect("/clients");
  redirect("/login");
}

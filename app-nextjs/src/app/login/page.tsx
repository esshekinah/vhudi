import { redirect } from "next/navigation";
import { checkCredentials, createSession, isAuthenticated } from "@/lib/auth";
import { Header } from "@/components/Header";

// Replaces index.php login form + POST handler.
export default async function LoginPage({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  if (await isAuthenticated()) redirect("/clients");
  const { error } = await searchParams;

  async function login(formData: FormData) {
    "use server";
    const email = String(formData.get("email") ?? "");
    const password = String(formData.get("password") ?? "");
    if (!checkCredentials(email, password)) {
      redirect("/login?error=1");
    }
    await createSession();
    redirect("/clients");
  }

  return (
    <div>
      <Header />
      <main className="mx-auto max-w-md px-4 py-16">
        <h2 className="mb-6 text-center text-xl font-semibold">Login</h2>
        {error && (
          <p className="mb-4 rounded bg-red-600 px-3 py-2 text-center text-white">
            Please enter the correct email &amp; password as registered
          </p>
        )}
        <form action={login} className="space-y-4 rounded bg-black/60 p-6">
          <input
            className="vhudi-input"
            name="email"
            type="email"
            placeholder="Email or Username"
            required
          />
          <input
            className="vhudi-input"
            name="password"
            type="password"
            placeholder="Password"
            required
          />
          <button className="vhudi-btn w-full" type="submit">
            Sign in
          </button>
        </form>
      </main>
    </div>
  );
}

import "server-only";
import { cookies } from "next/headers";
import { createHmac, timingSafeEqual } from "crypto";

/**
 * Lightweight signed-cookie session, replacing the legacy PHP $_SESSION['access'].
 * Credentials come from env (defaulting to the original hardcoded values so
 * behaviour matches the PHP app out of the box).
 */
const COOKIE = "vhudi_session";

function secret(): string {
  return process.env.SESSION_SECRET || "vhudi-dev-secret-change-me";
}

function sign(value: string): string {
  return createHmac("sha256", secret()).update(value).digest("hex");
}

function makeToken(): string {
  const payload = `access.${Date.now()}`;
  return `${payload}.${sign(payload)}`;
}

function verifyToken(token: string | undefined): boolean {
  if (!token) return false;
  const idx = token.lastIndexOf(".");
  if (idx < 0) return false;
  const payload = token.slice(0, idx);
  const sig = token.slice(idx + 1);
  const expected = sign(payload);
  try {
    return (
      sig.length === expected.length &&
      timingSafeEqual(Buffer.from(sig), Buffer.from(expected)) &&
      payload.startsWith("access.")
    );
  } catch {
    return false;
  }
}

/** index.php login check. */
export function checkCredentials(email: string, password: string): boolean {
  const expectedEmail = process.env.APP_LOGIN_EMAIL || "info@vhudi.co.za";
  const expectedPassword = process.env.APP_LOGIN_PASSWORD || "vhudi@2018";
  return email === expectedEmail && password === expectedPassword;
}

export async function createSession(): Promise<void> {
  const jar = await cookies();
  jar.set(COOKIE, makeToken(), {
    httpOnly: true,
    sameSite: "lax",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: 60 * 60 * 8, // 8 hours
  });
}

export async function destroySession(): Promise<void> {
  const jar = await cookies();
  jar.delete(COOKIE);
}

export async function isAuthenticated(): Promise<boolean> {
  const jar = await cookies();
  return verifyToken(jar.get(COOKIE)?.value);
}

export { COOKIE };

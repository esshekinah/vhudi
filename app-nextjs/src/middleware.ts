import { NextResponse, type NextRequest } from "next/server";

/**
 * Route guard mirroring the PHP `if(!isset($_SESSION['access'])) redirect index`.
 * Everything under the (app) group requires a session cookie; unauthenticated
 * users are bounced to /login.
 *
 * We only check for cookie presence here (Edge runtime can't verify the HMAC
 * without the crypto secret leaking to the edge bundle); full verification
 * happens in server components/actions via lib/auth.isAuthenticated().
 */
const PUBLIC_PATHS = ["/login"];

export function middleware(req: NextRequest) {
  const { pathname } = req.nextUrl;
  const hasSession = Boolean(req.cookies.get("vhudi_session")?.value);

  const isPublic = PUBLIC_PATHS.some((p) => pathname === p || pathname.startsWith(p + "/"));

  if (!hasSession && !isPublic) {
    const url = req.nextUrl.clone();
    url.pathname = "/login";
    return NextResponse.redirect(url);
  }

  if (hasSession && pathname === "/login") {
    const url = req.nextUrl.clone();
    url.pathname = "/clients";
    return NextResponse.redirect(url);
  }

  return NextResponse.next();
}

export const config = {
  // Protect everything except Next internals, static assets and the API auth route.
  matcher: ["/((?!_next/static|_next/image|favicon.ico|api/auth).*)"],
};

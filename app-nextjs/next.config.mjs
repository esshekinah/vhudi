/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  // firebase-admin uses node built-ins; keep it external on the server bundle
  serverExternalPackages: ["firebase-admin"],
};

export default nextConfig;

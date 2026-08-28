import "server-only";
import { cert, getApps, initializeApp, type App } from "firebase-admin/app";
import { getFirestore, type Firestore } from "firebase-admin/firestore";

/**
 * Server-side Firebase Admin singleton.
 * All Firestore reads/writes in the app go through this (bypasses security
 * rules), mirroring the original PHP which talked to MySQL directly.
 */
let app: App;

function getAdminApp(): App {
  if (getApps().length) return getApps()[0]!;

  const projectId = process.env.FIREBASE_PROJECT_ID;
  const clientEmail = process.env.FIREBASE_CLIENT_EMAIL;
  const privateKey = process.env.FIREBASE_PRIVATE_KEY?.replace(/\\n/g, "\n");

  if (!projectId || !clientEmail || !privateKey) {
    throw new Error(
      "Missing Firebase Admin credentials. Set FIREBASE_PROJECT_ID, FIREBASE_CLIENT_EMAIL and FIREBASE_PRIVATE_KEY (see .env.example)."
    );
  }

  app = initializeApp({
    credential: cert({ projectId, clientEmail, privateKey }),
  });
  return app;
}

export function db(): Firestore {
  const firestore = getFirestore(getAdminApp());
  return firestore;
}

export const collections = {
  practices: "practices",
  orders: "orders",
  lenses: "lenses",
  addons: "addons",
  orderAddons: "orderAddons",
  payments: "payments",
} as const;

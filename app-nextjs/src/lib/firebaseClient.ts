"use client";
import { getApps, initializeApp, type FirebaseApp } from "firebase/app";

/**
 * Optional client SDK init. The app currently performs all data access on the
 * server via firebase-admin, so this is only needed if you add client-side
 * Firebase features (e.g. Firebase Auth in the browser).
 */
export function getClientApp(): FirebaseApp {
  if (getApps().length) return getApps()[0]!;
  return initializeApp({
    apiKey: process.env.NEXT_PUBLIC_FIREBASE_API_KEY,
    authDomain: process.env.NEXT_PUBLIC_FIREBASE_AUTH_DOMAIN,
    projectId: process.env.NEXT_PUBLIC_FIREBASE_PROJECT_ID,
    storageBucket: process.env.NEXT_PUBLIC_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: process.env.NEXT_PUBLIC_FIREBASE_MESSAGING_SENDER_ID,
    appId: process.env.NEXT_PUBLIC_FIREBASE_APP_ID,
  });
}

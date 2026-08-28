import "server-only";
import { db, collections } from "../firebaseAdmin";
import { toNum } from "../money";
import type { Addon, Lens } from "../types";

/* ------------------------------- Lenses (price) ------------------------------ */

const lensCol = () => db().collection(collections.lenses);

function toLens(id: string, d: FirebaseFirestore.DocumentData): Lens {
  return {
    id,
    itemType: d.itemType ?? "",
    itemVision: d.itemVision ?? "",
    itemPrice: toNum(d.itemPrice),
  };
}

export async function listLenses(): Promise<Lens[]> {
  const snap = await lensCol().get();
  return snap.docs.map((doc) => toLens(doc.id, doc.data()));
}

export async function getLens(id: string): Promise<Lens | null> {
  const doc = await lensCol().doc(id).get();
  return doc.exists ? toLens(doc.id, doc.data()!) : null;
}

export async function createLens(input: {
  itemType: string;
  itemVision: string;
  itemPrice: number;
}): Promise<string> {
  const ref = await lensCol().add({ ...input, itemPrice: toNum(input.itemPrice) });
  return ref.id;
}

export async function updateLens(
  id: string,
  input: { itemType: string; itemVision: string; itemPrice: number }
): Promise<void> {
  await lensCol().doc(id).set({ ...input, itemPrice: toNum(input.itemPrice) }, { merge: true });
}

export async function deleteLens(id: string): Promise<void> {
  await lensCol().doc(id).delete();
}

/* -------------------------------- Addons (addon) ----------------------------- */

const addonCol = () => db().collection(collections.addons);

function toAddon(id: string, d: FirebaseFirestore.DocumentData): Addon {
  return {
    id,
    addonType: d.addonType ?? "",
    addonPrice: toNum(d.addonPrice),
  };
}

export async function listAddons(): Promise<Addon[]> {
  const snap = await addonCol().get();
  return snap.docs.map((doc) => toAddon(doc.id, doc.data()));
}

export async function getAddon(id: string): Promise<Addon | null> {
  const doc = await addonCol().doc(id).get();
  return doc.exists ? toAddon(doc.id, doc.data()!) : null;
}

export async function createAddon(input: {
  addonType: string;
  addonPrice: number;
}): Promise<string> {
  const ref = await addonCol().add({ ...input, addonPrice: toNum(input.addonPrice) });
  return ref.id;
}

export async function updateAddon(
  id: string,
  input: { addonType: string; addonPrice: number }
): Promise<void> {
  await addonCol().doc(id).set({ ...input, addonPrice: toNum(input.addonPrice) }, { merge: true });
}

export async function deleteAddon(id: string): Promise<void> {
  await addonCol().doc(id).delete();
}

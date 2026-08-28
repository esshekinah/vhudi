"use server";
import { revalidatePath } from "next/cache";
import {
  createAddon,
  createLens,
  deleteAddon,
  deleteLens,
  updateAddon,
  updateLens,
} from "@/lib/data/catalog";

/* -------- Lenses (lense-and-vision.php + lense-and-vision_update.php) -------- */

export async function createLensAction(formData: FormData) {
  await createLens({
    itemType: String(formData.get("item_type") ?? ""),
    itemVision: String(formData.get("item_vision") ?? ""),
    itemPrice: parseFloat(String(formData.get("price") ?? "0")) || 0,
  });
  revalidatePath("/products/lenses");
}

export async function updateLensAction(formData: FormData) {
  await updateLens(String(formData.get("id") ?? ""), {
    itemType: String(formData.get("item_type") ?? ""),
    itemVision: String(formData.get("item_vision") ?? ""),
    itemPrice: parseFloat(String(formData.get("price") ?? "0")) || 0,
  });
  revalidatePath("/products/lenses");
}

export async function deleteLensAction(formData: FormData) {
  await deleteLens(String(formData.get("id") ?? ""));
  revalidatePath("/products/lenses");
}

/* ------------------- Addons (addons.php + addons_update.php) ----------------- */

export async function createAddonAction(formData: FormData) {
  await createAddon({
    addonType: String(formData.get("addon") ?? ""),
    addonPrice: parseFloat(String(formData.get("price") ?? "0")) || 0,
  });
  revalidatePath("/products/addons");
}

export async function updateAddonAction(formData: FormData) {
  await updateAddon(String(formData.get("id") ?? ""), {
    addonType: String(formData.get("addon") ?? ""),
    addonPrice: parseFloat(String(formData.get("price") ?? "0")) || 0,
  });
  revalidatePath("/products/addons");
}

export async function deleteAddonAction(formData: FormData) {
  await deleteAddon(String(formData.get("id") ?? ""));
  revalidatePath("/products/addons");
}

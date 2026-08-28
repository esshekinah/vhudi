"use server";
import { revalidatePath } from "next/cache";
import { getAddon } from "@/lib/data/catalog";
import { addOrderAddon, removeOrderAddon } from "@/lib/data/orderAddons";

// addons2.php add
export async function addOrderAddonAction(formData: FormData) {
  const orderId = String(formData.get("orderId") ?? "");
  const practiceId = String(formData.get("practiceId") ?? "");
  const addonId = String(formData.get("addon") ?? "");
  const addon = await getAddon(addonId);
  if (addon) {
    await addOrderAddon({
      orderId,
      description: addon.addonType,
      price: addon.addonPrice,
    });
  }
  revalidatePath(`/clients/${practiceId}/orders/${encodeURIComponent(orderId)}/addons`);
}

// addons2.php remove
export async function removeOrderAddonAction(formData: FormData) {
  const removeId = String(formData.get("remove_id") ?? "");
  const orderId = String(formData.get("orderId") ?? "");
  const practiceId = String(formData.get("practiceId") ?? "");
  await removeOrderAddon(removeId);
  revalidatePath(`/clients/${practiceId}/orders/${encodeURIComponent(orderId)}/addons`);
}

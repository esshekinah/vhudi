"use server";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { getLens } from "@/lib/data/catalog";
import {
  createOrder,
  getOrderByDbOrder,
  orderNumberExists,
  updateOrder,
} from "@/lib/data/orders";

/**
 * new_order.php submit.
 * rtype = right-eye lens item id, ltype = left-eye lens item id.
 * lensRight = "type vision" of rtype ; priceRight = price(rtype)  [legacy LEFT]
 * lensLeft  = "type vision" of ltype ; priceLeft  = price(ltype)  [legacy RIGHT]
 */
export async function createOrderAction(formData: FormData) {
  const practiceId = String(formData.get("practiceId") ?? "");
  const dbOrder = String(formData.get("order") ?? "").trim();

  // Validation: order number required & unique (order() helper in PHP).
  if (!dbOrder || (await orderNumberExists(dbOrder))) {
    redirect(`/clients/${practiceId}/orders/new?err=order`);
  }

  const rtype = String(formData.get("rtype") ?? "");
  const ltype = String(formData.get("ltype") ?? "");
  const [rLens, lLens] = await Promise.all([
    rtype ? getLens(rtype) : Promise.resolve(null),
    ltype ? getLens(ltype) : Promise.resolve(null),
  ]);

  const desc = (l: Awaited<ReturnType<typeof getLens>>) =>
    l ? `${l.itemType} ${l.itemVision}`.trim() : "";

  await createOrder({
    dbOrder,
    practiceId,
    patient: String(formData.get("patient") ?? ""),
    date: String(formData.get("date") ?? ""),
    rSPH: String(formData.get("SPH") ?? ""),
    rCLYL: String(formData.get("CLYL") ?? ""),
    rAXIS: String(formData.get("AXIS") ?? ""),
    rPD: String(formData.get("PD") ?? ""),
    rSEG: String(formData.get("SEG") ?? ""),
    rRGD: String(formData.get("RGD") ?? ""),
    lSPH: String(formData.get("SPH2") ?? ""),
    lCLYL: String(formData.get("CLYL2") ?? ""),
    lAXIS: String(formData.get("AXIS2") ?? ""),
    lPD: String(formData.get("PD2") ?? ""),
    lSEG: String(formData.get("SEG2") ?? ""),
    lRGD: String(formData.get("RGD2") ?? ""),
    lensRight: desc(rLens),
    lensLeft: desc(lLens),
    priceRight: rLens?.itemPrice ?? 0,
    priceLeft: lLens?.itemPrice ?? 0,
    tint: String(formData.get("tint") ?? ""),
    tintPrice: String(formData.get("tprice") ?? ""),
    frameType: String(formData.get("ftype") ?? ""),
    framePrice: parseFloat(String(formData.get("fprice") ?? "0")) || 0,
    frameDetails: String(formData.get("fdetails") ?? ""),
    specialInstruction: String(formData.get("special_instruction") ?? ""),
    rStatus: "0",
    lStatus: "0",
  });

  revalidatePath(`/clients/${practiceId}/orders`);
  redirect(`/clients/${practiceId}/orders`);
}

/** open.php submit: UPDATE prescription WHERE db_order. */
export async function updateOrderAction(formData: FormData) {
  const practiceId = String(formData.get("practiceId") ?? "");
  const dbOrder = String(formData.get("order") ?? "");
  const existing = await getOrderByDbOrder(dbOrder);
  if (!existing) redirect(`/clients/${practiceId}/orders`);

  const rtype = String(formData.get("rtype") ?? "");
  const ltype = String(formData.get("ltype") ?? "");
  const [rLens, lLens] = await Promise.all([
    rtype ? getLens(rtype) : Promise.resolve(null),
    ltype ? getLens(ltype) : Promise.resolve(null),
  ]);
  const desc = (l: Awaited<ReturnType<typeof getLens>>) =>
    l ? `${l.itemType} ${l.itemVision}`.trim() : "";

  const patch: Record<string, unknown> = {
    patient: String(formData.get("patient") ?? ""),
    date: String(formData.get("date") ?? ""),
    frameType: String(formData.get("ftype") ?? ""),
    framePrice: parseFloat(String(formData.get("fprice") ?? "0")) || 0,
    rSPH: String(formData.get("SPH") ?? ""),
    rCLYL: String(formData.get("CLYL") ?? ""),
    rAXIS: String(formData.get("AXIS") ?? ""),
    rPD: String(formData.get("PD") ?? ""),
    rSEG: String(formData.get("SEG") ?? ""),
    rRGD: String(formData.get("RGD") ?? ""),
    lSPH: String(formData.get("SPH2") ?? ""),
    lCLYL: String(formData.get("CLYL2") ?? ""),
    lAXIS: String(formData.get("AXIS2") ?? ""),
    lPD: String(formData.get("PD2") ?? ""),
    lSEG: String(formData.get("SEG2") ?? ""),
    lRGD: String(formData.get("RGD2") ?? ""),
    tint: String(formData.get("tint") ?? ""),
    frameDetails: String(formData.get("fdetails") ?? ""),
    specialInstruction: String(formData.get("special_instruction") ?? ""),
  };
  // Only overwrite lens fields if a new lens was chosen.
  if (rLens) {
    patch.lensRight = desc(rLens);
    patch.priceRight = rLens.itemPrice;
  }
  if (lLens) {
    patch.lensLeft = desc(lLens);
    patch.priceLeft = lLens.itemPrice;
  }

  await updateOrder(existing.id, patch);
  revalidatePath(`/clients/${practiceId}/orders`);
  redirect(`/clients/${practiceId}/orders`);
}

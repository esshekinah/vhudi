"use server";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { payPractice } from "@/lib/data/orders";

// transaction.php pay handler — cascades payment across the practice's orders.
export async function payAction(formData: FormData) {
  const practiceId = String(formData.get("practiceId") ?? "");
  const payNow = parseFloat(String(formData.get("paynow") ?? "0")) || 0;
  const { change } = await payPractice(practiceId, payNow);
  revalidatePath(`/clients/${practiceId}/payments`);
  revalidatePath(`/clients/${practiceId}/orders`);
  redirect(`/clients/${practiceId}/payments?change=${change}`);
}

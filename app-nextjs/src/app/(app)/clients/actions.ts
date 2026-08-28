"use server";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import {
  createPractice,
  deletePractice,
  updatePractice,
} from "@/lib/data/practices";

export async function createPracticeAction(formData: FormData) {
  await createPractice({
    surname: String(formData.get("surname") ?? ""),
    names: String(formData.get("names") ?? ""),
    initials: String(formData.get("initials") ?? ""),
    cellNo: String(formData.get("cell") ?? ""),
    email: String(formData.get("email") ?? ""),
  });
  revalidatePath("/clients");
  redirect("/clients");
}

export async function updatePracticeAction(formData: FormData) {
  const id = String(formData.get("id") ?? "");
  await updatePractice(id, {
    surnameInitial: String(formData.get("surname") ?? ""),
    names: String(formData.get("names") ?? ""),
    cellNo: String(formData.get("cell") ?? ""),
    email: String(formData.get("email") ?? ""),
  });
  revalidatePath("/clients");
  redirect("/clients");
}

export async function deletePracticeAction(formData: FormData) {
  const id = String(formData.get("id") ?? "");
  await deletePractice(id);
  revalidatePath("/clients");
  redirect("/clients");
}

import "server-only";
import { db, collections } from "../firebaseAdmin";
import type { Practice } from "../types";

const col = () => db().collection(collections.practices);

function toPractice(id: string, d: FirebaseFirestore.DocumentData): Practice {
  return {
    id,
    surname: d.surname ?? "",
    names: d.names ?? "",
    cellNo: d.cellNo ?? "",
    email: d.email ?? "",
    idNumber: d.idNumber ?? "",
    surnameInitial: d.surnameInitial ?? "",
  };
}

/** all-clients.php: SELECT * FROM doctors */
export async function listPractices(): Promise<Practice[]> {
  const snap = await col().orderBy("surname").get();
  return snap.docs.map((doc) => toPractice(doc.id, doc.data()));
}

/** get_doctor($pk) — returns the display name (surnameInitial). */
export async function getPractice(id: string): Promise<Practice | null> {
  const doc = await col().doc(id).get();
  return doc.exists ? toPractice(doc.id, doc.data()!) : null;
}

/** new-client.php INSERT. surnameInitial = surname + ' ' + initials. */
export async function createPractice(input: {
  surname: string;
  names: string;
  initials: string;
  cellNo: string;
  email: string;
}): Promise<string> {
  const surnameInitial = `${input.surname} ${input.initials}`.trim();
  const ref = await col().add({
    surname: input.surname,
    names: input.names,
    cellNo: input.cellNo,
    email: input.email,
    idNumber: "",
    surnameInitial,
  });
  return ref.id;
}

/** update.php UPDATE doctors SET names,cellNo,email,surnameInitial WHERE pk. */
export async function updatePractice(
  id: string,
  input: { names: string; cellNo: string; email: string; surnameInitial: string }
): Promise<void> {
  await col().doc(id).set(
    {
      names: input.names,
      cellNo: input.cellNo,
      email: input.email,
      surnameInitial: input.surnameInitial.trim(),
    },
    { merge: true }
  );
}

/** remove.php — delete a practice. */
export async function deletePractice(id: string): Promise<void> {
  await col().doc(id).delete();
}

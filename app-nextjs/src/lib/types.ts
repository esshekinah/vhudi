/**
 * Domain types. Field names are the modernised equivalents of the legacy
 * MySQL columns (mapping is documented in DATA_MODEL.md).
 */

/** practices  (legacy table: doctors) */
export interface Practice {
  id: string; // Firestore doc id === legacy doctors.pk (as string)
  surname: string;
  names: string;
  cellNo: string;
  email: string;
  idNumber: string; // legacy doctors.id (the display "Id Number", not the PK)
  surnameInitial: string; // legacy surname_intial, e.g. "MASINDI N R"
}

/** orders  (legacy table: prescription) */
export interface Order {
  id: string; // Firestore doc id (we use dbOrder for stable linking; see notes)
  myCount: number; // legacy sequential invoice counter (MAX+1)
  dbOrder: string; // human order number — the key used across addons/payments
  practiceId: string; // legacy db_practice === doctors.pk
  patient: string;
  date: string; // 'YYYY-MM-DD'

  // Right eye (RE)
  rSPH: string;
  rCLYL: string;
  rAXIS: string;
  rPD: string;
  rSEG: string;
  rRGD: string;

  // Left eye (LE)
  lSPH: string;
  lCLYL: string;
  lAXIS: string;
  lPD: string;
  lSEG: string;
  lRGD: string;

  // Lens descriptions.
  // NOTE: the legacy code stores the RIGHT-lens description in `db_lens`
  // and the LEFT-lens description in `lens_left`, and the RIGHT-lens PRICE
  // in `LEFT` and the LEFT-lens PRICE in `RIGHT`. We normalise the naming
  // here but keep the exact same cost math (see money.ts).
  lensRight: string; // legacy db_lens
  lensLeft: string; // legacy lens_left
  priceRight: number; // legacy `LEFT`  (price of the right lens)
  priceLeft: number; // legacy `RIGHT` (price of the left lens)

  tint: string;
  tintPrice: string;
  frameType: string;
  framePrice: number;
  frameDetails: string;
  specialInstruction: string;

  rStatus: string; // legacy db_Rstatus (shown as "Status")
  lStatus: string;
}

/** lenses  (legacy table: price) */
export interface Lens {
  id: string; // legacy price.Item_No
  itemType: string;
  itemVision: string;
  itemPrice: number;
}

/** addons  (legacy table: addon) */
export interface Addon {
  id: string; // legacy addon.addon_No
  addonType: string;
  addonPrice: number;
}

/** orderAddons  (legacy table: addon_clients) */
export interface OrderAddon {
  id: string; // legacy addon_clients.id
  orderId: string; // legacy order_id === Order.dbOrder
  description: string;
  price: number;
}

/** payments  (legacy table: transaction) */
export interface Payment {
  id: string;
  orderId: string; // legacy order_id === Order.dbOrder
  date: string; // ISO datetime
  amount: number;
}

/** A row shown in order/invoice tables with computed money figures. */
export interface OrderSummary extends Order {
  practiceName: string;
  total: number;
  paid: number;
  due: number;
}

# Data model: MySQL → Firestore

Only the 5 tables the legacy app actually used are migrated. Legacy/dead tables
(`account`, `clients`, `extra`, `extra_tint`, `lense`, `my_price`, `invoice`,
`checkout(s)`, `ertra_value`, `logd`) are dropped.

Document IDs preserve the legacy primary keys so relationships keep working.

## `doctors` → `practices`

| MySQL column | Firestore field | Notes |
|---|---|---|
| `pk` | **document id** | The real key. `orders.practiceId` points at this. |
| `surname` | `surname` | |
| `names` | `names` | |
| `cell no` | `cellNo` | space in original column name |
| `email` | `email` | |
| `id` | `idNumber` | display "Id Number", **not** the PK |
| `surname_intial` | `surnameInitial` | display name, e.g. `MASINDI N R` |

## `price` → `lenses`

| MySQL column | Firestore field |
|---|---|
| `Item_No` | **document id** |
| `Item_Type` | `itemType` |
| `Item_Vision` | `itemVision` |
| `Item_Price` | `itemPrice` (number) |

## `addon` → `addons`

| MySQL column | Firestore field |
|---|---|
| `addon_No` | **document id** |
| `addon_Type` | `addonType` |
| `addon_Price` | `addonPrice` (number) |

## `addon_clients` → `orderAddons`

| MySQL column | Firestore field | Notes |
|---|---|---|
| `id` | **document id** | |
| `order_id` | `orderId` | = `orders.dbOrder` (a string code, not a numeric FK) |
| `description` | `description` | |
| `price` | `price` (number) | |

## `transaction` → `payments`

| MySQL column | Firestore field | Notes |
|---|---|---|
| `id` | **document id** | |
| `order_id` | `orderId` | = `orders.dbOrder` |
| `date` | `date` | ISO datetime |
| `amount` | `amount` (number) | |

## `prescription` → `orders`

The single most important table. Legacy naming is confusing; here is the exact
mapping used by both the app and the migration script.

| MySQL column | Firestore field | Notes |
|---|---|---|
| `db_order` | `dbOrder` + **document id** | human order number; joins for addons/payments |
| `myCount` | `myCount` (number) | app-managed sequence = `MAX(myCount)+1`; shown as "Invoice No" |
| `db_practice` | `practiceId` | = `practices` doc id (`doctors.pk`) |
| `db_patient` | `patient` | |
| `db_date` | `date` | `YYYY-MM-DD` |
| `db_lens` | `lensRight` | RIGHT-eye lens **description** |
| `lens_left` | `lensLeft` | LEFT-eye lens **description** |
| `LEFT` | `priceRight` (number) | RIGHT-eye lens **price** (reserved word in SQL!) |
| `RIGHT` | `priceLeft` (number) | LEFT-eye lens **price** |
| `db_rSPH` … `db_rRGD` | `rSPH`,`rCLYL`,`rAXIS`,`rPD`,`rSEG`,`rRGD` | right eye Rx |
| `db_lSPH` … `db_lRGD` | `lSPH`,`lCLYL`,`lAXIS`,`lPD`,`lSEG`,`lRGD` | left eye Rx |
| `db_TINT` | `tint` | |
| `db_FrameType` | `frameType` | |
| `db_FramePrice` | `framePrice` (number) | |
| `db_FrameD` | `frameDetails` | |
| `db_sInstruction` | `specialInstruction` | |
| `db_Rstatus` | `rStatus` | shown as "Status" |
| `db_Lstatus` | `lStatus` | |

> ⚠️ The legacy `LEFT`/`RIGHT` columns store **prices**, and the two lens
> *descriptions* are cross-stored (`db_lens` = right, `lens_left` = left). The
> port keeps the exact same arithmetic:
>
> ```
> orderTotal = priceRight + priceLeft + Σ orderAddons.price
> paid       = Σ payments.amount   (where orderId == dbOrder)
> due        = orderTotal − paid
> ```

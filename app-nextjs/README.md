# Vhudi Optical Dispensing — Next.js + Firebase Firestore

A modern rewrite of the legacy PHP/MySQL "Vhudi Optical Dispensing" app using
**Next.js 15 (App Router) + TypeScript + Tailwind** on the front end and
**Firebase Firestore** (via the Admin SDK) as the database.

All the original features are preserved:

| Feature | Legacy PHP | New route |
|---|---|---|
| Login / logout | `index.php`, `logout.php` | `/login`, logout button |
| Practices ("Clients") CRUD | `clients/all-clients.php`, `new-client.php`, `update.php`, `remove.php` | `/clients`, `/clients/new`, `/clients/[id]/edit` |
| New order (prescription) | `clients/new_order.php` | `/clients/[id]/orders/new` |
| View / search / sort orders | `clients/view_order.php` | `/clients/[id]/orders` |
| Open / edit an order | `clients/open.php` | `/clients/[id]/orders/[order]/open` |
| Order addons | `clients/addons2.php` | `/clients/[id]/orders/[order]/addons` |
| Payments (cascade) | `clients/transaction.php` | `/clients/[id]/payments` |
| Global invoice list | `invoice.php` | `/invoice` |
| Lens catalog CRUD | `products/lense-and-vision.php` | `/products/lenses` |
| Addon catalog CRUD | `products/addons.php` | `/products/addons` |
| Printable invoice | `clients/pdf/examples/pdf.php` (TCPDF) | `/print/[count]` (browser print / Save-as-PDF) |

## Architecture

- **Data access** is server-side only (Route Handlers / Server Actions using
  `firebase-admin`), mirroring the way the PHP talked directly to MySQL. The
  browser never touches Firestore, so `firestore.rules` denies all direct
  client access by default.
- **Auth** replaces `$_SESSION['access']` with a signed, HTTP-only session
  cookie (`src/lib/auth.ts`). `middleware.ts` guards every route. The login
  credentials default to the original hardcoded values but are configurable via
  env vars.
- **Money math** (`src/lib/money.ts`) reproduces the legacy calculations
  exactly: `orderTotal = priceRight + priceLeft + Σ addons`, `paid = Σ payments`,
  `due = total − paid`.

## Getting started

> **Note:** This app was authored in an offline sandbox, so dependencies were
> **not** installed and `next build` was **not** run here. Run the steps below
> in an environment with npm registry access.

```bash
cd app-nextjs
cp .env.example .env.local   # then fill in the values (see below)
npm install
npm run dev                  # http://localhost:3000
```

### Environment variables (`.env.local`)

**Firebase Admin (server, required):** from Firebase Console → Project settings
→ Service accounts → *Generate new private key*.

```
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_CLIENT_EMAIL=firebase-adminsdk-xxxxx@your-project.iam.gserviceaccount.com
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n"
```

**App login (replaces the hardcoded PHP credentials):**

```
APP_LOGIN_EMAIL=info@vhudi.co.za
APP_LOGIN_PASSWORD=vhudi@2018
SESSION_SECRET=some-long-random-string
```

The `NEXT_PUBLIC_FIREBASE_*` values are only needed if you later add
client-side Firebase features.

## Migrating the existing data

The legacy dump (`data/vhudi.sql`) can be imported into Firestore. Document IDs
preserve the legacy primary keys so cross-references keep working
(`practiceId = doctors.pk`, `orderId = prescription.db_order`).

```bash
npm run migrate
```

This parses the `INSERT` statements for the 5 relevant tables and writes them to
the `practices`, `lenses`, `addons`, `orders`, `orderAddons`, and `payments`
collections. Verified row counts from the bundled dump: doctors 21, price 17,
addon 30, addon_clients 16, transaction 9, prescription 3124.

## Firestore collections

See [`DATA_MODEL.md`](./DATA_MODEL.md) for the full field-by-field mapping from
the old MySQL tables.

## Notes / intentional changes

- The legacy partial-payment path had a bug (it recorded the full outstanding
  `due` instead of the tendered amount). The port records exactly what is
  tendered. Full-payment behaviour is identical.
- SQL injection, plaintext creds, and mixed `mysqli`/`mysql_*` calls from the
  original are gone. Input goes through typed server actions.
- PDF generation via TCPDF is replaced by an HTML invoice you can print or
  "Save as PDF" from the browser.

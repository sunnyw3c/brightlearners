# Routes and screens

From Appendix C of the source plan, plus the routes introduced inside each phase, mapped to the screens in the mockup ([../design/ui-mockup.png](../design/ui-mockup.png)).

## URL rules

- Class slugs are stored with their prefix: `class-1`, `class-2`, `class-3`. The route parameter is constrained so it cannot swallow other top-level pages:
  `Route::get('/{class}', ...)->where('class', 'class-[a-z0-9-]+')`.
- Canonical URLs are human-readable and never contain database IDs.
- Filter and sort combinations use query strings and are `noindex` unless promoted to a real landing page.
- A published slug that changes gets a row in `redirects`.
- Every route has a name. Link with named routes or the Wayfinder helpers.

## Public discovery

| Route | Name | Purpose | Phase | Mockup screen |
|---|---|---|---|---|
| `/` | `home` | Homepage: class selector, value proposition, free / paid / member paths | 1 (placeholder), 5 | Home |
| `/learn` | `learn.index` | Browse the curriculum | 3 (shell), 5 | — |
| `/class-{slug}` | `learn.class` | Class landing | 5 | Class 2 Learning Resources |
| `/class-{slug}/{subject}` | `learn.subject` | Subject landing | 5 | Class page with a subject chip selected |
| `/class-{slug}/{subject}/{topic}` | `learn.topic` | Topic landing | 5 | — |
| `/free` | `free.index` | Free resource hub with filters | 5 | "Resources" in the top nav |
| `/free/{class}/{subject}/{slug}` | `free.show` | Free resource detail, preview, download | 5 | Addition Practice Worksheet |
| `/search?q=` | `search` | Unified search | 5 | Search icon in the header |

## Shop

| Route | Name | Purpose | Phase | Mockup screen |
|---|---|---|---|---|
| `/shop` | `shop.index` | Commercial catalogue with filters | 6 | "Workbooks" in the top nav |
| `/shop/{type}` | `shop.type` | Product-type landing (`topic-packs`, `workbooks`, `ebooks`, `holiday-packs`, `revision-packs`, `bundles`) | 6 | — |
| `/shop/{class}/{slug}` | `shop.show` | Canonical product detail | 6 | Maths Mastery Workbook |
| `/products/{slug}` | `products.alias` | Optional alias, 301 to the canonical URL | 6 | — |

## Membership

| Route | Name | Purpose | Phase | Mockup screen |
|---|---|---|---|---|
| `/membership` | `membership.index` | Offer, inclusions, exclusions, price, renewal behaviour | 10 | Weekly Learning Programme (90 Days) |
| `/membership/sample-month` | `membership.sample` | A representative month, public | 10 | — |
| `/membership/manage` | `membership.manage` | Manage or cancel a recurring plan | 17 | — |

## Content

| Route | Name | Purpose | Phase | Mockup screen |
|---|---|---|---|---|
| `/parents` | `parents.index` | Parent Hub | 12 | "Parent Hub" in the top nav |
| `/parents/{category}` | `parents.category` | Guide category | 12 | — |
| `/blog/{slug}` | `articles.show` | Article | 12 | — |

## Commerce and payment

| Route | Method | Name | Purpose | Phase | Mockup screen |
|---|---|---|---|---|---|
| `/cart` | GET | `cart.show` | View and edit the cart | 7 | Your Cart |
| `/cart/items` | POST, DELETE | `cart.items.*` | Add or remove a product | 7 | Add to Cart |
| `/cart/coupon` | POST, DELETE | `cart.coupon.*` | Apply or remove a coupon | 7 | "Have a coupon code?" |
| `/checkout` | GET | `checkout.show` | Validated checkout (login required) | 7 | Proceed to Checkout |
| `/checkout/payment` | POST | `checkout.payment` | Create the internal order and the Razorpay order; return the checkout payload | 8 | Secure Payment |
| `/payments/razorpay/callback` | POST | `payments.razorpay.callback` | Verify the browser payment result | 8 | — |
| `/webhooks/razorpay` | POST | `webhooks.razorpay` | Signed raw webhook endpoint, no CSRF, no session | 8 | — |
| `/order/{order}/success` | GET | `orders.success` | Order result, owner only | 8 | — |
| `/order/{order}/payment-pending` | GET | `orders.pending` | Pending / reconciliation state | 8 | — |

## Parent account (login required)

| Route | Name | Purpose | Phase | Mockup screen |
|---|---|---|---|---|
| `/register` | `register` | Parent account creation | 1–2 | Sign Up |
| `/login` | `login` | Parent login | 1–2 | Login |
| `/forgot-password` | `password.request` | Password reset request | 1–2 | — |
| `/account/dashboard` | `account.dashboard` | Current learning and purchases summary | 11 | Hello, Priya! |
| `/account/settings` | `account.settings` | Name, email, password, 2FA, notification preferences | 2 | "Profile" in the sidebar |
| `/account/profiles` | `account.profiles.index` | Manage learning profiles | 2 | "Add Child" |
| `/account/profiles/{profile}/weekly-learning` | `account.profiles.weekly-learning` | Current learning for one child | 11 | Weekly Programme cards |
| `/account/weekly-learning` | `account.weekly-learning` | Current released programme content | 10 | "My Learning" in the sidebar |
| `/account/library` | `account.library.index` | Purchased, membership and free resources | 9 | "Downloads" in the sidebar |
| `/account/library/{resource}` | `account.library.show` | Library detail | 9 | — |
| `/download/{resource}` | `downloads.show` | Access check, then a temporary URL. No login needed for a free resource. | 5 (free), 9 (paid and member) | Download buttons |
| `/account/progress` | `account.progress` | Simple engagement history | 11 | "Progress" in the sidebar |
| `/account/orders` | `account.orders.index` | Order history | 7 | "Orders" in the sidebar |
| `/account/orders/{order}` | `account.orders.show` | Order detail, owner only | 7 | — |
| `/account/membership` | `account.membership` | Membership status and history | 10 | "Membership" in the sidebar |
| `/account/notifications` | `account.notifications` | Optional in-app list | 11 | Bell icon |
| `/account/assessments` | `account.assessments` | Assessment history | 17 | — |
| `/account/recommendations` | `account.recommendations` | Suggested next learning | 17 | — |
| `/assessment/{slug}` | `assessments.show` | Starter or skill check | 17 | — |

The starter kit creates its own `/dashboard` and `/settings/*` routes. Phase 2 moves them under `/account/*` so they match this table.

## Admin and infrastructure

| Route | Purpose | Phase |
|---|---|---|
| `/admin` | Filament staff area. Has its own login page. | 1 |
| `/up` | Laravel's built-in liveness check (already in `bootstrap/app.php`) | exists |
| `/health` | Minimal health check for uptime monitoring: database and cache reachable. No versions, paths or secrets in the response. | 1 |
| `/sitemap.xml` | Sitemap index | 12 |
| `/robots.txt` | Robots policy, generated per environment | 12 |

## Mockup navigation

| Mockup label | Goes to |
|---|---|
| Home | `/` |
| Resources | `/free` |
| Workbooks | `/shop` |
| Membership | `/membership` |
| Parent Hub | `/parents` |
| Search icon | `/search` |
| Login / Sign Up | `/login`, `/register` |
| Cart icon | `/cart` |

Dashboard sidebar in the mockup:

| Mockup label | Goes to |
|---|---|
| Dashboard | `/account/dashboard` |
| My Learning | `/account/weekly-learning` |
| Downloads | `/account/library` |
| Membership | `/account/membership` |
| Orders | `/account/orders` |
| Progress | `/account/progress` |
| Profile | `/account/settings` and `/account/profiles` |
| Log Out | logout |

See [design-reference.md](design-reference.md) for what each mockup screen contains and where it differs from the plan.

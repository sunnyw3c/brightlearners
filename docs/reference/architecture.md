# Architecture and code conventions

From Appendix A of the source plan, adapted to this repo. Read this before writing code in any phase.

## Topology

```
Browser / Parent / Staff
        |
  CDN / reverse proxy
        |
 -------------------------
 | Laravel HTTP + Inertia |----> Node SSR process
 -------------------------
     |          |
   MySQL      Redis ----> queue workers (Horizon on Linux)
     |          |
     |       Scheduler
     |
 Private object storage ----> short-lived signed downloads
     |
 CDN for public previews/images

External: Razorpay | Email provider | Analytics / Search Console | Uptime and error monitoring
```

The public site, the parent account, the business logic and the admin all live in one Laravel application and one database. This avoids duplicated authentication and inconsistent business rules.

## Domain boundaries

| Domain | Owns | Must not own |
|---|---|---|
| Accounts | Users, learning profiles, auth preferences | Product pricing or resource publishing |
| Curriculum | Classes, subjects, topics, skills | Orders or payments |
| Content | Resources, versions, reviews, previews | Commercial price |
| Catalog | Products, bundles, merchandising | Payment verification |
| Commerce | Cart, pricing, coupons, orders | Educational review |
| Payments | Gateway records, verification, refunds, webhooks | The catalogue price (it only reads the order total) |
| Access | Entitlements, download decisions | The payment gateway state machine |
| Membership | Plans, subscriptions, programmes, releases | Resource files |
| Learning | Progress, assessments, recommendations | Financial status |
| Growth | Articles, SEO, redirects | Core authorization |
| Analytics | Events, daily metrics | Anything another domain needs to function |

A domain may read another domain's models. It must not write another domain's tables directly; it calls that domain's action or listens to its event.

## Folder layout

```
app/
  Domains/
    Accounts/      Actions/ Models/ Policies/ Services/ Enums/ Events/ Listeners/
    Curriculum/
    Content/
    Catalog/
    Commerce/
    Payments/
    Access/
    Membership/
    Learning/
    Growth/
    Analytics/
  Filament/        Resources/ Pages/ Widgets/   (admin only)
  Http/
    Controllers/   Middleware/   Requests/
  Jobs/
  Models/
    User.php       (stays here, see below)
  Notifications/
  Support/         (Money, small helpers)
resources/js/
  pages/  components/  layouts/  features/
routes/
  web.php  console.php      (api.php only when Phase 18 needs it)
```

Each domain only creates the sub-folders it needs.

## Conventions

### 1. `User` stays in `app/Models`

The starter kit, Fortify, Filament and `config/auth.php` all expect `App\Models\User`. Leave it there and treat it as part of the Accounts domain. Every other model goes in its domain.

### 2. Creating classes inside a domain

Artisan accepts a full path that starts with `App/`:

```bash
php artisan make:model App/Domains/Curriculum/Models/Subject --no-interaction
php artisan make:class App/Domains/Commerce/Actions/CalculateOrderTotal --no-interaction
php artisan make:policy App/Domains/Content/Policies/LearningResourcePolicy --no-interaction
php artisan make:event App/Domains/Commerce/Events/OrderPaid --no-interaction
php artisan make:enum App/Domains/Content/Enums/ResourceStatus --string --no-interaction
```

Create migrations, factories and tests separately so their names are right:

```bash
php artisan make:migration create_subjects_table --no-interaction
php artisan make:factory SubjectFactory --no-interaction
php artisan make:test --pest Curriculum/SubjectTest --no-interaction
```

Run `php artisan make:<thing> --help` first if an option is unclear.

### 3. Models

Follow the style already used in `app/Models/User.php`: PHP attributes instead of properties.

```php
#[Table('classes')]
#[Fillable(['name', 'slug', 'sort_order', 'active'])]
#[UseFactory(SchoolClassFactory::class)]
#[UsePolicy(SchoolClassPolicy::class)]
class SchoolClass extends Model
```

- Because models are not in `App\Models`, Laravel cannot guess their factory or policy. Always declare `#[UseFactory]` and `#[UsePolicy]` on the model, and `#[UseModel(SchoolClass::class)]` (or `protected $model`) on the factory.
- Two table names cannot be used as class names:
  - `class` is a reserved word in PHP. The model for the `classes` table is `SchoolClass`. Foreign keys are still `class_id`, and the relationship method is `schoolClass()`.
  - `resource` is a soft-reserved word in PHP, and `Resource` also collides with Filament's and Laravel's own `Resource` classes. The model for the `resources` table is `LearningResource`. Foreign keys are still `resource_id`.
- Use `casts()` for dates, booleans, enums and JSON.
- Every model gets a factory and, where it holds launch data, a seeder.

### 4. Enums

String-backed, TitleCase case names, snake_case values:

```php
enum OrderStatus: string
{
    case PendingPayment = 'pending_payment';
    case Paid = 'paid';
}
```

### 5. Actions hold the business logic

One class per business operation, one public `handle()` method, dependencies through the constructor. Controllers, Filament actions, jobs and listeners all call the same action.

Examples from the source plan: `CreateCartOrder`, `CalculateOrderTotal`, `ApplyCoupon`, `CreateRazorpayOrder`, `VerifyRazorpayCallback`, `ProcessRazorpayWebhook`, `MarkOrderPaid`, `GrantPurchasedEntitlements`, `CanAccessResource`, `GenerateSignedDownload`, `ActivateMembership`, `ReleaseLearningWeek`, `PublishResourceVersion`, `AggregateDailyMetrics`.

Controllers coordinate. They must not contain pricing, payment, entitlement or publishing logic.

### 6. Validation and authorization

- Validation lives in Form Request classes under `app/Http/Requests`.
- Authorization lives in policies and permission checks on the server. Hiding a button is never the only protection.
- Any route that takes an ID belonging to a user (order, profile, download) must have a test where another user tries it and fails.

### 7. Money

- Every amount in the database is an **unsigned integer in paise**. ₹199 is stored as `19900`. Razorpay uses the same unit.
- Never use `float` for money.
- `App\Support\Money` formats paise for display.
- Money sent to React is always an object: `{ paise: 19900, formatted: "₹199" }`. React never does price arithmetic.
- Every money table also has a `currency` column, `INR` for now.

### 8. Files

- Never write a filesystem path into business code. Always go through `Storage::disk(...)`.
- Two disks are configured in Phase 4:
  - `resources` — private. Source PDFs, low-ink variants, answer keys.
  - `previews` — public. Preview page images and product covers.
- Locally both disks sit under `storage/app`. In staging and production they are S3-compatible buckets.
- A protected file is only ever delivered as a short-lived temporary URL created after an access check.

### 9. Access rule

`App\Domains\Access\Services\AccessService::canAccess($user, $resource)` is the only place that decides access. It returns a decision object with the reason, checked in this order:

1. Resource is not published → deny.
2. Resource is marked free → allow.
3. User holds a live entitlement row (purchase or admin grant) → allow.
4. User holds a membership that covers a released programme week containing this resource → allow.
5. Otherwise → deny.

Rule 4 is computed from `subscriptions` and `learning_weeks`; it is not copied into `entitlements` rows. See decision T-07 in [../tracking/decisions.md](../tracking/decisions.md).

### 10. Events, listeners, jobs

- A domain announces something with an event (`OrderPaid`). Other domains react in queued listeners (`GrantPurchasedEntitlements`).
- Anything slow is a queued job: email, preview generation, indexing, aggregation, webhook processing.
- Anything triggered by a payment must be safe to run twice.
- Queue names: `default`, `payments`, `media`, `notifications`.

### 11. Admin

- Filament classes live in `app/Filament`, grouped by navigation group: Content, Commerce, Membership, Customers, Marketing, Support, System.
- A Filament resource points at a domain model through its `$model` property. It calls domain actions for anything beyond simple field edits.
- `User::canAccessPanel()` allows only users with a staff role.
- Filament is Livewire; the public site is Inertia/React. They share the database and the `web` guard, nothing else.

### 12. Frontend

- `resources/js/pages` — one file per Inertia page. `layouts` — public, account and auth layouts. `components` — shared UI. `features` — code for one feature area (cart, checkout, library).
- TypeScript everywhere. Props for each page are typed.
- Link with the route helpers generated by Wayfinder (shipped with the starter kit) or named routes. Do not hard-code URLs.
- SSR rule: code that runs at import time must not touch `window`, `document` or `localStorage`. Put browser-only code inside `useEffect`.
- Public pages set their title, meta description and canonical through Inertia's `<Head>`, so they are present in the server-rendered HTML.
- Check for an existing component before writing a new one.

### 13. Tests

- Pest. Most tests are feature tests. Create with `php artisan make:test --pest <Name>`.
- Group by domain: `tests/Feature/Commerce/CouponLimitTest.php`.
- Build data with factories and their states.
- The suite runs on MySQL, so locking and JSON behaviour match production.
- Time-dependent rules (membership expiry, release dates, coupon windows) use Laravel's time travel helpers.
- Run the smallest set that covers the change: `php artisan test --compact --filter=CouponLimit`.

### 14. Before finishing any change

```bash
vendor/bin/pint --dirty --format agent
php artisan test --compact <the tests you touched>
```

## What not to do

- No microservices, Kubernetes or event bus for the MVP.
- No duplicated PDF files per product.
- No paid file under `public/`.
- No price, discount or total calculated in the browser.
- No overwrite of a published file.
- No child email, phone, school, date of birth or address.
- No new Composer or npm dependency without it being named in a phase file or approved.

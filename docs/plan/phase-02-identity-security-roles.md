# Phase 2 — Identity, security & roles

Create parent authentication, staff role-based access and minimal learner profiles.

| | |
|---|---|
| Workstream | A. Product foundation |
| Duration | ~1 week |
| Depends on | Phase 1 foundation deployed |
| Exit gate | Parents and staff authenticate safely. Role boundaries are enforced. Child data is minimised. |
| Backlog | P2-01, P2-02, P2-03, P2-04 |
| Decide first | D-13 what an unverified parent may do. D-15 what "limited" refund means for Support. Both are `Default in use` in [../tracking/decisions.md](../tracking/decisions.md) as of 2026-10-05: the recommended defaults were applied without a formal business sign-off. |

## Objective

Build the identity model around the parent or guardian as the account holder, with a lightweight learning profile for each child.

## Before you start

- The starter kit from Phase 1 already provides register, login, email verification, password reset and two-factor authentication through Fortify. This phase adapts them; it does not rebuild them.
- Read [../reference/roles-and-permissions.md](../reference/roles-and-permissions.md). The seeder in step 2.3 is built from it.
- Use `search-docs` for `laravel/fortify` and `spatie/laravel-permission` before changing auth or roles.

## Build steps

- [x] **2.1** Parent registration with email verification and password reset.
  - `User` already implemented `MustVerifyEmail` from the Phase 1 starter-kit install; nothing to uncomment.
  - D-13's default applied: `/account/dashboard` and `/account/profiles` carry `verified` middleware (see [routes/account.php](../../routes/account.php)); `/` stays open to unverified parents. `/checkout` does not exist yet (Phase 7).
  - Added an unticked-by-default marketing-consent checkbox to `resources/js/pages/auth/register.tsx`.

- [x] **2.2** Keep parents and staff separate in concept, even though both live in `users`.
  - A parent has no role. A staff member has at least one role.
  - Added `User::isStaff(): bool` (`$this->roles()->exists()`).
  - Staff accounts are created only inside `/admin` or by the console command from Phase 1. `/register` can never create one (unchanged — `CreateNewUser` never touches roles).

- [x] **2.3** Create the roles and permissions.
  - Roles: `super-admin`, `business-admin`, `content-manager`, `teacher-reviewer`, `customer-support`, `finance`, `marketing`.
  - `database/seeders/RolesAndPermissionsSeeder.php` creates every permission in [../reference/roles-and-permissions.md](../reference/roles-and-permissions.md) and syncs them to the roles with `syncPermissions()`, so it is safe to run again after every deploy. Called from `DatabaseSeeder`.
  - `super-admin` passes every check through `Gate::before` in `AppServiceProvider`.

- [x] **2.4** Write Laravel policies for protected domain actions. Do not rely on hiding UI.
  - This phase: `LearningProfilePolicy` (`viewAny`, `view`, `create`, `update`, `delete`), each checking `profile.user_id === user.id`.
  - Also added `UserPolicy` (`app/Policies`, following `User`'s existing `App\Models` placement) gating the Phase 2.10 staff-management screen on the `roles.manage` permission.
  - Every later phase adds the policy for its own models.

- [x] **2.5** Create learning profiles.
  ```bash
  php artisan make:model App/Domains/Accounts/Models/LearningProfile --no-interaction
  php artisan make:migration create_learning_profiles_table --no-interaction
  php artisan make:factory LearningProfileFactory --no-interaction
  php artisan make:policy App/Domains/Accounts/Policies/LearningProfilePolicy --no-interaction
  php artisan make:controller Account/LearningProfileController --no-interaction
  php artisan make:request Account/StoreLearningProfileRequest --no-interaction
  php artisan make:request Account/UpdateLearningProfileRequest --no-interaction
  ```
  - Fields: nickname, class, optional avatar, optional interests. Nothing else.
  - `class_id` is a nullable, unconstrained `unsignedBigInteger` (no FK yet — `classes` arrives in Phase 3). Until then `config('account.fixed_classes')` offers Class 1, 2 and 3.
  - The avatar is a key from `config('account.avatar_keys')` (a fixed list of built-in illustrations). There are no image uploads of children.
  - The cap is `config('account.max_learning_profiles')`, default 5, env `MAX_LEARNING_PROFILES`.
  - Deleting a profile sets `active = false` (`LearningProfileController::destroy`).
  - `/account/profiles` (`resources/js/pages/account/profiles/index.tsx`) is a minimal, functional "Add Child" list/add/edit/remove screen — not a pixel match to the mockup; visual polish is out of scope for this phase.

- [x] **2.6** Do not collect a child's email, phone, school, date of birth or address. `tests/Feature/Accounts/LearningProfileSchemaTest.php` fails if `learning_profiles` ever gains a column outside the allowed list.

- [x] **2.7** Parent profile and settings.
  - `/dashboard` moved to `/account/dashboard` (route name `account.dashboard`; `config('fortify.home')` updated) and `/settings/*` moved to `/account/settings/*` (sub-route names unchanged — `profile.edit`, `security.edit`, `user-password.update`, `appearance.edit` — only their paths moved).
  - Added a top-level `account.settings` redirect to `/account/settings/profile`, matching [routes-and-screens.md](../reference/routes-and-screens.md); the starter kit's original `/settings` redirect had no name at all.
  - `notification_preferences` is created, and `CreateNotificationPreferencesForNewUser` (`app/Domains/Accounts/Listeners`) listens for `Registered` and records the marketing-consent choice, time and source. Registered manually in `AppServiceProvider` — Laravel's event auto-discovery only scans `app/Listeners`, not domain folders.
  - The starter kit's own tests that redirected to `route('dashboard')` were updated to `route('account.dashboard')` (`tests/Feature/Auth/*`, `tests/Feature/DashboardTest.php`); the frontend files that imported the Wayfinder-generated `dashboard` helper (`app-header.tsx`, `app-sidebar.tsx`, `welcome.tsx`, `dashboard.tsx`) now import it from `@/routes/account` instead of `@/routes`, since Wayfinder namespaces a dotted route name under its first segment.

- [x] **2.8** Rate-limit login, registration, password reset and repeated suspicious attempts.
  - Fortify already limits login and the two-factor challenge.
  - Added `registration` and `password-reset` named limiters (`config('account.rate_limits')`, env `REGISTRATION_RATE_LIMIT` / `PASSWORD_RESET_RATE_LIMIT`), keyed by email + IP.
  - Fortify has no config slot to attach a limiter to the registration or password-reset routes (only login/two-factor/passkeys), and those routes load lazily, so attaching the throttle middleware at `boot()` time is too early — the routes don't exist yet. Attached instead via an `Illuminate\Routing\Events\RouteMatched` listener in `FortifyServiceProvider`, which fires per-request after the route is resolved but before its middleware is gathered.

- [x] **2.9** Add the account-deletion request placeholder.
  - `ProfileController::destroy` no longer deletes the account. It force-fills `users.status = deletion_requested` (status is deliberately outside `User`'s fillable list, so a public form can never set it), logs the user out, and sends `AccountDeletionRequested` to `config('account.support_owner_email')`.
  - D-10 (support owner assigned) is still open, so that address is a placeholder (`support@brightlearners.test`) — see decision **T-16** in [../tracking/decisions.md](../tracking/decisions.md). Replace it once D-10 is decided.
  - A user whose status is `suspended` cannot log in — enforced in `Fortify::authenticateUsing` (`FortifyServiceProvider`), which also replicates Fortify's default credential check since overriding it replaces that pipeline entirely.

- [x] **2.10** Protect the admin panel.
  - `canAccessPanel()` now returns `isStaff()`.
  - Multi-factor authentication was already required in production from Phase 1 (`AdminPanelProvider::multiFactorAuthentication(..., isRequired: app()->isProduction())`); unchanged here.
  - Added a Filament resource for staff users (`app/Filament/Resources/Users`), restricted to `super-admin` and `business-admin` via `UserPolicy` (checked on the `roles.manage` permission) and scoped to `whereHas('roles')` so parents never appear in it. Its form edits name, email, verification, status and roles; it force-fills `status` the same way `ProfileController` does, and never round-trips the password hash (blank = unchanged).

**Audit-friendly identifiers.** Every record that says who did something stores `user_id` or `actor_id`, never an email or a name. User IDs are never reused. When an account is finally removed, its row is anonymised, not deleted.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#identity-and-access).

| Table | Change |
|---|---|
| `users` | Add `status` |
| `learning_profiles` | New |
| `roles`, `permissions` | Seeded |
| `notification_preferences` | New |

## Routes and screens

| Route | Purpose | Inertia page |
|---|---|---|
| `/register` | Parent account creation | from the starter kit |
| `/login` | Parent login | from the starter kit |
| `/forgot-password` | Password reset request | from the starter kit |
| `/account/settings` | Account, password, 2FA, preferences | `account/settings/*` |
| `/account/profiles` | Manage learning profiles | `account/profiles/index` |

## Admin (Filament)

- Staff user management, limited to `super-admin` and `business-admin`.
- Customer Support can view the account metadata it needs, but cannot change roles or prices.

## Tests to write

| Required test | File and cases |
|---|---|
| Unverified account behaviour matches the chosen policy | `tests/Feature/Accounts/EmailVerificationPolicyTest.php` — unverified parent reaches public pages; is redirected from `/account/*` and `/checkout` |
| A parent cannot access `/admin` | `tests/Feature/Accounts/AdminAccessTest.php` — parent gets 403; each staff role gets 200 |
| Content Manager cannot issue refunds | `tests/Feature/Accounts/RolePermissionTest.php` — `content-manager` lacks `refunds.issue` |
| Support cannot publish resources | same file — `customer-support` lacks `resources.publish` |
| A parent cannot edit another parent's learning profile | `tests/Feature/Accounts/LearningProfileAuthorizationTest.php` — view, update and delete of another user's profile return 403 |
| The rate limiter triggers after the configured threshold | `tests/Feature/Accounts/AuthRateLimitTest.php` — the request after the limit returns 429, for login, registration and password reset |
| No child-identifying fields | `tests/Feature/Accounts/LearningProfileSchemaTest.php` — the column list equals the allowed list |

The two permission tests check the permission matrix now. The real refund and publish actions are tested again in Phases 8 and 4.

All required tests above are implemented and passing (`php artisan test --compact tests/Feature/Accounts`), plus extra coverage this phase added: `LearningProfileManagementTest` (create, cap, deactivate-not-delete), `RegistrationNotificationPreferenceTest`, `AccountDeletionTest` (suspended login block), and the staff Filament resource's own access/visibility tests inside `AdminAccessTest.php`.

## Exit checklist

- [x] Every sensitive route has an authorization test. `/account/dashboard`, `/account/profiles/*` (ownership), `/admin`, `/admin/users` (role-gated) and the auth rate limiters are all covered.
- [x] No unnecessary child-identifying field exists in the schema. Enforced by `LearningProfileSchemaTest`.
- [ ] The role and permission matrix is approved by the business owner. The matrix is implemented exactly as written in [roles-and-permissions.md](../reference/roles-and-permissions.md); formal business sign-off is outside engineering's reach and still needs to happen.

## Risks and controls

| Risk | Control |
|---|---|
| Child-data over-collection | Parent-controlled profiles and data minimisation by design |
| UI-only authorization | Policies and permission checks on the server |
| Admin account compromise | 2FA, strong credentials, restricted roles, audit logging |

## Not in this phase

- Child direct login
- Social login, unless the business chooses to add it
- Teacher or organisation accounts

> The learning profile is a personalisation container. It is not a legal identity record for the child.

# Phase 2 — Identity, security & roles

Create parent authentication, staff role-based access and minimal learner profiles.

| | |
|---|---|
| Workstream | A. Product foundation |
| Duration | ~1 week |
| Depends on | Phase 1 foundation deployed |
| Exit gate | Parents and staff authenticate safely. Role boundaries are enforced. Child data is minimised. |
| Backlog | P2-01, P2-02, P2-03, P2-04 |
| Decide first | D-13 what an unverified parent may do. D-15 what "limited" refund means for Support. |

## Objective

Build the identity model around the parent or guardian as the account holder, with a lightweight learning profile for each child.

## Before you start

- The starter kit from Phase 1 already provides register, login, email verification, password reset and two-factor authentication through Fortify. This phase adapts them; it does not rebuild them.
- Read [../reference/roles-and-permissions.md](../reference/roles-and-permissions.md). The seeder in step 2.3 is built from it.
- Use `search-docs` for `laravel/fortify` and `spatie/laravel-permission` before changing auth or roles.

## Build steps

- [ ] **2.1** Parent registration with email verification and password reset.
  - Make `User` implement `MustVerifyEmail` (the import is commented out in `app/Models/User.php` today).
  - Apply the policy from D-13. Recommended: an unverified parent can browse and download free resources, but must verify before checkout, the library and the dashboard. Put the `verified` middleware on those route groups.
  - Add a marketing-consent checkbox to the register form. It is unticked by default.

- [ ] **2.2** Keep parents and staff separate in concept, even though both live in `users`.
  - A parent has no role. A staff member has at least one role.
  - Add `User::isStaff(): bool`.
  - Staff accounts are created only inside `/admin` or by the console command from Phase 1. `/register` can never create one.

- [ ] **2.3** Create the roles and permissions.
  - Roles: `super-admin`, `business-admin`, `content-manager`, `teacher-reviewer`, `customer-support`, `finance`, `marketing`.
  - `php artisan make:seeder RolesAndPermissionsSeeder --no-interaction`. It creates every permission in [../reference/roles-and-permissions.md](../reference/roles-and-permissions.md) and syncs them to the roles. It must be safe to run again after every deploy.
  - `super-admin` passes every check through `Gate::before`.

- [ ] **2.4** Write Laravel policies for protected domain actions. Do not rely on hiding UI.
  - This phase: `LearningProfilePolicy` (`viewAny`, `view`, `create`, `update`, `delete`), each checking `profile.user_id === user.id`.
  - Every later phase adds the policy for its own models.

- [ ] **2.5** Create learning profiles.
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
  - `class_id` is nullable with no foreign key yet, because `classes` is created in Phase 3. Until then the form offers Class 1, 2 and 3 from a fixed list.
  - The avatar is a key that picks a built-in illustration. There are no image uploads of children.
  - Cap the number of profiles per account in config (suggested: 5).
  - Deleting a profile sets `active = false`.
  - In the mockup this is the "Add Child" control on the dashboard.

- [ ] **2.6** Do not collect a child's email, phone, school, date of birth or address. Add a test that fails if `learning_profiles` ever gains a column outside the allowed list.

- [ ] **2.7** Parent profile and settings.
  - Move the starter kit's `/dashboard` and `/settings/*` pages under `/account/*` to match [../reference/routes-and-screens.md](../reference/routes-and-screens.md).
  - `/account/settings` covers name, email, password, two-factor and notification preferences.
  - Create `notification_preferences` and add a row for each new user in a listener on the `Registered` event, carrying the marketing-consent choice, time and source.

- [ ] **2.8** Rate-limit login, registration, password reset and repeated suspicious attempts.
  - Fortify already limits login and the two-factor challenge.
  - Add named limiters for registration and password-reset requests, keyed by IP and email.
  - Keep the thresholds in config so tests can read them.

- [ ] **2.9** Add the account-deletion request placeholder.
  - The starter kit deletes the account immediately. Replace that with "Request account deletion", which sets `users.status = deletion_requested` and notifies the support owner.
  - Orders and payments will later point at the user, so a hard delete is never automatic. The final process follows the privacy policy (D-10).
  - A user whose status is `suspended` cannot log in.

- [ ] **2.10** Protect the admin panel.
  - `canAccessPanel()` now returns `isStaff()`.
  - Require multi-factor authentication for the panel.
  - Add a Filament resource for staff users, visible only to `super-admin` and `business-admin`.

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

## Exit checklist

- [ ] Every sensitive route has an authorization test.
- [ ] No unnecessary child-identifying field exists in the schema.
- [ ] The role and permission matrix is approved by the business owner.

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

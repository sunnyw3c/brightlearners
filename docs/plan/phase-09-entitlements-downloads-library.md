# Phase 9 — Entitlements, private downloads & My Learning Library

Centralise access to every protected resource and deliver files securely.

| | |
|---|---|
| Workstream | C. Commerce |
| Duration | 1–2 weeks |
| Depends on | Paid orders can be marked complete (Phase 8). Private object storage is available (Phase 4). |
| Exit gate | Every protected file request passes one access service. One-time buyers see their library. |
| Backlog | P9-01, P9-02, P9-03, P9-04 |
| Decide first | D-07 whether an expired member can re-download old packs. D-14 what a refund does to access. |

## Objective

Create the authorization layer that turns purchases, membership and future grants into access to resources.

> You cannot technically stop someone copying a PDF they have downloaded. Focus on access control, clear licence wording, watermarking if appropriate, traceability and customer value, not intrusive DRM.

## Before you start

- `AccessService` and `GET /download/{resource}` already exist from Phase 5 with two rules (published, free). This phase completes them.
- Read "Access rule" in [../reference/architecture.md](../reference/architecture.md#9-access-rule).

## Commands

```bash
php artisan make:model App/Domains/Access/Models/Entitlement --no-interaction
php artisan make:model App/Domains/Access/Models/Download --no-interaction
php artisan make:migration create_access_tables --no-interaction
php artisan make:class App/Domains/Access/Data/AccessDecision --no-interaction
php artisan make:class App/Domains/Access/Actions/GrantPurchasedEntitlements --no-interaction
php artisan make:class App/Domains/Access/Actions/GenerateSignedDownload --no-interaction
php artisan make:listener App/Domains/Access/Listeners/GrantEntitlementsOnOrderPaid --no-interaction
```

## Build steps

- [x] **9.1** Create entitlements as the single source of access truth: user (and optional profile), resource, source type and ID, start, end and revoked timestamps.

- [x] **9.2** On `OrderPaid`, expand each purchased product or bundle into resource entitlements, inside a transaction.
  - Use `Product::deliverableResources()` from Phase 6.
  - `source_type = order_item`, `source_id` = the order item.
  - Insert with "ignore if it exists", relying on the unique index `(user_id, resource_id, source_type, source_id)`. Running the listener twice creates nothing new.

- [x] **9.3** A one-time purchase entitlement does not expire: `ends_at` is null. It ends only by refund or revocation under the policy.

- [x] **9.4** Free resources bypass entitlements through the explicit `resources.is_free` flag. No rows are written for free access.

- [x] **9.5** Implement `AccessService::canAccess($user, $resource)` and use it everywhere.

  It returns an `AccessDecision` (`allowed`, `source`, `entitlement`, `reason`). The rules, in order:

  | # | Rule | Source |
  |---|---|---|
  | 1 | The resource has no published current version → deny | — |
  | 2 | The resource is free → allow | `free` |
  | 3 | The user has an entitlement that has started, has not ended and is not revoked → allow | `purchase` or `admin_grant` |
  | 4 | The user's membership covers a released week containing this resource → allow | `membership` |
  | 5 | Otherwise → deny, with the reason | — |

  Rule 4 goes through a `MembershipAccess` contract that answers "no" until Phase 10 implements it.

  Nothing else in the codebase decides access. Library queries, the download controller, progress updates and the support "why" screen all call this service.

- [x] **9.6** Generate a short-lived signed URL only after the access check. Never expose the private storage key as a permanent public URL.
  - `GenerateSignedDownload` takes an allowed `AccessDecision`, the resource and the variant (colour, low-ink, answer key), and returns `Storage::disk('resources')->temporaryUrl(...)`.
  - Lifetime comes from config (suggested 5 minutes).
  - The download filename includes the version.
  - It is the only class that calls `temporaryUrl` on the `resources` disk.

- [x] **9.7** Log every download and the version delivered, in `downloads`, from a listener on `ResourceDownloaded` (the event Phase 5 already fires).

- [x] **9.8** Build `/account/library` with Purchased, Membership, Free and Completed tabs or filters.
  - Purchased: resources with a live entitlement.
  - Membership: empty until Phase 10.
  - Free: free resources this user has downloaded.
  - Completed: arrives with progress in Phase 11.
  - In the mockup this is "Downloads" in the dashboard sidebar and the "Recent Downloads" list.

- [x] **9.9** Show the current resource version, and a correction notice when a material update exists after the version the user last downloaded.

- [x] **9.10** Handle refund revocation according to D-14, without deleting audit history.
  - `ReviewOrRevokeEntitlements` listens to `RefundCompleted`. A full refund sets `revoked_at` on that order's entitlements. A partial refund follows the policy.
  - Rows are never deleted.

- [x] **9.11** Define the membership post-expiry rule precisely and encode it in the membership rule.
  - Fixed: files already downloaded stay legally usable; new downloads and online member content stop when membership ends.
  - Still to decide (D-07): can an expired member re-download packs that were released while they were a member? Write the answer into config (`membership.redownload_after_expiry`) so the rule in Phase 10 reads it.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#access-and-delivery--phase-9).

| Table | Purpose |
|---|---|
| `entitlements` | Resource access grants from purchase or admin |
| `downloads` | Which resource version was delivered, to whom, when, and through which kind of access |
| `access_audit` | Optional. Allowed / denied security events, kept briefly. |

## Routes and screens

| Route | Purpose | Inertia page |
|---|---|---|
| `/account/library` | All accessible and purchased learning resources | `account/library/index` |
| `/account/library/{resource}` | Library detail: versions, variants, correction notice | `account/library/show` |
| `/download/{resource}` | Authorised download endpoint that returns a temporary URL | — |

## Admin (Filament)

- Support can see why a user does or does not have access: a page that runs `AccessService` for a chosen user and resource and shows the `AccessDecision`.
- Admin grant and revoke need a written reason and write an audit entry. A grant uses `source_type = admin_grant` and can have an expiry.

## Events, jobs and schedule

| Trigger | Result |
|---|---|
| `OrderPaid` | `GrantPurchasedEntitlements` |
| `RefundCompleted` | `ReviewOrRevokeEntitlements` |
| `MaterialCorrectionPublished` | `NotifyAffectedUsers` — the recipient list now comes from `downloads` and `entitlements`; the email is built in Phase 11 |
| `ResourceDownloaded` | Write the `downloads` row |

Complete `AffectedCustomers` from Phase 4 so it returns real users.

## Tests to write

| Required test | File and cases |
|---|---|
| User A cannot download User B's entitlement | `tests/Feature/Access/DownloadAuthorizationTest.php` |
| A direct object URL is not publicly accessible | `tests/Feature/Access/PrivateStorageTest.php` — the `resources` disk is private; no route serves it without a signature |
| An expired entitlement denies access | `tests/Feature/Access/AccessServiceTest.php` — past `ends_at`, future `starts_at`, revoked |
| A permanent purchase stays accessible after membership expires | same file — completed in Phase 10 once memberships exist |
| The refund rule is applied correctly | `tests/Feature/Access/RefundRevocationTest.php` — full refund revokes; rows remain |
| A signed URL expires | `tests/Feature/Access/SignedDownloadTest.php` — travel past the lifetime, the URL is refused |
| The correct resource version is logged at download | `tests/Feature/Access/DownloadLogTest.php` — after a correction, new downloads log the new version |
| The member-discount flag does not grant access | `AccessServiceTest` — an eligible product with no purchase is denied |
| A paid order is fulfilled once | `tests/Feature/Access/GrantEntitlementsTest.php` — the listener run twice creates one set of rows; a bundle grants every child resource |
| Only the access layer creates download URLs | `tests/Arch/AccessArchTest.php` — controllers do not use the `Storage` facade; `temporaryUrl` appears only in `GenerateSignedDownload` |

## Exit checklist

- [x] One paid test order appears in My Library and downloads through a temporary URL.
- [x] Every download controller calls the central access service.
- [x] No paid PDF exists under the public web root. Add a CI check that fails if a PDF appears in `public/`.

## Risks and controls

| Risk | Control |
|---|---|
| Access logic duplication | One service, one policy, shared tests |
| Piracy through public URLs | Private bucket, short-lived signed links, licence wording |
| Refund inconsistency | An explicit entitlement-reversal rule and an audit log |

## Not in this phase

- DRM that prevents screenshots or copying
- Device-limit enforcement, unless evidence justifies it

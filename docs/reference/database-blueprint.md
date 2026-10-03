# Database blueprint

From Appendix B of the source plan, expanded to column level. The final schema is whatever the migrations say; this file is the starting specification for them.

## Rules for every migration

- MySQL 8, `utf8mb4`.
- Every foreign key is a real constraint and has an index.
- Money columns are **unsigned integers in paise** (₹199 = `19900`), with a `currency` column on the same table.
- Status columns are strings that match a PHP enum. Do not use MySQL `ENUM`.
- Business records are archived or soft-deleted, not hard-deleted.
- Check the current structure with the Boost `database-schema` tool before writing a migration.
- Rows marked **added** are not in the source plan. They were added here because a step in the plan needs them.

Legend: `PK` primary key · `FK` foreign key · `?` nullable · `U` unique · `I` indexed.

---

## Identity and access

### `users` — Phase 2 (table exists)

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| name | string | |
| email | string U | |
| email_verified_at | timestamp ? | |
| password | string | hashed |
| status | string | `active`, `suspended`, `deletion_requested`. Default `active`. Added in Phase 2. |
| two-factor columns | | Added by the starter kit / Fortify |
| remember_token, timestamps | | |

### `learning_profiles` — Phase 2

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| user_id | FK users | cascade on delete |
| nickname | string(40) | not a legal name |
| class_id | FK classes ? | nullable until Phase 3 creates `classes` |
| avatar_key | string ? | key of a built-in avatar, not an upload |
| interests | json ? | |
| active | boolean | default true |
| timestamps | | |

Index `(user_id, active)`. No child email, phone, school, date of birth or address.

### `roles`, `permissions` and pivots — Phase 2

Created by `spatie/laravel-permission`. Names are in [roles-and-permissions.md](roles-and-permissions.md).

### `notification_preferences` — Phase 2, extended in Phase 11

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| user_id | FK users U | one row per parent |
| learning_reminders | boolean | default true |
| membership_notices | boolean | default true |
| marketing_consent | boolean | default false |
| marketing_consent_at | timestamp ? | |
| marketing_consent_source | string ? | e.g. `registration`, `settings` |
| timestamps | | |

Receipts, access notices and correction notices are transactional. They are always sent and have no switch.

### `audit_logs` — created in Phase 3, completed in Phase 14

The source plan lists this table under Phase 14, but Phases 3, 7 and 9 already require changes to be logged. The table and a small `Audit::record()` helper are created in Phase 3. Phase 14 adds the admin viewer and checks coverage.

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| actor_id | FK users ? | null for system actions |
| action | string I | e.g. `product.price_changed` |
| subject_type, subject_id | morph I | |
| before, after | json ? | changed fields only |
| metadata | json ? | reason, related IDs |
| ip_address | string(45) ? | only where justified |
| occurred_at | timestamp I | |

### `support_notes` — Phase 14, optional

`id`, `user_id` (customer), `author_id`, `body`, timestamps. Restricted access and a retention period.

### `settings` — Phase 1, optional

`key` U, `value` json. Create only if Phase 0 finds business settings that must change at runtime.

---

## Curriculum — Phase 3

### `classes`

Model: `SchoolClass`.

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| name | string | "Class 1" |
| slug | string U | `class-1` |
| sort_order | unsigned small int | |
| active | boolean | |
| intro | text ? | landing-page copy |
| seo_title, seo_description | string ? | |
| timestamps | | |

### `subjects`

`id`, `name`, `slug` U, `sort_order`, `active`, timestamps.

### `topics`

`id`, `subject_id` FK, `name`, `slug`, `description` text ?, `sort_order`, `active`, timestamps. Unique `(subject_id, slug)`.

### `skills`

`id`, `topic_id` FK, `name`, `slug`, `learning_objective` text, `difficulty_band` string ?, `sort_order`, `active`, timestamps. Unique `(topic_id, slug)`.

### `class_subject`

`id`, `class_id` FK, `subject_id` FK, `sort_order`, `active`, `intro` text ?. Unique `(class_id, subject_id)`. Says which subjects a class offers.

### `class_skill`

`id`, `class_id` FK, `skill_id` FK, `learning_objective` text ? (class-specific wording), `difficulty_band` ?, `sort_order`, `active`. Unique `(class_id, skill_id)`. Says which skills belong to a class.

### `class_topic` — added, Phase 5

`id`, `class_id` FK, `topic_id` FK, `intro` text ?, `seo_title` ?, `seo_description` ?, `sort_order`, `active`. Unique `(class_id, topic_id)`. Holds the per-class copy for `/class-1/maths/addition` (step 5.3).

---

## Learning content — Phase 4

### `resources`

Model: `LearningResource` (`Resource` is a soft-reserved word in PHP and clashes with Filament's `Resource` class).

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| title | string | |
| slug | string U | stable; a change creates a redirect |
| type | string I | `worksheet`, `activity`, `game`, `reading`, `parent_guide`, `starter_check`, `other` |
| summary | string(300) ? | |
| description | text ? | |
| learning_objective | text ? | required before publish |
| difficulty | string ? | |
| estimated_minutes | unsigned small int ? | |
| page_count | unsigned small int ? | |
| supplies | text ? | |
| language | string(10) | default `en` |
| has_answer_key | boolean | |
| low_ink_available | boolean | |
| licence_type | string | default `household` |
| is_free | boolean I | the explicit public flag used by the access service |
| ai_assisted | boolean | internal only, never shown publicly |
| featured | boolean | for homepage and class pages |
| status | string | see `ResourceStatus` in [Phase 4](../plan/phase-04-resource-engine.md) |
| scheduled_for | timestamp ? | |
| published_at | timestamp ? | |
| created_by | FK users | |
| timestamps, soft deletes | | |

Index `(status, published_at)`.

### `resource_skill`

`id`, `resource_id` FK, `skill_id` FK, `class_id` FK, `is_primary` boolean. Unique `(resource_id, skill_id, class_id)`. Index `(class_id, skill_id)`. The pair `(class_id, skill_id)` must exist in `class_skill`.

### `resource_versions`

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| resource_id | FK resources | |
| version | string(10) | "1.0", "1.1" |
| file_path | string | on the private `resources` disk |
| low_ink_path | string ? | |
| answer_file_path | string ? | |
| checksum | string(64) ? | sha256 of the main file |
| change_notes | text ? | |
| preview_status | string | `pending`, `ready`, `failed`. **Added** — a version cannot be published until this is `ready`. |
| reviewed_by | FK users ? | |
| reviewed_at | timestamp ? | the review date required by BR-07 |
| published_at | timestamp ? | once set, the row and its files are immutable |
| is_current | boolean | exactly one current version per published resource |
| created_by | FK users | |
| timestamps | | |

Unique `(resource_id, version)`.

### `resource_reviews`

`id`, `resource_id` FK, `resource_version_id` FK, `review_type` (`educational`, `answer_verification`, `design_print`), `reviewer_id` FK users, `status` (`pending`, `approved`, `changes_requested`), `notes` text ?, `reviewed_at` ?, timestamps. Index `(resource_version_id, review_type)`.

### `resource_previews`

`id`, `resource_version_id` FK, `page_no`, `image_path` (public `previews` disk), `width`, `height`, `sort_order`, timestamps.

### `resource_corrections`

`id`, `resource_id` FK, `version_from`, `version_to`, `severity` (`minor`, `material`), `customer_notice_required` boolean, `notes` text, `notified_at` ?, `created_by` FK users, timestamps.

---

## Catalogue — Phase 6

### `products`

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| name | string | |
| slug | string U | |
| type | string I | `topic_pack`, `workbook`, `activity_ebook`, `holiday_pack`, `revision_pack`, `bundle`, `membership` |
| sku | string U ? | |
| short_description | string ? | |
| description | text ? | |
| primary_class_id | FK classes ? | **added** — gives the `{class}` part of `/shop/{class}/{slug}` |
| regular_price | unsigned int | paise |
| sale_price | unsigned int ? | paise |
| sale_starts_at, sale_ends_at | timestamp ? | |
| currency | char(3) | `INR` |
| member_discount_eligible | boolean | a flag for pricing only; it never grants access |
| subscription_plan_id | FK subscription_plans ? | **added** in Phase 10, set only on `membership` products |
| status | string | `draft`, `active`, `archived` |
| featured | boolean | |
| cover_path | string ? | public `previews` disk |
| publish_at | timestamp ? | |
| published_at | timestamp ? | |
| seo_title, seo_description | string ? | |
| timestamps, soft deletes | | |

Index `(status, type)`.

### `product_resources`

`id`, `product_id` FK, `resource_id` FK, `sort_order`, `version_policy` (default `current`). Unique `(product_id, resource_id)`.

### `bundle_products`

`id`, `bundle_id` FK products, `product_id` FK products, `sort_order`. Unique `(bundle_id, product_id)`. A bundle is a product of type `bundle` that points at other products. It has no files of its own.

### `product_prices` — optional, not built for the MVP

Only if price history or multi-currency is needed later. The MVP keeps the price on the product and snapshots it on the order item.

---

## Commerce — Phase 7

### `carts`

`id`, `token` uuid U (cookie value for guests), `user_id` FK ?, `coupon_id` FK ? (applied coupon), `currency`, timestamps.

### `cart_items`

`id`, `cart_id` FK cascade, `product_id` FK, `quantity` (always 1 for digital products), timestamps. Unique `(cart_id, product_id)`.

### `coupons`

`id`, `code` U (stored upper-case), `type` (`percent`, `fixed`), `value` (1–100 for percent, paise for fixed), `minimum_order` paise, `starts_at` ?, `expires_at` ?, `max_uses` ?, `uses_per_user` ?, `active`, timestamps.

### `coupon_usages`

`id`, `coupon_id` FK, `user_id` FK, `order_id` FK U, `used_at`. Index `(coupon_id, user_id)`. Created with the order; removed when the order is cancelled, fails or expires.

### `orders`

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| order_number | string U | human-readable, e.g. `BL-2026-000123` |
| user_id | FK users | |
| subtotal, discount, tax, total | unsigned int | paise |
| currency | char(3) | |
| status | string | `draft`, `pending_payment`, `paid`, `failed`, `cancelled`, `refunded`, `partially_refunded` |
| coupon_id | FK coupons ? | |
| coupon_code | string ? | snapshot |
| billing_name, billing_email | string | |
| billing_phone, billing_state, gstin | string ? | reserved until the accountant confirms (decision D-05) |
| invoice_number | string ? | reserved |
| paid_at | timestamp ? | |
| expires_at | timestamp ? | abandoned pending orders are cancelled after this |
| timestamps | | |

Index `(user_id, status)` and `(status, created_at)`.

### `order_items`

`id`, `order_id` FK, `product_id` FK ? (null on delete, for historical safety), `product_name`, `product_type`, `sku` ?, `unit_price`, `quantity`, `discount`, `tax`, `total` (paise), `metadata` json ? (included resource IDs, plan code), timestamps. These rows are a snapshot and are never recalculated from the product.

---

## Payments — Phase 8

### `payments`

`id`, `order_id` FK, `provider` (`razorpay`), `provider_order_id` U, `provider_payment_id` U ?, `amount` paise, `currency`, `status` (`created`, `authorized`, `captured`, `failed`, `refunded`, `partially_refunded`), `method` ?, `failure_reason` ?, `verified_at` ?, `paid_at` ?, `payload_reference` ?, timestamps.

### `refunds`

`id`, `payment_id` FK, `amount` paise, `status` (`pending`, `processed`, `failed`), `provider_refund_id` U ?, `reason` text, `initiated_by` FK users, `processed_at` ?, timestamps.

### `webhook_events`

`id`, `provider`, `event_id`, `event_type`, `signature_valid` boolean, `payload` (encrypted), `received_at`, `processed_at` ?, `status` (`received`, `processed`, `failed`, `ignored`), `error` text ?, `attempts`. Unique `(provider, event_id)` — this is the duplicate-webhook guard.

---

## Access and delivery — Phase 9

### `entitlements`

| Column | Type | Notes |
|---|---|---|
| id | PK | |
| user_id | FK users | |
| learning_profile_id | FK ? | |
| resource_id | FK resources | |
| source_type | string | `order_item`, `admin_grant` |
| source_id | unsigned big int ? | |
| starts_at | timestamp | |
| ends_at | timestamp ? | null = does not expire |
| revoked_at | timestamp ? | |
| metadata | json ? | grant or revoke reason, actor |
| timestamps | | |

Unique `(user_id, resource_id, source_type, source_id)` — stops duplicate grants when a payment event is processed twice. Index `(user_id, resource_id)`.

Membership access is not stored here. It is computed by the access service from `subscriptions` and `learning_weeks`.

### `downloads`

`id`, `user_id` FK ?, `learning_profile_id` FK ?, `resource_id` FK, `resource_version_id` FK, `entitlement_id` FK ?, `access_source` (`free`, `purchase`, `membership`, `admin_grant`) **added**, `variant` (`colour`, `low_ink`, `answer_key`) **added**, `ip_hash` ?, `user_agent` ?, `downloaded_at`. Index `(resource_version_id)` and `(user_id, downloaded_at)`. This table decides who receives a correction notice.

### `access_audit` — optional

Allowed/denied events for security review, with a short retention period.

---

## Membership — Phase 10

### `subscription_plans`

`id`, `code` U (`pilot_90`, `monthly`, `quarterly`, `annual`), `name`, `duration_days` ?, `duration_months` ?, `price` paise, `currency`, `auto_renew_supported`, `active`, `benefits` json (member discount percent, inclusions), timestamps. Only `pilot_90` is active at launch.

### `subscriptions`

`id`, `user_id` FK, `plan_id` FK, `source_order_id` FK orders U ?, `starts_at`, `ends_at`, `status` (`active`, `expired`, `cancelled`, `refunded`), `auto_renew` (false for the pilot), `cancelled_at` ?, timestamps. Index `(user_id, status)` and `(status, ends_at)`. The unique `source_order_id` guarantees one membership per paid order.

### `subscription_events`

`id`, `subscription_id` FK, `type`, `occurred_at`, `metadata` json ?.

### `learning_programs`

`id`, `class_id` FK, `month`, `year`, `title`, `status` (`draft`, `ready`, `published`), `publish_at` ?, timestamps. Unique `(class_id, year, month)`.

### `learning_weeks`

`id`, `program_id` FK, `week_number` (1–4), `title`, `emphasis` ?, `release_at`, `released_at` ? (**added**, set once when the release job runs), timestamps. Unique `(program_id, week_number)`.

### `learning_items`

`id`, `week_id` FK, `resource_id` FK, `sort_order`, `required` boolean, `guidance` text ?, timestamps. Unique `(week_id, resource_id)`.

---

## Learning state — Phase 11 and 17

### `resource_progress` — Phase 11

`id`, `user_id` FK, `learning_profile_id` FK, `resource_id` FK, `status` (`not_started`, `opened`, `downloaded`, `completed`), `opened_at` ?, `downloaded_at` ?, `completed_at` ?, `source_context` ?, timestamps. Unique `(learning_profile_id, resource_id)`.

### `notifications` — Phase 11

Laravel's database notification table (`php artisan make:notifications-table`), only if in-app notifications are used.

### Assessment tables — Phase 17

| Table | Columns |
|---|---|
| `assessments` | `class_id`, `subject_id`, `title`, `slug` U, `status`, `version` |
| `assessment_sections` | `assessment_id`, `title`, `sort_order` |
| `assessment_questions` | `section_id`, `skill_id`, `type`, `prompt`, `difficulty`, `answer_config` json |
| `assessment_attempts` | `user_id`, `learning_profile_id`, `assessment_id`, `assessment_version`, `started_at`, `completed_at` |
| `assessment_answers` | `attempt_id`, `question_id`, `response` json, `is_correct`, `score` |
| `skill_scores` | `learning_profile_id`, `skill_id`, `score`, `band`, `source_attempt_id`, `calculated_at` |
| `recommendations` | `learning_profile_id`, `skill_id`, `target_type`, `target_id`, `reason_code`, `generated_at`, `acted_at` ? |

Recurring billing in Phase 17 adds provider subscription IDs to `subscriptions` and renewal references to `payments`.

---

## Growth and operations — Phase 12 and 13

### `article_categories`

`id`, `name`, `slug` U, `description` ?, `sort_order`.

### `articles`

`id`, `title`, `slug` U, `excerpt`, `body` long text, `author_id` FK users, `category_id` FK, `status` (`draft`, `published`, `archived`), `published_at` ?, `seo_title` ?, `seo_description` ?, `og_image_path` ?, timestamps, soft deletes.

### `article_relations` — added

`id`, `article_id` FK, `related_type`, `related_id`, `sort_order`. Links an article to classes, skills, resources and products (step 12.2).

### `redirects`

`id`, `from_path` U, `to_path`, `status_code` (default 301), `active`, `hits`, timestamps.

### `seo_overrides` — optional, not built

SEO fields stay on the content models.

### `analytics_events` — Phase 13

`id`, `event_name` I, `anonymous_id` ?, `user_id` ?, `session_id` ?, `occurred_at` I, `properties` json, `source` (`server`, `client`), `dedupe_key` U ? (**added** — makes server events such as `purchase_completed` fire once). Index `(event_name, occurred_at)`.

### `daily_metrics` — Phase 13

`id`, `date`, `metric_key`, `dimension` json ?, `dimension_hash`, `value` decimal(20,4). Unique `(date, metric_key, dimension_hash)`.

### `acquisition_sources` — optional

UTM and source attribution, only if needed.

### `feedback` — Phase 16, optional, added

`id`, `user_id` FK, `resource_id` FK ?, `category` (`content`, `ux`, `payment`, `support`), `severity`, `body`, `status`, timestamps.

---

## Table index by phase

| Phase | Tables |
|---|---|
| 1 | `settings` (optional) |
| 2 | `users` (+`status`), `learning_profiles`, `roles`, `permissions`, `notification_preferences` |
| 3 | `classes`, `subjects`, `topics`, `skills`, `class_subject`, `class_skill`, `audit_logs` |
| 4 | `resources`, `resource_skill`, `resource_versions`, `resource_reviews`, `resource_previews`, `resource_corrections` |
| 5 | `class_topic`, `redirects` (created here if a slug changes before Phase 12) |
| 6 | `products`, `product_resources`, `bundle_products` |
| 7 | `carts`, `cart_items`, `coupons`, `coupon_usages`, `orders`, `order_items` |
| 8 | `payments`, `refunds`, `webhook_events` |
| 9 | `entitlements`, `downloads`, `access_audit` (optional) |
| 10 | `subscription_plans`, `subscriptions`, `subscription_events`, `learning_programs`, `learning_weeks`, `learning_items` |
| 11 | `resource_progress`, `notifications` |
| 12 | `articles`, `article_categories`, `article_relations`, `redirects` |
| 13 | `analytics_events`, `daily_metrics`, `acquisition_sources` (optional) |
| 14 | `audit_logs` (viewer and full coverage), `support_notes` (optional) |
| 16 | `feedback` (optional) |
| 17 | `assessments`, `assessment_sections`, `assessment_questions`, `assessment_attempts`, `assessment_answers`, `skill_scores`, `recommendations` |

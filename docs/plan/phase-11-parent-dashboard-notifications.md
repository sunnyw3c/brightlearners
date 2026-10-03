# Phase 11 — Parent dashboard, progress & notifications

Make membership feel like a guided learning routine and record simple engagement.

| | |
|---|---|
| Workstream | D. Membership |
| Duration | 1–2 weeks |
| Depends on | Membership programme releases work (Phase 10). Learning profiles are assigned to a class. |
| Exit gate | The parent dashboard shows the current week, month progress and relevant reminders without becoming a heavy LMS |
| Backlog | P11-01, P11-02, P11-03, P11-04 |
| Decide first | M-08 how the progress figure is worded |

## Objective

Create a parent-facing home base that reduces choice overload: what to do this week, what was completed, what is next, and where purchases are stored.

> Completion is an engagement signal, not proof of mastery. Skill assessment is a later phase and stays separate in meaning and in data.

## Before you start

- Mockup screen: "Hello, Priya!" with the sidebar, the child chips (Aarav – Class 2, Myra – Class 1, Add Child), "Weekly Programme — Week 3 of 12", the three subject cards and "Recent Downloads".
- The mockup shows a progress bar with a percentage. Label it as activities opened or completed, never as a score or a level (M-08).
- "Week 3 of 12" is the number of weeks released during this member's term out of the weeks in the term.

## Commands

```bash
php artisan make:model App/Domains/Learning/Models/ResourceProgress --no-interaction
php artisan make:migration create_resource_progress_table --no-interaction
php artisan make:notifications-table --no-interaction
php artisan make:enum App/Domains/Learning/Enums/ProgressStatus --string --no-interaction
php artisan make:class App/Domains/Learning/Actions/RecordProgress --no-interaction
php artisan make:notification WeeklyPackReleased --no-interaction
php artisan make:notification MonthlyCheckAvailable --no-interaction
php artisan make:notification MembershipExpiringNotice --no-interaction
php artisan make:notification CorrectionNotice --no-interaction
```

`OrderConfirmation` already exists from Phase 8. This phase routes it through the same preference check as the others.

## Build steps

- [ ] **11.1** Design the dashboard around one active learning profile at a time, with a profile switcher.
  - The chosen profile ID is kept in the session.
  - Every request re-checks that the profile belongs to the logged-in user. A profile ID from the browser is never trusted.

- [ ] **11.2** Show this week first. Do not bury parents in analytics. The first screen holds the current week's items and one clear next action.

- [ ] **11.3** Add the progress states: `not_started`, `opened`, `downloaded`, `completed`.
  - `RecordProgress` moves a status forward only. It never moves it back.
  - `opened` is set when the library detail or weekly item is opened; `downloaded` by a listener on `ResourceDownloaded`; `completed` by the parent.

- [ ] **11.4** Let the parent mark an activity complete. A simple feedback field can be added later. `RecordProgress` calls `AccessService` first, so progress cannot be recorded on something the family cannot access.

- [ ] **11.5** Show monthly progress such as "3 of 4 packs opened" or "completed", without claiming academic mastery.

- [ ] **11.6** Show the next release date and the next skill check.

- [ ] **11.7** Link purchased resources and membership resources into one library. Complete the Membership and Completed tabs of `/account/library` from Phase 9.

- [ ] **11.8** Implement the notifications: new weekly pack, monthly check available, membership expiring, order confirmation and correction notices.

  | Notification | Trigger | Kind |
  |---|---|---|
  | `OrderConfirmation` | `OrderPaid` | transactional |
  | `CorrectionNotice` | `MaterialCorrectionPublished` | transactional |
  | `MembershipExpiringNotice` | `MembershipExpiring` | membership notice |
  | `WeeklyPackReleased` | `LearningWeekReleased` | learning reminder |
  | `MonthlyCheckAvailable` | `LearningMonthPublished` | learning reminder |

- [ ] **11.9** Keep transactional and learning messages separate from marketing consent.
  - Transactional messages are always sent.
  - Learning reminders and membership notices follow their switches in `notification_preferences`.
  - Marketing consent controls marketing only. Turning it off never stops a receipt or an access notice.
  - Put the check in one place, `App\Domains\Accounts\Services\NotificationGate`, and call it from each notification's `via()`.

- [ ] **11.10** Queue every email. No page request waits for mail. Notifications implement `ShouldQueue` on the `notifications` queue.

- [ ] **11.11** Create accessible empty states for a family with no membership or no profile: explain what is missing and give one action (add a child, see the membership, browse free resources).

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#learning-state--phase-11-and-17).

| Table | Purpose |
|---|---|
| `resource_progress` | Opened / downloaded / completed, per learning profile |
| `notification_preferences` | Extend the Phase 2 flags if a new kind of message needs one |
| `notifications` | Laravel's database notifications, if the in-app list is built |

## Routes and screens

| Route | Purpose | Inertia page |
|---|---|---|
| `/account/dashboard` | Parent dashboard | `account/dashboard` |
| `/account/profiles/{profile}/weekly-learning` | Current learning for one profile | `account/weekly-learning` |
| `/account/progress` | Simple progress history | `account/progress` |
| `/account/notifications` | Optional in-app list | `account/notifications` |

Use an `AccountLayout` with the sidebar from the mockup. Sidebar targets are listed in [../reference/routes-and-screens.md](../reference/routes-and-screens.md#mockup-navigation).

## Admin (Filament)

- Support can see a non-sensitive progress summary only when troubleshooting needs it and the policy allows it.
- A "send test" action for each notification type, so each can be previewed in staging.

## Events, jobs and schedule

Events: `ResourceOpened`, `ResourceDownloaded` (exists since Phase 5), `ResourceCompleted`.

`LearningWeekReleased` → `SendLearningNotification`, which queues `WeeklyPackReleased` for each active member with a profile in that class.

## Tests to write

| Required test | File and cases |
|---|---|
| A profile switch never leaks another account's data | `tests/Feature/Learning/ProfileSwitchTest.php` — switching to another user's profile ID is refused; the dashboard shows only the owner's data |
| A progress update requires resource access | `tests/Feature/Learning/RecordProgressTest.php` — denied without access; status only moves forward |
| The member-expiry reminder schedule works | `tests/Feature/Learning/ExpiryReminderTest.php` — time travel to each reminder day; one notice per day, none after expiry |
| Marketing opt-out does not suppress transactional receipts or access notices | `tests/Feature/Learning/NotificationGateTest.php` — one case per row of the table in step 11.8 |
| Emails are queued | `tests/Feature/Learning/NotificationQueueTest.php` — `Notification::fake()`, assert queued not sent inline |

## Exit checklist

- [ ] A parent with one member profile sees the current week within one screen.
- [ ] A parent can complete a resource and see the month progress update.
- [ ] Every notification type has a preview or test-send in staging.

## Risks and controls

| Risk | Control |
|---|---|
| Overstating learning outcomes | Track engagement and completion separately from assessment and mastery |
| Notification fatigue | Preference controls and a limited cadence |
| A complex dashboard | Prioritise the current week and the next action. Defer charts. |

## Not in this phase

- Leaderboards
- A badges economy
- Child-to-child competition
- Automated mastery claims

# SOP: Customer Cannot Access Content Troubleshooting

Standard Operating Procedure for diagnosing and resolving customer access and library download issues.

## Purpose

Empower Customer Support staff to quickly diagnose why a parent cannot view or download a resource, and apply controlled, audited access overrides when appropriate.

## The Single Authority: `AccessService`

All library download and view decisions flow through `App\Domains\Access\Services\AccessService`. Access is allowed under four conditions:
1. **Free Resource**: The resource is flagged `is_free = true`.
2. **Direct Purchase**: The customer holds an active, unrevoked `Entitlement` linked to an order.
3. **Active Membership**: The customer has an active subscription, the resource belongs to their child's class, and its programme week has been released (`released_at` is set).
4. **Manual Admin Grant**: A staff member granted access with an optional expiry date and mandatory reason.

## Step-by-Step Troubleshooting

1. **Open Customer Support View**:
   - Navigate to **Customers → [Parent] → Support View**.
   - Check the parent's:
     - `email_verified_at`: Must be verified before accessing paid library content.
     - `learningProfiles`: Confirm the child's class matches the resource's class.
2. **Run "Check Access (Why?)"**:
   - Click the header action **Check Access (Why?)**.
   - Select the problematic `LearningResource`.
   - The system displays an immediate verdict:
     - **Allowed**: Displays the authoritative source (`purchase`, `membership`, `admin_grant`, `free`).
     - **Denied**: Displays the exact failure reason (e.g. `unauthenticated`, `not_entitled`, `no_active_membership`, `week_not_released`, `different_class`).
3. **Common Scenarios & Solutions**:
   - **Scenario 1: Unverified Email**:
     - Parent cannot access dashboard/library until verifying email.
     - Action: Request the parent check spam/promotions for the verification email, or trigger a resend.
   - **Scenario 2: Membership Week Not Yet Released**:
     - The resource belongs to a future week of the 12-week programme.
     - Action: Advise the parent of the upcoming release schedule.
   - **Scenario 3: Membership Expired**:
     - The 90-day pilot or annual membership period ended.
     - Action: Invite the parent to renew their subscription.
   - **Scenario 4: Legitimate Service Issue (Need Immediate Override)**:
     - If a parent paid or was promised access during support resolution:
     - Click **Grant Manual Access**.
     - Select the resource.
     - Enter a **Mandatory Reason** (e.g. "Support Ticket #4102: Payment verified manually").
     - (Optional) Set an expiry date.
     - Confirm. An audited manual entitlement is granted instantly.
4. **Log Context**:
   - Add a brief note under **Internal Support Notes** detailing the resolution.

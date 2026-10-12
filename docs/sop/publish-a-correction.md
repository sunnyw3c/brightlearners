# SOP: Publish a Correction

Standard Operating Procedure for correcting an educational resource and notifying affected families.

## Purpose

Maintain version immutability (published files are never overwritten in-place) while providing clean corrections and proactive parent communication for material errors.

## Principles

1. **Immutability**: A published version remains frozen. Any correction creates a new version (`1.1` for minor/material corrections, or `2.0` for full rewrites).
2. **Review Integrity**: The corrected version must pass the full suite of reviews (`educational`, `answer_verification`, `design_print`) before publishing.
3. **Parent Notice**: Material corrections require notifying parents who downloaded or accessed the flawed version.

## Step-by-Step Procedure

1. **Initiate Correction**:
   - Open **Content → Resources** and select the published resource.
   - Click **Create Correction**.
   - Upload the corrected PDF file (and optional answer / low-ink files).
   - Select the **Correction Severity**:
     - `cosmetic`: Typo or formatting fix; no change to answers or curriculum.
     - `minor`: Minor rewording; answer unchanged.
     - `material`: Incorrect answer key, broken question, or curriculum error.
   - Set **Customer Notice Required** (`true` if parents need to be told to re-download).
   - Enter descriptive **Change Notes**.
2. **Review and Verification**:
   - The new version appears in **Content → Correction Queue**.
   - Teacher Reviewers inspect the fix and approve the reviews.
3. **Publish Corrected Version**:
   - Once all reviews pass, execute **Publish Version**.
   - The resource's `currentVersion` points to the new version.
   - A `resource_corrections` record is created linking `version_from` to `version_to`.
4. **Notify Affected Customers**:
   - Open **Content → Published Corrections**.
   - For corrections with `customer_notice_required = true` and `notified_at = null`, click **Send Notice**.
   - The modal calculates and displays the exact count of affected families from download and entitlement history.
   - Confirm to dispatch the `CorrectionNotice` emails and stamp `notified_at`.
   - Re-sending is automatically blocked.

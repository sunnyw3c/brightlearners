# SOP: Publish a Resource

Standard Operating Procedure for reviewing and publishing a learning resource.

## Purpose

Ensure learning content meets educational accuracy, curriculum alignment, and print-quality standards before becoming accessible to parents and children.

## Roles Involved

- **Content Manager**: Prepares resource metadata and initiates upload.
- **Teacher Reviewer**: Conducts educational and answer verification reviews.
- **Design Reviewer / Super Admin**: Conducts design and print check.

## Prerequisites & Gates

A resource version cannot be published until all four criteria are satisfied:
1. **Metadata Complete**: School class, skill, learning objective, resource type, and page count are filled.
2. **Review Approval**: Three mandatory review gates are marked **Approved**:
   - `educational`: Curriculum relevance and pedagogical accuracy.
   - `answer_verification`: All answer keys verified (reviewer must not be the person who uploaded the PDF).
   - `design_print`: Margins, print contrast, low-ink legibility, and page numbering verified.
3. **Previews Ready**: PDF preview generation has finished successfully and preview images exist on disk.
4. **Permissions**: Actor holds `resources.publish`.

## Step-by-Step Procedure

1. **Navigate to Content → Resources**:
   - Locate the resource in `Draft` status or open **Content Review → Review Queue**.
2. **Verify Reviews**:
   - Open the latest version's **Reviews** tab.
   - Confirm all three review gates are in `Approved` status.
3. **Inspect File & Previews**:
   - Verify that preview thumbnails have generated cleanly.
4. **Publish**:
   - Click **Publish Version** action on the version record.
   - Confirm the confirmation dialog.
5. **System Side-Effects**:
   - The version status switches to `published` and `is_current` is set to `true`.
   - The resource status transitions from `draft` to `published`.
   - An immutable audit trail entry (`resource.published`) is recorded.
   - Cache invalidation and Scout search indexing are queued.

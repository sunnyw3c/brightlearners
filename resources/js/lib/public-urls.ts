/**
 * Class/subject/topic landing URLs. Built as plain paths rather than
 * through the generated Wayfinder helpers because the `learn.class` route
 * name collides with the reserved JavaScript keyword `class`. The shape
 * mirrors the server's stable URL rules exactly
 * (docs/reference/routes-and-screens.md, "URL rules").
 */
export function classHref(classSlug: string): string {
    return `/${classSlug}`;
}

export function subjectHref(classSlug: string, subjectSlug: string): string {
    return `/${classSlug}/${subjectSlug}`;
}

export function topicHref(
    classSlug: string,
    subjectSlug: string,
    topicSlug: string,
): string {
    return `/${classSlug}/${subjectSlug}/${topicSlug}`;
}

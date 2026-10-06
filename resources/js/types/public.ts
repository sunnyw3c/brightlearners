export type SeoProps = {
    title: string;
    description: string | null;
    canonical: string;
    noindex: boolean;
};

export type BreadcrumbData = {
    label: string;
    url: string | null;
};

export type ResourceCardData = {
    id: number;
    title: string;
    slug: string;
    summary: string | null;
    type: string;
    free: boolean;
    class_name: string | null;
    subject_name: string | null;
    url: string | null;
    preview_image_url: string | null;
    preview_width: number | null;
    preview_height: number | null;
    estimated_minutes: number | null;
    page_count: number | null;
};

export type SearchResultData = {
    type: string;
    title: string;
    summary: string;
    url: string;
    class: string | null;
    free: boolean;
};

export type Paginated<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};

export type MoneyProp = {
    paise: number;
    formatted: string;
};

export type ProductCardData = {
    id: number;
    name: string;
    slug: string;
    short_description: string | null;
    type: string;
    class_name: string | null;
    url: string;
    cover_image_url: string | null;
    regular_price: MoneyProp;
    price: MoneyProp | null;
    on_sale: boolean;
};

export type SchoolClassSummary = {
    id: number;
    name: string;
    slug: string;
};

export type SubjectSummary = {
    id: number;
    name: string;
    slug: string;
};

export type TopicSummary = {
    id: number;
    name: string;
    slug: string;
};

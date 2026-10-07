export type Money = {
    paise: number;
    formatted: string;
};

export type PriceLine = {
    product_id: number;
    name: string;
    slug: string;
    type: string;
    sku: string | null;
    cover_image_url: string | null;
    unit_price: Money;
    member_discount: Money;
    coupon_discount: Money;
    discount: Money;
    tax: Money;
    total: Money;
    quantity: 1;
};

export type PriceBreakdown = {
    lines: PriceLine[];
    subtotal: Money;
    member_discount: Money;
    coupon_discount: Money;
    discount: Money;
    tax: Money;
    total: Money;
    currency: string;
};

<?php

namespace App\Domains\Catalog\Enums;

/**
 * Launch types from docs/plan/phase-06-product-catalogue.md, step 6.1.
 * Phase 10 adds `Membership`.
 */
enum ProductType: string
{
    case TopicPack = 'topic_pack';
    case Workbook = 'workbook';
    case ActivityEbook = 'activity_ebook';
    case HolidayPack = 'holiday_pack';
    case RevisionPack = 'revision_pack';
    case Bundle = 'bundle';

    /**
     * The `{type}` segment of `/shop/{type}` (docs/reference/routes-and-screens.md).
     */
    public function routeSegment(): string
    {
        return match ($this) {
            self::TopicPack => 'topic-packs',
            self::Workbook => 'workbooks',
            self::ActivityEbook => 'ebooks',
            self::HolidayPack => 'holiday-packs',
            self::RevisionPack => 'revision-packs',
            self::Bundle => 'bundles',
        };
    }

    public static function fromRouteSegment(string $segment): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->routeSegment() === $segment) {
                return $case;
            }
        }

        return null;
    }

    /**
     * @return list<string>
     */
    public static function routeSegments(): array
    {
        return array_map(fn (self $case): string => $case->routeSegment(), self::cases());
    }
}

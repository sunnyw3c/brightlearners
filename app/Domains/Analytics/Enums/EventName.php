<?php

namespace App\Domains\Analytics\Enums;

enum EventName: string
{
    case ResourceView = 'resource_view';
    case ResourcePreview = 'resource_preview';
    case FreeDownload = 'free_download';
    case ProductView = 'product_view';
    case AddToCart = 'add_to_cart';
    case CheckoutStarted = 'checkout_started';
    case PurchaseCompleted = 'purchase_completed';
    case PaymentFailed = 'payment_failed';
    case MembershipView = 'membership_view';
    case MembershipStarted = 'membership_started';
    case ResourceOpened = 'resource_opened';
    case ResourceDownloaded = 'resource_downloaded';
    case ResourceCompleted = 'resource_completed';
    case SearchPerformed = 'search_performed';
    case FeedbackSubmitted = 'feedback_submitted';

    // Phase 17 forward-compatibility
    case AssessmentStarted = 'assessment_started';
    case AssessmentCompleted = 'assessment_completed';
    case RecommendationClicked = 'recommendation_clicked';

    /**
     * @return array<int, string>
     */
    public function requiredProperties(): array
    {
        return match ($this) {
            self::ResourceView => ['resource_id', 'class', 'subject', 'skill', 'source'],
            self::ResourcePreview => ['resource_id', 'preview_page'],
            self::FreeDownload => ['resource_id', 'version_id'],
            self::ProductView => ['product_id', 'class', 'price'],
            self::AddToCart => ['product_id', 'price', 'cart_id'],
            self::CheckoutStarted => ['order_id', 'total', 'item_count'],
            self::PurchaseCompleted => ['order_id', 'revenue', 'items'],
            self::PaymentFailed => ['order_id', 'reason_category'],
            self::MembershipView => ['plan_context'],
            self::MembershipStarted => ['subscription_id', 'plan_id', 'paid_amount'],
            self::ResourceOpened => ['profile_id', 'resource_id', 'source'],
            self::ResourceDownloaded => ['profile_id', 'resource_id', 'version_id'],
            self::ResourceCompleted => ['profile_id', 'resource_id'],
            self::SearchPerformed => ['query', 'filters', 'result_count'],
            self::FeedbackSubmitted => ['category'],
            self::AssessmentStarted => ['assessment_id', 'profile_id'],
            self::AssessmentCompleted => ['assessment_id', 'profile_id', 'skill_bands'],
            self::RecommendationClicked => ['recommendation_id', 'target'],
        };
    }

    /**
     * Determines whether the event can be accepted from client POST /events.
     */
    public function isClientAllowed(): bool
    {
        return match ($this) {
            self::ResourcePreview,
            self::MembershipView,
            self::ResourceView,
            self::ProductView,
            self::SearchPerformed => true,
            default => false,
        };
    }
}

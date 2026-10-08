<?php

namespace App\Filament\Resources\Coupons\Pages;

use App\Filament\Resources\Coupons\CouponResource;
use App\Support\Audit;
use Filament\Resources\Pages\CreateRecord;

class CreateCoupon extends CreateRecord
{
    protected static string $resource = CouponResource::class;

    protected function afterCreate(): void
    {
        Audit::record(
            action: 'coupon.created',
            subject: $this->record,
            after: $this->record->toArray(),
            metadata: ['reason' => 'Coupon created in Filament admin.'],
        );
    }
}

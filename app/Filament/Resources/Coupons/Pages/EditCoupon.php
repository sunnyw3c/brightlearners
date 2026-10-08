<?php

namespace App\Filament\Resources\Coupons\Pages;

use App\Filament\Resources\Coupons\CouponResource;
use App\Support\Audit;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoupon extends EditRecord
{
    protected static string $resource = CouponResource::class;

    /**
     * @var array<string, mixed>|null
     */
    private ?array $beforeData = null;

    protected function beforeSave(): void
    {
        $this->beforeData = $this->record->toArray();
    }

    protected function afterSave(): void
    {
        Audit::record(
            action: 'coupon.updated',
            subject: $this->record,
            before: $this->beforeData,
            after: $this->record->toArray(),
            metadata: ['reason' => 'Coupon updated in Filament admin.'],
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Console\Commands;

use App\Domains\Catalog\Actions\ActivateProduct;
use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

#[Signature('products:publish-scheduled')]
#[Description('Activate products whose scheduled publish time has come')]
class PublishScheduledProducts extends Command
{
    public function handle(ActivateProduct $activateProduct): int
    {
        Product::query()
            ->where('status', ProductStatus::Draft)
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now())
            ->each(function (Product $product) use ($activateProduct): void {
                try {
                    $activateProduct->handle($product);
                } catch (ValidationException) {
                    // Not yet publishable; left as draft for the next run.
                }
            });

        return self::SUCCESS;
    }
}

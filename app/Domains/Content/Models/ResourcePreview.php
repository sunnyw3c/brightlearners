<?php

namespace App\Domains\Content\Models;

use Database\Factories\ResourcePreviewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * A generated preview image on the public `previews` disk. Never a full
 * rendering of a paid resource — only the first pages
 * (config('content.preview_pages')).
 */
#[Fillable(['resource_version_id', 'page_no', 'image_path', 'width', 'height', 'sort_order'])]
#[UseFactory(ResourcePreviewFactory::class)]
class ResourcePreview extends Model
{
    /** @use HasFactory<ResourcePreviewFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<ResourceVersion, $this>
     */
    public function version(): BelongsTo
    {
        return $this->belongsTo(ResourceVersion::class, 'resource_version_id');
    }

    public function url(): string
    {
        return Storage::disk('previews')->url($this->image_path);
    }
}

<?php

namespace App\Domains\Growth\Models;

use App\Domains\Growth\Policies\RedirectPolicy;
use App\Domains\Growth\Services\RedirectValidator;
use Database\Factories\RedirectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Table('redirects')]
#[Fillable([
    'from_path',
    'to_path',
    'status_code',
    'active',
    'hits',
])]
#[UseFactory(RedirectFactory::class)]
#[UsePolicy(RedirectPolicy::class)]
class Redirect extends Model
{
    /** @use HasFactory<RedirectFactory> */
    use HasFactory;

    protected $attributes = [
        'status_code' => 301,
        'active' => true,
        'hits' => 0,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'active' => 'boolean',
            'hits' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Redirect $redirect): void {
            $redirect->from_path = RedirectValidator::normalize($redirect->from_path);
            $redirect->to_path = RedirectValidator::normalize($redirect->to_path);

            RedirectValidator::validate($redirect->from_path, $redirect->to_path, $redirect->id);
        });
    }
}

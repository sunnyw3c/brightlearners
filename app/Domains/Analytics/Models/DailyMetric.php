<?php

namespace App\Domains\Analytics\Models;

use Database\Factories\DailyMetricFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(DailyMetricFactory::class)]
class DailyMetric extends Model
{
    use HasFactory;

    protected $table = 'daily_metrics';

    protected $fillable = [
        'date',
        'metric_key',
        'dimension',
        'dimension_hash',
        'value',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'dimension' => 'array',
            'value' => 'decimal:4',
        ];
    }
}

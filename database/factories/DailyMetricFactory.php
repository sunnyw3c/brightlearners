<?php

namespace Database\Factories;

use App\Domains\Analytics\Models\DailyMetric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyMetric>
 */
class DailyMetricFactory extends Factory
{
    protected $model = DailyMetric::class;

    public function definition(): array
    {
        return [
            'date' => now()->toDateString(),
            'metric_key' => 'visitors',
            'dimension' => null,
            'dimension_hash' => '',
            'value' => 10.0,
        ];
    }
}

<?php

namespace App\Support;

/**
 * Formats integer paise for display (docs/reference/architecture.md,
 * "Money"). Money sent to React is always `{ paise, formatted }`; React
 * never does price arithmetic.
 */
final class Money
{
    public static function format(int $paise, string $currency = 'INR'): string
    {
        $rupees = $paise / 100;
        $decimals = $paise % 100 === 0 ? 0 : 2;

        return match ($currency) {
            'INR' => '₹'.number_format($rupees, $decimals),
            default => number_format($rupees, 2)." {$currency}",
        };
    }

    /**
     * @return array{paise: int, formatted: string}
     */
    public static function toProp(int $paise, string $currency = 'INR'): array
    {
        return [
            'paise' => $paise,
            'formatted' => self::format($paise, $currency),
        ];
    }
}

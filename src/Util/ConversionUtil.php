<?php

namespace App\Util;

class ConversionUtil
{
    const MASS_CONVERSION_RATES = [
        't' => 1000000,
        'kg' => 1000,
        'g' => 1,
    ];
    static public function convertWeight(float $amount, string $fromUnit = 'kg', string $toUnit = 'g'): float
    {
        if($fromUnit === $toUnit) {
            return $amount;
        }

        if (!isset(self::MASS_CONVERSION_RATES[$fromUnit]) || !isset(self::MASS_CONVERSION_RATES[$toUnit])) {
            throw new \Exception('Invalid unit');
        }

        return $amount * self::MASS_CONVERSION_RATES[$fromUnit] / self::MASS_CONVERSION_RATES[$toUnit];
    }
}
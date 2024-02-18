<?php

namespace App\Tests\App\Util;

use App\Util\ConversionUtil;
use PHPUnit\Framework\TestCase;

class ConversionUtilTest extends TestCase
{
    public function testConvertToGrams(): void
    {
        $amountToConvert = 0.2;
        $expectedAmount = 200;

        $this->assertEquals($expectedAmount, ConversionUtil::convertWeight($amountToConvert, 'kg', 'g'));
    }

    public function testConvertToKilograms(): void
    {
        $amountToConvert = 200;
        $expectedAmount = 0.2;

        $this->assertEquals($expectedAmount, ConversionUtil::convertWeight($amountToConvert, 'g', 'kg'));
    }

    
}

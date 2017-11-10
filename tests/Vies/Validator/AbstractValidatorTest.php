<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

use DragonBe\Vies\Vies;
use PHPUnit\Framework\TestCase;

abstract class AbstractValidatorTest extends TestCase
{
    protected function validateVatNumber(string $country, string $vatNumber, bool $state)
    {
        $this->assertSame($state, (new Vies())->validateVatSum($country, $vatNumber));
    }
}

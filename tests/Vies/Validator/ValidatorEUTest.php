<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorEUTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorEU
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('EU', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['372009975', false],
            ['826409867', false],
            ['528003555', false],
        ];
    }
}

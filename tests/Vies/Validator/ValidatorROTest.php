<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorROTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorRO
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('RO', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['11198699', true],
            ['14186770', true],
            ['11198698', false],
            ['1', false],
            ['12345678902', false],
        ];
    }
}

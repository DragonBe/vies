<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorEETest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorEE
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('EE', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['100207415', true],
            ['1002074', false],
            ['A12345678', false],
        ];
    }
}

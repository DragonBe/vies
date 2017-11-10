<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorLUTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorLU
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('LU', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['10000356', true],
            ['10000355', false],
            ['1234567', false],
        ];
    }
}

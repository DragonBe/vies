<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorBETest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorBE
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('BE', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['776091951', true],
            ['0776091952', false],
            ['07760919', false],
        ];
    }
}

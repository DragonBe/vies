<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorFITest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorFI
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('FI', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['09853608', true],
            ['09853607', false],
            ['1234567', false],
            ['01089940', true],
        ];
    }
}

<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorNLTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorNL
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('NL', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['010000446B01', true],
            ['010000436B01', false],
            ['12345678901', false],
            ['123456789A12', false],
            ['123456789B00', false],
            ['002342672B42', true],
        ];
    }
}

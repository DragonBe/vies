<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorMTTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorMT
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('MT', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['15121333', true],
            ['15121332', false],
            ['1234567', false],
            ['05121333', false],
        ];
    }
}

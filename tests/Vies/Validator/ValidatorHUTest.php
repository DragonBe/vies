<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorHUTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorHU
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('HU', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['21376414', true],
            ['10597190', true],
            ['2137641', false],
            ['1234567A', false],
        ];
    }
}

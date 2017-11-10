<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorELTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorEL
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('EL', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['040127797', true],
            ['040127796', false],
            ['1234567', false],
        ];
    }
}

<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorPLTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorPL
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('PL', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['5260001246', true],
            ['12342678090', false],
            ['1212121212', false],
        ];
    }
}

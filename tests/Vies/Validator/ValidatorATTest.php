<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorATTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorAT
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('AT', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['U10223006', true],
            ['U1022300', false],
            ['A10223006', false],
            ['U10223005', false],
        ];
    }
}

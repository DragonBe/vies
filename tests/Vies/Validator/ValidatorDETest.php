<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorDETest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorDE
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('DE', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['111111125', true],
            ['111111124', false],
            ['1234567', false],
        ];
    }
}

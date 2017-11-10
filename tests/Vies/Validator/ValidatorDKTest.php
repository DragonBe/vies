<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorDKTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorDK
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('DK', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['88146328', true],
            ['88146327', false],
            ['1234567', false],
        ];
    }
}

<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorFRTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorFR
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('FR', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['00300076965', true],
            ['K7399859412', true],
            ['4Z123456782', true],
            ['0030007696A', false],
            ['1234567890', false],
            ['K6399859412', false],
            ['KO399859412', false],
            ['IO399859412', false],
        ];
    }
}

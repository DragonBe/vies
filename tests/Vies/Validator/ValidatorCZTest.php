<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorCZTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorCZ
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('CZ', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['46505334', true],
            ['7103192745', true],
            ['640903926', true],
            ['395601439', true],
            ['630903928', true],
            ['27082440', true],
            ['4650533', false],
            ['96505334', false],
            ['46505333', false],
            ['7103192743', false],
            ['5103192743', false],
            ['1903192745', false],
            ['7133192745', false],
            ['395632439', false],
            ['396301439', false],
            ['545601439', false],
            ['640903927', false],
            ['7103322745', false],
        ];
    }
}

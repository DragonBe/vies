<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorCYTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorCY
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('CY', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['00532445O', true],
            ['005324451', false],
            ['0053244511', false],
            ['12000139V', false],
            ['72000139V', false],
        ];
    }
}

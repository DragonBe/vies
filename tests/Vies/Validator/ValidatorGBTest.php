<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorGBTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorGB
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('GB', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['434031494', true],
            ['GD001', true],
            ['HA500', true],
            ['434031493', false],
            ['12345', false],
            ['GD500', false],
            ['HA100', false],
            ['12345678', false],
        ];
    }
}

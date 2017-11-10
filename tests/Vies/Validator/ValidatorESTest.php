<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorESTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorES
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('ES', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['A0011012B', true],
            ['A78304516', true],
            ['K0011012B', false],
            ['12345678', false],
            ['K001A012B', false],
            ['A0011012C', false],
        ];
    }
}

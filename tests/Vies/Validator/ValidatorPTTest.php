<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorPTTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorPT
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('PT', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['502757191', true],
            ['502757192', false],
            ['12345678', false],
        ];
    }
}

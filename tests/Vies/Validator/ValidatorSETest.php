<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorSETest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorSE
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('SE', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['556188840401', true],
            ['556188840400', false],
            ['1234567890', false],
            ['556181140401', false],
        ];
    }
}

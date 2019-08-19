<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorITTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorIT
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('IT', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['00000010215', true],
            ['00000010214', false],
            ['1234567890', false],
            ['00000001234', false],
            ['AA123456789', false],
        ];
    }
}

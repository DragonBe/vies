<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorIETest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorIE
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('IE', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['8Z49289F', true],
            ['3628739L', true],
            ['5343381W', true],
            ['6433435OA', true],
            ['8Z49389F', false],
            ['1234567', false],
            ['6433435OB', false],
        ];
    }
}

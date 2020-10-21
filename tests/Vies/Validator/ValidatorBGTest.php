<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

class ValidatorBGTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorBG
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('BG', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['301004503', true],
            ['10100450', false],
            ['301004502', false],
            ['8311046307', true],
            ['3002779909', true],
        ];
    }
}

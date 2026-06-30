<?php

declare (strict_types=1);

namespace DragonBe\Test\Vies\Validator;

use DragonBe\Vies\Validator\ValidatorSK;

class ValidatorSKTest extends AbstractValidatorTest
{
    /**
     * @covers \DragonBe\Vies\Validator\ValidatorSK
     * @dataProvider vatNumberProvider
     */
    public function testValidator(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('SK', $vatNumber, $state);
    }

    public function vatNumberProvider()
    {
        return [
            ['4030000007', true],
            ['4030000006', false],
            ['123456789', false],
            ['0123456789', false],
            ['4060000007', false],
        ];
    }

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorSK
     */
    public function testValidateRaisesNoWarningOnNonNumericInput()
    {
        $raised = null;
        set_error_handler(static function (int $errno, string $errstr) use (&$raised): bool {
            $raised = $errstr;
            return true;
        }, E_DEPRECATED | E_WARNING);

        try {
            $result = (new ValidatorSK())->validate('222222222A');
        } finally {
            restore_error_handler();
        }

        self::assertFalse($result, 'Non-numeric SK input must be invalid');
        self::assertNull($raised, 'Validator must not trigger E_WARNING or E_DEPRECATED');
    }
}

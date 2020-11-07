<?php

declare (strict_types=1);

/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

/**
 * Class ValidatorBG
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 ... C9 Numeric from 0 to 9
 *
 * Rules:
 * C9
 *      A1 = 1*C1 + 2*C2 + 3*C3 + 4*C4 + 5*C5 + 6*C6 + 7*C7 + 8*C8
 *      R1 = A1 modulo 11
 *      If R1 = 10, then
 *          A2 = 3*C1 + 4*C2 + 5*C3 + 6*C4 + 7*C5 + 8*C6 + 9*C7 + 10*C8
 *          R2 = A2 modulo 11
 *          If R2 = 10 then R = 0
 *          Else R = R2
 *      Else R = R1
 *      C9 = R
 */
class ValidatorBG extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        $vatNumberLength = strlen($vatNumber);
        if (! in_array($vatNumberLength, [9, 10], true)) {
            return false;
        }

        if (10 === $vatNumberLength) {
            return $this->validateNaturalPerson($vatNumber)
                || $this->validateForeignNaturalPerson($vatNumber);
        }
        return $this->validateBusiness($vatNumber);
    }

    /**
     * Validation for business VAT ID's with 9 digits
     *
     * @param string $vatNumber
     * @return bool
     */
    private function validateBusiness(string $vatNumber): bool
    {
        $weights = [1, 2, 3, 4, 5, 6, 7, 8];
        return $this->checkValue($vatNumber, $weights, parent::DEFAULT_MODULO, 8);
    }

    /**
     * Validate VAT ID's for natural persons
     *
     * @param string $vatNumber
     * @return bool
     * @see https://github.com/yolk/valvat/blob/master/lib/valvat/checksum/bg.rb
     */
    private function validateNaturalPerson(string $vatNumber): bool
    {
        $weights = [2, 4, 8, 5, 10, 9, 7, 3, 6];
        return $this->checkValue($vatNumber, $weights, parent::DEFAULT_MODULO, parent::DEFAULT_VAT_POSITION);
    }

    /**
     * Validate VAT ID's for foreign natural persons
     *
     * @param string $vatNumber
     * @return bool
     * @see https://github.com/yolk/valvat/blob/master/lib/valvat/checksum/bg.rb
     */
    private function validateForeignNaturalPerson(string $vatNumber): bool
    {
        $weights = [21, 19, 17, 13, 11, 9, 7, 3, 1];
        return $this->checkValue($vatNumber, $weights, 10, parent::DEFAULT_VAT_POSITION);
    }
}

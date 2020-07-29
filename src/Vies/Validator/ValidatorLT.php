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
 * Class ValidatorLT
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *             [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11 C12]
 *
 * Range:
 *      C1 ... C12 Numeric from 0 to 9
 *      C8 = 1
 *
 * Rules:
 * C8
 *      A1 = 1*C1 + 2*C2 + 3*C3 + 4*C4 + 5*C5 + 6*C6 + 7*C7 + 8*C8 + 9*C9 + 1*C10 + 2*C11
 *      R1 = A1 modulo 11
 *
 *      If R1 <> 10, then C12 = R1
 *      Else
 *
 *          A2 = 3*C1 + 4*C2 + 5*C3 + 6*C4 + 7*C5 + 8*C6 + 9*C7 + 1*C8 + 2*C9 + 3*C10 + 4*C11
 *          R2 = A2 modulo 11
 *          If R2 = 10, then C12 = 0
 *          Else C12 = R2
 */
class ValidatorLT extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) == 12) {
            return $this->validateTemporaryTaxpayer($vatNumber);
        }

        if (strlen($vatNumber) == 9) {
            return $this->validateLegal($vatNumber);
        }

        return false;
    }

    /**
     * Validate Temporary Tax Payer
     *
     * @param string $vatNumber
     *
     * @return bool
     */
    private function validateTemporaryTaxpayer(string $vatNumber): bool
    {
        if ($vatNumber[10] != 1) {
            return false;
        }

        $weights = [1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2];
        $checksum = (int)$vatNumber[11];
        $checkVal = $this->sumWeights($weights, $vatNumber);

        if (($checkVal % 11) == 10) {
            $weights = [3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4];
            $checkVal = $this->sumWeights($weights, $vatNumber);
            $checkVal = ($checkVal % 11 == 10) ? 0 : $checkVal % 11;

            return $checkVal == $checksum;
        }

        return $checkVal % 11 == $checksum;
    }

    /**
     * Validate Legal
     *
     * @param string $vatNumber
     *
     * @return bool
     */
    private function validateLegal(string $vatNumber): bool
    {
        if ($vatNumber[7] != 1) {
            return false;
        }

        $weights = [1, 2, 3, 4, 5, 6, 7, 8];
        $checksum = (int) $vatNumber[8];
        $checkVal = $this->sumWeights($weights, $vatNumber);

        if (($checkVal % 11) == 10) {
            $weights = [3, 4, 5, 6, 7, 8, 9, 1];
            $checkVal = $this->sumWeights($weights, $vatNumber);
            $checkVal = ($checkVal % 11 == 10) ? 0 : $checkVal % 11;

            return $checkVal == $checksum;
        }

        return $checkVal % 11 == $checksum;
    }
}

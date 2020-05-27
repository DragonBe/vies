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
 * Class ValidatorLV
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11]
 *
 * Range:
 *      C1 ... C11 Numeric from 0 to 9
 *      C1 > 3
 *
 * Rules:
 * C11
 *      A1 = 9*C1 + 1*C2 + 4*C3 + 8*C4 + 3*C5 + 10*C6 + 2*C7 + 5*C8 + 7*C9 + 6*C1
 *      R = 3 - (A1 modulo 11)
 *      If R < -1, then C11 = R + 11
 *      If R > -1, then C11 = R
 *      If R = -1, then VAT number is invalid
 */
class ValidatorLV extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }

        if ((int)$vatNumber[0] <= 3) {
            return false;
        }

        $weights = [9, 1, 4, 8, 3, 10, 2, 5, 7, 6];
        $checksum = (int)substr($vatNumber, -1);
        $checkVal = $this->sumWeights($weights, $vatNumber);
        $checkVal = 3 - ($checkVal % 11);

        if ($checkVal == -1) {
            return false;
        }

        if ($checkVal < -1) {
            $checkVal += 11;
        }

        return $checksum == $checkVal;
    }
}

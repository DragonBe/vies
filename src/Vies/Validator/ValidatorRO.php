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
 * Class ValidatorRO
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10]
 *
 * Range:
 *      C1 ... C10 Numeric
 *      Note that if the length is less than 10 digits, leading zeros must be assumed to perform the computation.
 *
 * Rules:
 * C10
 *      A1 = C1*7 + C2*5 + C3*3 + C4*2 + C5*1 + C6*7 + C7*5 + C8*3 + C9*2
 *      A2 = A1 * 10
 *      R1 = A2 modulo 11
 *      If R1 = 10, then R = 0
 *      Else R = R1
 *      C10 = R
 */
class ValidatorRO extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) < 2 || strlen($vatNumber) > 10) {
            return false;
        }

        $vatNumber = str_pad($vatNumber, 10, "0", STR_PAD_LEFT);
        $checksum = (int)$vatNumber[9];
        $weights = [7, 5, 3, 2, 1, 7, 5, 3, 2];
        $checkVal = $this->sumWeights($weights, $vatNumber);
        $checkVal = ($checkVal * 10) % 11;
        if ($checkVal == 10) {
            $checkVal = 0;
        }

        return $checkVal == $checksum;
    }
}

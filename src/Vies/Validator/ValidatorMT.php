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
 * Class ValidatorMT
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 *
 * Range:
 *      C1 ... C8 Numeric from 0 to 9
 *      C1 ... C6 > 100000
 *
 * Rules:
 * [C7 C8]
 *      A1 = 3*C1 + 4*C2 + 6*C3 + 7*C4 + 8*C5 + 9*C6
 *      R = 37 - (A1 modulo 37)
 *      If R = 00, then C7 C8 = 37
 *      C7 C8 = R
 */
class ValidatorMT extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }

        if ((int)substr($vatNumber, 0, 6) <= 100000) {
            return false;
        }

        $weights = [3, 4, 6, 7, 8, 9];
        $checksum = (int)substr($vatNumber, -2, 2);
        $checkVal = $this->sumWeights($weights, $vatNumber);
        $checkVal = intval(37 - ($checkVal % 37));

        return $checkVal == $checksum;
    }
}

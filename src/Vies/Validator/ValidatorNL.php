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
 * Class ValidatorNL
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11 C12]
 *
 * Range:
 *      C1 ... C9 Numeric from 0 to 9
 *      C10 Alphabetic “B”
 *      C11 ... C12 Numeric from 0 to 9
 *
 * Rules:
 * C9
 *      A1 = C1*9 + C2*8 + C3*7 + C4*6 + C5*5 + C6*4 + C7*3 + C8*2
 *      A2 = A1 modulo 11
 *      If A2 = 10 then number is invalid
 *      else C9 = A2
 *
 * [C11 C12]
 *      >00
 */
class ValidatorNL extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 12) {
            return false;
        }

        if (strtoupper($vatNumber[9]) != 'B') {
            return false;
        }

        if ((int)substr($vatNumber, -2) == 0) {
            return false;
        }

        $checksum = (int)$vatNumber[8];
        $weights = [9, 8, 7, 6, 5, 4, 3, 2];
        $checkval = $this->sumWeights($weights, $vatNumber);
        $checkval = ($checkval % 11) > 9 ? 0 : ($checkval % 11);

        return $checkval == $checksum;
    }
}

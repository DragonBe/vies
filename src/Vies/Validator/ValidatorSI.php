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
 * Class ValidatorSI
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 *
 * Range:
 *      C1 ... C8 Numeric
 *      C1 ... C7 1000000 and <= 9999999
 *
 * Rules:
 * C8
 *      A1 = C1*8 + C2*7 + C3*6 + C4*5 + C5*4 + C6*3 + C7*2
 *      R = 11 - (A1 modulo 11)
 *      If R = 10, then C8 = 0
 *      else if R = 11 then number is invalid
 *      else C8 = R
 */
class ValidatorSI extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }

        if (intval($vatNumber[0]) == 0) {
            return false;
        }

        $checksum = (int)$vatNumber[7];
        $weights = [8, 7, 6, 5, 4, 3, 2];
        $checkVal = $this->sumWeights($weights, $vatNumber);

        $mod = 11 - ($checkVal % 11);
        if ($mod === 11) {
            return false;
        }

        $checkVal = ($mod == 10) ? 0 : $mod;

        return $checksum == $checkVal;
    }
}

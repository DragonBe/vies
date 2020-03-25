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
 * Class ValidatorPT
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 > 0
 *
 * Rules:
 * C9
 *      R = 11 – (9*C1 + 8*C2 + 7*C3 + 6*C4 + 5*C5 + 4*C6 + 3*C7 + 2*C8) modulo 11
 *      If R= 10 or R= 11, Then R = 0
 *      C9 = R
 */
class ValidatorPT extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        $checksum = (int)$vatNumber[8];
        $weights = [9, 8, 7, 6, 5, 4, 3, 2];
        $checkVal = $this->sumWeights($weights, $vatNumber);
        $checkVal = (11 - ($checkVal % 11)) > 9 ? 0 : (11 - ($checkVal % 11));

        return $checksum == $checkVal;
    }
}

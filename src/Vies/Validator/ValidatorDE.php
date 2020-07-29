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
 * Class ValidatorDE
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 ... C9 Numeric
 *      C1 > 0
 *
 * Rules:
 * C9
 *      P = 10
 *
 *      For N = 1…8 (N = character position i.e. C1)
 *          S = CN + P
 *          M = S modulo 10
 *          If M = 0 then M = 10
 *          P = (2*M) modulo 11
 *
 *      R = 11 – P
 *      If R = 10
 *      then
 *          C9 = 0
 *      else
 *          C9 =R
 */
class ValidatorDE extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        $prod = 10;
        for ($i = 0; $i < 8; $i++) {
            $checkVal = ((int)$vatNumber[$i] + $prod) % 10;
            $checkVal = ($checkVal == 0) ? 10 : $checkVal;
            $prod = ($checkVal * 2) % 11;
        }

        $prod = $prod == 1 ? 11 : $prod;

        return 11 - $prod == (int) substr($vatNumber, -1);
    }
}

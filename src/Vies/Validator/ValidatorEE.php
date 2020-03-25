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
 * Class ValidatorEE
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 .. C9 Numeric
 *      C1C2 = 10
 *
 * Rules:
 * C9
 *      A1 = 3*1 + 7*0 + 1*0 + 3*2 + 7*0 + 1*7 + 3*4 + 7*1 = 35
 *      A2 = CEIL(35;10) = 40
 *      C9 = 40 - 35 = 5
 */
class ValidatorEE extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        $checkVal = $this->sumWeights([3, 7, 1, 3, 7, 1, 3, 7], $vatNumber);
        $checkVal = (ceil($checkVal / 10) * 10) - $checkVal;

        return $checkVal == (int)$vatNumber[8];
    }
}

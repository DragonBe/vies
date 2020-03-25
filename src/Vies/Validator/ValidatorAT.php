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
 * Class ValidatorAT
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 Alphabetic
 *      C2 ... C9 Numeric from 0 to 9
 *
 * Rules:
 * C1
 *      U
 * C9
 *      Si = INT(Ci / 5) + (Ci * 2) modulo10
 *      R = S3 + S5 + S7
 *      (10 – (R + C2 + C4 + C6 + C8 + 4) modulo 10) modulo 10
 *
 */
class ValidatorAT extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        if (strtoupper($vatNumber[0]) != 'U') {
            return false;
        }

        $checkVal = 0;
        for ($i = 1; $i < 8; $i++) {
            $checkVal += $this->crossSum((int)$vatNumber[$i] * ($this->isEven($i) ? 2 : 1));
        }

        $checkVal = substr((string)(96 - $checkVal), -1);

        return (int)$vatNumber[8] == $checkVal;
    }
}

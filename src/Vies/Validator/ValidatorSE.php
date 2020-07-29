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
 * Class ValidatorSE
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11 C12]
 *
 * Range:
 *      C1 ... C12 Numeric
 *      [C11 C12] >=01 and <= 94
 *
 * Rules:
 * C10
 *      = (10 – (R + C2 + C4 + C6 + C8) modulo 10) modulo 10
 *      Where R = S1 + S3 + S5 + S7 + S9
 *      Where Si = INT(Ci/5) + (Ci*2)modulo 10
 */
class ValidatorSE extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 12) {
            return false;
        }

        if ((int)substr($vatNumber, -2) < 1 || (int)substr($vatNumber, -2) > 94) {
            return false;
        }

        $checksum = (int)$vatNumber[9];
        $checkVal = 0;

        for ($i = 1; $i < 10; $i++) {
            $checkVal += $this->crossSum((int)$vatNumber[9 - $i] * ($this->isEven($i) ? 1 : 2));
        }

        return $checksum == (($checkVal % 10) == 0 ? 0 : 10 - ($checkVal % 10));
    }
}

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
 * Class ValidatorSK
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10]
 *
 * Range:
 *      C1 ... C10 Numeric
 *      C1 In the range 1...9
 *      C2, C4 ... C10 In the range 0...9
 *      C3 One of 2, 3, 4, 7, 8, 9
 *
 * Rules:
 * [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10]
 *      [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10] modulo 11 = 0
 */
class ValidatorSK extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 10) {
            return false;
        }

        if (intval($vatNumber[0]) == 0) {
            return false;
        }

        return in_array((int) $vatNumber[2], [2, 3, 4, 7, 8, 9])
            && $vatNumber % 11 == 0;
    }
}

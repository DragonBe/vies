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
 * Class ValidatorCY
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 ... C8 Numeric from 0 to 9
 *      C9 Alphabetic
 *      C1 0, 1, 3, 4, 5, 9
 *
 * Rules:
 * C1 C2
 *      C1C2 cannot be 12 (e.g. 12000139V is invalid)
 */
class ValidatorCY extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        if (intval(substr($vatNumber, 0, 2) == 12)) {
            return false;
        }

        return in_array((int) $vatNumber[0], [0, 1, 3, 4, 5, 9], true)
            && ctype_alpha($vatNumber[8]);
    }
}

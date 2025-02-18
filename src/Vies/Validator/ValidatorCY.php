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
 *      C1 0, 1, 3, 4, 5, 6, 9
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

        $total = 0;
        for ($i = 0; $i < 8; ++$i) {
            $temp = (int) $vatNumber[$i];
            if (0 === $i % 2) {
                if (0 === $temp) {
                    $temp = 1;
                } elseif (1 === $temp) {
                    $temp = 0;
                } elseif (2 === $temp) {
                    $temp = 5;
                } elseif (3 === $temp) {
                    $temp = 7;
                } elseif (4 === $temp) {
                    $temp = 9;
                } else {
                    $temp = 2 * $temp + 3;
                }
            }
            $total += $temp;
        }

        // Establish check digit using modulus 26, and translate to char. equivalent.
        $total = $total % 26;
        $total = chr($total + 65);

        // Check to see if the check digit given is correct
        return $vatNumber[8] === $total;
    }
}

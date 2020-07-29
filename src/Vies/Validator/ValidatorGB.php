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
 * Class ValidatorGB
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5]
 *             [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *             [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11 C12]
 *
 * Range:
 *      C1 .. C2 Alphanumeric from A to Z or 0 to 9
 *      C2 .. C11 Numeric from 0 to 9
 *
 * Rules:
 * Case 1:
 *      [C1 C2] Alpha: “GD” or “HA”
 *      C3 ... C5 Numeric from 0 to 9
 *
 *      if [C1 C2] = “GD”
 *          [C3 C4 C5] from 000 to 499
 *
 *      If [C1 C2] = “HA”
 *          [C3 C4 C5] from 500 to 999
 *
 * Case 2
 *      [C1 C2 C3] from 000 to 009 are numbers for Isle of Man
 *      [C10 C11 C12] > 000
 *      [C1 C2 C3 C4 C5 C6 C7 C8 C9] >000000000
 *
 *      [C8 C9]
 *          R1 = (8*C1 + 7*C2 + 6*C3 + 5*C4 + 4*C5 + 3*C6 + 2*C7 + C8C9) modulo 97
 *          R2 = ((8*C1 + 7*C2 + 6*C3 + 5*C4 + 4*C5 + 3*C6 + 2*C7 + C8C9) + 55) modulo 97
 *          Either R1 or R2 must equal to zero.
 *
 */
class ValidatorGB extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) == 5) {
            return $this->validateGovernment($vatNumber);
        }

        if (strlen($vatNumber) != 9 && strlen($vatNumber) != 12) {
            return false;
        }

        $weights = [8, 7, 6, 5, 4, 3, 2];
        $checkVal = $this->sumWeights($weights, $vatNumber);
        $checkVal += (int)substr($vatNumber, 7, 2);

        $Result1 = $checkVal % 97;
        $Result2 = ($Result1 + 55) % 97;

        return ! ($Result1 * $Result2);
    }

    /**
     * Validate Government VAT
     *
     * @param  string $vatNumber
     *
     * @return bool
     */
    private function validateGovernment(string $vatNumber): bool
    {
        $prefix = strtoupper(substr($vatNumber, 0, 2));
        $number = (int) substr($vatNumber, 2, 3);

        // Government departments
        if ($prefix == 'GD') {
            return $number < 500;
        }

        // Health authorities
        if ($prefix == 'HA') {
            return $number > 499;
        }

        return false;
    }
}

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
 * Class ValidatorNL
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11 C12]
 */
class ValidatorNL extends ValidatorAbstract
{
    /**
     * Check Character: 10-A, 11-B .... 35-Z, 36-+, 37-*
     *
     * @var array
     */
    protected $checkCharacter = [
        'A' => 10,
        'B' => 11,
        'C' => 12,
        'D' => 13,
        'E' => 14,
        'F' => 15,
        'G' => 16,
        'H' => 17,
        'I' => 18,
        'J' => 19,
        'K' => 20,
        'L' => 21,
        'M' => 22,
        'N' => 23,
        'O' => 24,
        'P' => 25,
        'Q' => 26,
        'R' => 27,
        'S' => 28,
        'T' => 29,
        'U' => 30,
        'V' => 31,
        'W' => 32,
        'X' => 33,
        'Y' => 34,
        'Z' => 35,
        '+' => 36,
        '*' => 37,
    ];

    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 12) {
            return false;
        }

        if (strtoupper($vatNumber[9]) != 'B') {
            return false;
        }

        return $this->validateCommercial($vatNumber) || $this->validateSoleProprietor($vatNumber);
    }

    /**
     * Range:
     *      C1 ... C9 Numeric from 0 to 9
     *      C10 Alphabetic “B”
     *      C11 ... C12 Numeric from 0 to 9
     *
     * Rules:
     * C9
     *      A1 = C1*9 + C2*8 + C3*7 + C4*6 + C5*5 + C6*4 + C7*3 + C8*2
     *      A2 = A1 modulo 11
     *      If A2 = 10 then number is invalid
     *      else C9 = A2
     *
     * [C11 C12]
     *      >00
     *
     * @param string $vatNumber
     * @return bool
     */
    protected function validateCommercial(string $vatNumber): bool
    {
        if ((int)substr($vatNumber, -2) == 0) {
            return false;
        }

        $checksum = (int)$vatNumber[8];
        $weights = [9, 8, 7, 6, 5, 4, 3, 2];
        $checkVal = $this->sumWeights($weights, $vatNumber);
        $checkVal = ($checkVal % 11) > 9 ? 0 : ($checkVal % 11);

        return $checkVal == $checksum;
    }

    /**
     * Range:
     *      C1 ... C9 0-9 A-Z + *
     *      C10 Alphabetic “B”
     *      C11 ... C12 Numeric from 0 to 9
     *
     * [C11 C12]
     *      02 - 98
     *
     * @param string $vatNumber
     * @return bool
     */
    protected function validateSoleProprietor(string $vatNumber): bool
    {
        if (! preg_match("#^[A-Z0-9+*]{9}B[0-9]{2}$#u", $vatNumber)) {
            return false;
        }

        $sumBase = array_reduce(str_split($vatNumber), function ($acc, $e) {
            if (ctype_digit($e)) {
                return $acc.$e;
            }

            return $acc.$this->checkCharacter[$e];
        }, '2321');

        if (PHP_INT_SIZE === 4 && function_exists('bcmod')) {
            return bcmod($sumBase, '97') === '1';
        } else {
            return ((int) $sumBase % 97) === 1;
        }
    }
}

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
 * Class ValidatorFR
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11]
 *
 * Range:
 *      C1 .. C2 Alphanumeric from A to Z or 0 to 9
 *      C2 .. C11 Numeric from 0 to 9
 *
 * Rules:
 * Case 1: Old Style
 *      [C1 C2] = ([C3 C4 C5 C6 C7 C8 C9 C10 C11] [1 2])modulo 97
 *
 * Case 2 : New Style
 *      S1 = Check Character (C1)
 *      S2 = Check Character (C2)
 *
 *      If C1 numeric then
 *          C2 alphabetic
 *          S = (S1 * 24) + (S2 – 10)
 *
 *      IF C1 alphabetic then
 *          S = (S1*34) + (S2-100)
 *
 *      P = (S/11) + 1
 *      R1 = (S)modulo11
 *      R2 = ( [C3 C4 C5 C6 C7 C8 C9 C10 C11] + P)modulo11
 *      R1 = R2
 *
 *      Check Character
 *      0-0, 1-1, 2-2, 3-3, 4-4, 5-5, 6-6, 7-7, 8-8, 9-9, 10-A, 11-B, 12-C, 13-D, 14-E, 15-F, 16-G, 17-H, 18-J, 19-K,
 *      20-L, 21-M, 22-N, 23-P, 24-Q, 25-R, 26-S, 27-T, 28-U, 29-V, 30-W, 31-X, 32-Y, 33-Z.
 *
 */
class ValidatorFR extends ValidatorAbstract
{
    /**
     * the valid characters for the first two digits (O and I are missing)
     *
     * @var string
     */
    protected $alphabet = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';

    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }

        if (strpos($this->alphabet, $vatNumber[0]) === false) {
            return false;
        }

        if (strpos($this->alphabet, $vatNumber[1]) === false) {
            return false;
        }

        $checksum = substr($vatNumber, 0, 2);

        if (ctype_digit($checksum)) {
            return $checksum == $this->validateOld($vatNumber);
        }

        return $checksum == $this->validateNew($vatNumber);
    }

    /**
     * @param string $vatNumber
     *
     * @return string
     */
    private function validateOld(string $vatNumber): string
    {
        $checkVal = substr($vatNumber, 2);
        if (! ctype_digit($checkVal)) {
            return "";
        }
        $checkVal .= "12";
        if (PHP_INT_SIZE === 4 && function_exists("bcmod")) {
            $checkVal = (int) bcmod($checkVal, "97");
        } else {
            $checkVal = intval($checkVal) % 97;
        }

        return $checkVal == 0 ? "00" : (string) $checkVal;
    }

    /**
     * @param string $vatNumber
     *
     * @return bool
     */
    private function validateNew(string $vatNumber): bool
    {
        $multiplier = 34;
        $subStractor = 100;
        if (ctype_digit($vatNumber[0])) {
            $multiplier = 24;
            $subStractor = 10;
        }

        $checkCharacter = array_flip(str_split($this->alphabet));
        $checkVal = ($checkCharacter[$vatNumber[0]] * $multiplier) + $checkCharacter[$vatNumber[1]] - $subStractor;

        if (PHP_INT_SIZE === 4 && function_exists("bcmod")) {
            return (int) bcmod(bcadd(substr($vatNumber, 2), strval(($checkVal / 11) + 1)), "11") === $checkVal % 11;
        } else {
            return ((intval(substr($vatNumber, 2)) + ($checkVal / 11) + 1) % 11) == $checkVal % 11;
        }
    }
}

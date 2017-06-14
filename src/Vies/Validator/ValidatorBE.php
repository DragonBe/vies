<?php
/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

/**
 * Class ValidatorBE
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C0 C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C0 Always the digit '0'
 *      C1 Numeric from 1 to 9
 *      C2 ... C9 Numeric from 0 to 9
 *
 * Rules:
 * [C8 C9]
 *      97 - ([C0 C1 C2 C3 C4 C5 C6 C7] modulo 97)
 */
class ValidatorBE extends ValidatorAbstract
{

    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber): bool
    {
        if (strlen($vatNumber) == 9) {
            $vatNumber = "0" . $vatNumber;
        }

        if (strlen($vatNumber) != 10) {
            return false;
        }

        $checkvals = (int)substr($vatNumber, 0, -2);
        $checksum = (int)substr($vatNumber, -2);
        if (97 - ($checkvals % 97) != $checksum) {
            return false;
        }

        return true;
    }
}

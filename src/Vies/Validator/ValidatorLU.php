<?php
/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

/**
 * Class ValidatorLU
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 *
 * Range:
 *      C1 ... C8 Numeric from 0 to 9
 *
 * Rules:
 * [C7 C8]
 *      ([C1 C2 C3 C4 C5 C6]) Modulo 89
 */
class ValidatorLU extends ValidatorAbstract
{

    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber): bool
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }

        $checksum = (int)substr($vatNumber, -2);
        $checkval = (int)substr($vatNumber, 0, 6);

        if (($checkval % 89) != $checksum) {
            return false;
        }

        return true;
    }
}

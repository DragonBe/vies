<?php
/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

/**
 * Class ValidatorHU
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 *
 * Range:
 *      C1 ... C8 Numeric from 0 to 9
 *
 * Rules:
 * C8
 *      A1 = 9*C1 + 7*C2 + 3*C3 + 1*C4 + 9*C5 + 7*C6 + 3*C7
 *      If the number in the right hand column of A1 is zero then C8 = 0
 *      Otherwise, subtract the number in the right hand column of A1 from 10
 *      C8 = A1
 */
class ValidatorHU extends ValidatorAbstract
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

        $weights = [9, 7, 3, 1, 9, 7, 3];
        $checksum = (int)$vatNumber[7];
        $checkval = $this->sumWeights($weights, $vatNumber);

        $checkval = (int)substr($checkval, -1);

        $checkval = ($checkval > 0) ? 10 - $checkval : 0;

        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }
}

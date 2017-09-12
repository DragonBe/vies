<?php
/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

/**
 * Class ValidatorBG
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 ... C9 Numeric from 0 to 9
 *
 * Rules:
 * C9
 *      A1 = 1*C1 + 2*C2 + 3*C3 + 4*C4 + 5*C5 + 6*C6 + 7*C7 + 8*C8
 *      R1 = A1 modulo 11
 *      If R1 = 10, then
 *          A2 = 3*C1 + 4*C2 + 5*C3 + 6*C4 + 7*C5 + 8*C6 + 9*C7 + 10*C8
 *          R2 = A2 modulo 11
 *          If R2 = 10 then R = 0
 *          Else R = R2
 *      Else R = R1
 *      C9 = R
 */
class ValidatorBG extends ValidatorAbstract
{

    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        $weights = [1, 2, 3, 4, 5, 6, 7, 8];
        $checksum = (int)$vatNumber[8];
        $checkval = $this->sumWeights($weights, $vatNumber);

        if ($checkval % 11 == 10) {
            $weights = [3, 4, 5, 6, 7, 8, 9, 10];
            $checkval = $this->sumWeights($weights, $vatNumber);

            $checkval = ($checkval % 11) == 10 ? 0 : ($checkval % 11);
        } else {
            $checkval = $checkval % 11;
        }

        if ($checkval != $checksum) {
            return false;
        }

        return true;
    }
}

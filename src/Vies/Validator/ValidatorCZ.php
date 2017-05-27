<?php
/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

/**
 * Class ValidatorCZ
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 *
 * Range:
 *      C1 ... C8 Numeric from 0 to 9
 *      C1 <> 9
 *
 * Rules:
 * C8
 *      A1 = 8*C1 + 7*C2 + 6*C3 + 5*C4 + 4*C5 + 3*C6 + 2*C7
 *      A2 = nearest higher multiple of 11
 *
 *      if A1 mod 11 = 0
 *      then
 *          A2= A1 + 11
 *      else
 *          A2 = CEIL1(A1/11, 1) * 11
 *
 *      D = A2 -A1
 *      C8 = D mod 10
 */
class ValidatorCZ extends ValidatorAbstract
{

    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber)
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }

        if (intval($vatNumber[0] == 9)) {
            return false;
        }

        $weights = [8, 7, 6, 5, 4, 3, 2];
        $checksum = (int)$vatNumber[7];
        $checkbase = $this->sumWeights($weights, $vatNumber);

        $checkval = ($checkbase % 11) ? ceil($checkbase / 11.1) * 11 : (($checkbase % 11) + 11);

        if ($checksum != $checkval - $checkbase) {
            return false;
        }

        return true;
    }
}

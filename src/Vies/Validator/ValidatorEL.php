<?php
/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

/**
 * Class ValidatorEL
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 .. C9 Numeric
 *
 * Rules:
 * C9
 *      A1 = 256*C1 + 128*C2 + 64*C3 + 32*C4 + 16*C5 + 8*C6 + 4*C7 + 2*C8
 *      A2 = A1 modulo 11
 *      C9 = A2 modulo 10
 */
class ValidatorEL extends ValidatorAbstract
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

        $checksum = $vatNumber[8];
        $weights = [256, 128, 64, 32, 16, 8, 4, 2];
        $checkval = $this->sumWeights($weights, $vatNumber);
        $checkval = ($checkval % 11) > 9 ? 0 : ($checkval % 11);

        if ($checkval != $checksum) {
            return false;
        }

        return true;
    }
}

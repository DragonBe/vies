<?php
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
 *      C1 0, 1, 3, 4, 5, 9
 *
 * Rules:
 * C1 C2
 *      C1C2 cannot be 12 (e.g. 12000139V is invalid)
 */
class ValidatorCY extends ValidatorAbstract
{
    protected $allowedC1 = [0, 1, 3, 4, 5, 9];

    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        if (intval(substr($vatNumber, 0, 2) == 12)) {
            return false;
        }

        if (!in_array($vatNumber[0], $this->allowedC1)) {
            return false;
        }

        if (!ctype_alpha($vatNumber[8])) {
            return false;
        }

        return true;
    }
}

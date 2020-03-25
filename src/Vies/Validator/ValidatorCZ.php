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
     * @var array
     */
    protected $allowedD = [8, 7, 6, 5, 4, 3, 2, 1, 0, 9, 8];

    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate(string $vatNumber): bool
    {
        $vatLength = strlen($vatNumber);

        if ($vatLength === 8) {
            return $this->validateLegalEntities($vatNumber);
        } elseif ($vatLength === 10) {
            return $this->validateIndividualsLong($vatNumber);
        } elseif ($vatLength === 9) {
            if ($vatNumber[0] == "6") {
                return $this->validateIndividualsShortSpecial($vatNumber);
            } else {
                return $this->validateIndividualsShort($vatNumber);
            }
        }

        return false;
    }

    /**
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
     *
     * @param string $vatNumber
     * @return bool
     */
    protected function validateLegalEntities($vatNumber)
    {
        $weights = [8, 7, 6, 5, 4, 3, 2];
        $checksum = (int)$vatNumber[7];
        $checkBase = $this->sumWeights($weights, $vatNumber);

        $checkval = ($checkBase % 11) ? intval(ceil($checkBase / 11) * 11) : intval($checkBase + 11);
        $checkval = ($checkval - $checkBase) % 10;

        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }

    /**
     * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
     *
     * Range:
     *      C1 ... C9 Numeric from 0 to 9
     *
     * Rules:
     *      C1 C2 ... must 00-53
     *      C3 C4 ... Month of birth
     *                >= 1 <=12
     *                >= 51 <= 62
     *      C5 C6 ... Day of birth
     *
     * @param string $vatNumber
     * @return bool
     */
    protected function validateIndividualsShort($vatNumber)
    {
        $monthBase = array_merge(range(1, 12), range(51, 62));

        $yearOfBirth = (int)substr($vatNumber, 0, 2);
        $monthOfBirth = (int)substr($vatNumber, 2, 2);
        $dayOfBirth = (int)substr($vatNumber, 4, 2);

        //validate day
        if ($dayOfBirth < 1 || $dayOfBirth > 31) {
            return false;
        }

        //validate month
        if (in_array($monthOfBirth, $monthBase) === false) {
            return false;
        }

        //validate year
        if ($yearOfBirth > 53) {
            return false;
        }

        return true;
    }

    /**
     * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
     *
     * Range:
     *      C1 ... C9 Numeric
     *      C1 = 6
     *
     * Rules:
     *      C9 =
     *      A1 = 8*C2 + 7*C3 + 6*C4 + 5*C5 + 4*C6 + 3*C7 + 2*C8
     *      A2 = nearest higher multiple of 11
     *
     *      if A1 mod 11 = 0
     *          then
     *      A2 = A1 + 11
     *          else
     *      A2 = CEIL1(A1/11, 1) * 11
     *      D = A2 -A1
     *
     * @param string $vatNumber
     * @return bool
     */
    protected function validateIndividualsShortSpecial($vatNumber)
    {
        $weights = [0, 8, 7, 6, 5, 4, 3, 2];
        $checkval = $this->sumWeights($weights, $vatNumber, 1);
        $checksum = ($checkval % 11);

        if ($checksum > 0) {
            $checksum = ceil($checkval / 11) * 11;
        } else {
            $checksum = $checkval + 11;
        }

        $checksum = $checksum - $checkval;
        $checkval = $this->allowedD[$checksum - 1];

        if ($vatNumber[8] != $checkval) {
            return false;
        }

        return true;
    }

    /**
     * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10]
     *
     * Range:
     *      C1 ... C10 Numeric from 0 to 9
     *      C1 C2 C3 C4 C5 C6 ... Represents a date of birth of an individual
     *
     * Rules:
     *      C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 must be devided by 11 without remainder.
     *      C1 C2 ... must be in the range 00-[last two digits of current date year] or 54-99
     *      C3 C4 ... Month of birth
     *      C5 C6 ... Day of birth
     *
     *      C10 ... A1 = C1C2 + C3C4 + C5C6 + C7C8 + C9C10
     *              A1 must be divisible by 11 with no remainder.
     *
     * @param string $vatNumber
     * @return bool
     */
    public function validateIndividualsLong($vatNumber)
    {
        $monthBase = array_merge(range(1, 12), range(21, 32), range(51, 62), range(71, 82));

        $yearOfBirth = (int)substr($vatNumber, 0, 2);
        $monthOfBirth = (int)substr($vatNumber, 2, 2);
        $dayOfBirth = (int)substr($vatNumber, 4, 2);

        //validate day
        if ($dayOfBirth < 1 || $dayOfBirth > 31) {
            return false;
        }

        //validate month
        if (in_array($monthOfBirth, $monthBase) === false) {
            return false;
        }

        //validate year
        if ($yearOfBirth > (int)date("y") && $yearOfBirth < 54) {
            return false;
        }

        $checkval = 0;

        for ($i = 0; $i <= 8; $i += 2) {
            $checkval += (int)substr($vatNumber, $i, 2);
        }

        $checkval = ($checkval % 11);

        if ($checkval) {
            return false;
        }

        return true;
    }
}

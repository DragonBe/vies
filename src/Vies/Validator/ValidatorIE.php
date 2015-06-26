<?php
/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

/**
 * Class ValidatorIE
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 *
 */
class ValidatorIE extends ValidatorAbstract
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

        if (!$this->validateIENew($vatNumber) && !$this->validateIEOld($vatNumber)) {
            return false;
        }

        return true;
    }

    /**
     * @param $vatNumber
     * @return bool
     */
    private function validateIEOld($vatNumber)
    {
        $transform = array('0', substr($vatNumber, 2, 5), $vatNumber[0], $vatNumber[7]);
        $vat_id = join('', $transform);

        return $this->validateIENew($vat_id);
    }

    /**
     * @param $vatNumber
     * @return bool
     */
    private function validateIENew($vatNumber)
    {
        $checksum = strtoupper(substr($vatNumber, -1));
        $checkval = 0;
        $checkchar = 'A';
        for ($i = 2; $i <= 8; $i++) {
            $checkval += (int)$vatNumber[8 - $i] * $i;
        }
        $checkval = ($checkval % 23);

        if ($checkval == 0) {
            $checkchar = 'W';
        } else {
            for ($i = $checkval - 1; $i > 0; $i--) {
                $checkchar++;
            }
        }
        if ($checkchar != $checksum) {
            return false;
        }

        return true;
    }
}

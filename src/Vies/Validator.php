<?php
/**
 * \DragonBe\Vies
 *
 * Component using the European Commission (EC) VAT Information Exchange System (VIES) to verify and validate VAT
 * registration numbers in the EU, using PHP and Composer.
 *
 * @author Michelangelo van Dam <dragonbe+github@gmail.com>
 * @license MIT
 *
 */
namespace DragonBe\Vies;

/**
 * Class Validator
 * @category DragonBe
 * @package \DragonBe\Vies
 */
class Validator
{
    /**
     * @param string $countryCode The two-character country code of a European
     * member country
     * @param string $vatNumber The VAT number (without the country
     * identification) of a registered company
     * @return bool
     */
    public function validate($countryCode, $vatNumber)
    {
        return call_user_func(array($this, 'validate' . $countryCode), $vatNumber);
    }

    protected function validateAT($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }
        if (strtoupper($vatNumber[0]) != 'U') {
            return false;
        }
        $checksum = (int)$vatNumber[8];
        $checkval = 0;
        for ($i = 1; $i < 8; $i++) {
            $checkval += $this->crossSum((int)$vatNumber[$i] * ($this->isEven($i) ? 2 : 1));
        }
        $checkval = substr((string)(96 - $checkval), -1);
        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }

    protected function validateBE($vatNumber)
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

    protected function validateBG($vatNumber)
    {
        if (strlen($vatNumber) == 9 && is_numeric($vatNumber)) {
            return true;
        }

        if (strlen($vatNumber) == 10 && is_numeric($vatNumber)) {
            return true;
        }

        return false;
    }

    protected function validateCY($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        return true;
    }

    protected function validateCZ($vatNumber)
    {
        if ((strlen($vatNumber) != 8) && (strlen($vatNumber) != 9) && (strlen($vatNumber) != 10)) {
            return false;
        }
        if (!is_numeric($vatNumber)) {
            return false;
        }

        return true;
    }

    protected function validateDE($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }
        $prod = 10;
        $checksum = (int)substr($vatNumber, -1);
        for ($i = 0; $i < 8; $i++) {
            $checkval = ((int)$vatNumber[$i] + $prod) % 10;
            if ($checkval == 0) {
                $checkval = 10;
            }
            $prod = ($checkval * 2) % 11;
        }
        $prod = $prod == 1 ? 11 : $prod;
        if (11 - $prod != $checksum) {
            return false;
        }

        return true;
    }

    protected function validateDK($vatNumber)
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }
        $weights = array(2, 7, 6, 5, 4, 3, 2, 1);
        $checksum = 0;
        for ($i = 0; $i < 8; $i++) {
            $checksum += (int)$vatNumber[$i] * $weights[$i];
        }
        if (($checksum % 11) > 0) {
            return false;
        }

        return true;
    }

    protected function validateEE($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }
        if (!is_numeric($vatNumber)) {
            return false;
        }

        return true;
    }

    protected function validateEL($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }
        $checksum = substr($vatNumber, -1);
        $checkval = 0;
        for ($i = 1; $i <= 8; $i++) {
            $checkval += (int)$vatNumber[8 - $i] * pow(2, $i);
        }
        $checkval = ($checkval % 11) > 9 ? 0 : ($checkval % 11);
        if ($checkval != $checksum) {
            return false;
        }

        return true;
    }

    protected function validateES($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        if (!is_numeric(substr($vatNumber, 1, 6))) {
            return false;
        }

        $allowed = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'N', 'P', 'Q', 'R', 'S', 'W');
        $checkval = false;
        if (in_array($vatNumber[0], $allowed)) {
            $checkval = true;
        }
        if (!$checkval) {
            return false;
        }

        $checksum = (int)$vatNumber[8];
        $checkval = 0;
        for ($i = 2; $i <= 8; $i++) {
            $checkval += $this->crossSum((int)$vatNumber[9 - $i] * ($this->isEven($i) ? 2 : 1));
        }
        if ($checksum != 10 - ($checkval % 10)) {
            return false;
        }

        return true;
    }

    protected function validateFI($vatNumber)
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }
        $weights = array(7, 9, 10, 5, 8, 4, 2);
        $checkval = 0;
        $checksum = (int)substr($vatNumber, -1);
        for ($i = 0; $i < 7; $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }
        if (11 - ($checkval % 11) != $checksum) {
            return false;
        }

        return true;
    }

    protected function validateFR($vatNumber)
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }
        if (!is_numeric(substr($vatNumber, 2))) {
            return false;
        }

        return true;
    }

    protected function validateHR($vatNumber)
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }
        if (!is_numeric($vatNumber)) {
            return false;
        }

        return true;
    }

    protected function validateHU($vatNumber)
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }
        if (!is_numeric($vatNumber)) {
            return false;
        }

        return true;
    }

    protected function validateIE($vatNumber)
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }
        if (!$this->validateIENew($vatNumber) && !$this->validateIEOld($vatNumber)) {
            return false;
        }

        return true;
    }

    protected function validateIEOld($vatNumber)
    {
        $transform = array('0', substr($vatNumber, 2, 5), $vatNumber[0], $vatNumber[7]);
        $vat_id = join('', $transform);

        return $this->validateIENew($vat_id);
    }

    protected function validateIENew($vatNumber)
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

    protected function validateIT($vatNumber)
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }
        if (substr($vatNumber, 0, 7) == '0000000') {
            return false;
        }

        $checksum = (int)substr($vatNumber, -1);
        $S1 = $S2 = 0;
        for ($i = 1; $i <= 10; $i++) {
            if (!$this->isEven($i)) {
                $S1 += $vatNumber[$i - 1];
            } else {
                $S2 += (int)($vatNumber[$i - 1] / 5) + ((2 * $vatNumber[$i - 1]) % 10);
            }
        }

        $checkval = (10 - ($S1 + $S2) % 10) % 10;

        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }

    protected function validateLU($vatNumber)
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

    protected function validateLV($vatNumber)
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }

        if ((int)$vatNumber[0] <= 3) {
            return false;
        }

        $weights = array(9, 1, 4, 8, 3, 10, 2, 5, 7, 6);
        $checkval = 0;
        $checksum = (int)substr($vatNumber, -1);
        for ($i = 0; $i < 10; $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }
        $checkval = 3 - ($checkval % 11);
        if ($checkval == -1) {
            return false;
        }
        if ($checkval < -1) {
            $checkval += 11;
        }

        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }

    protected function validateLT($vatNumber)
    {
        if ((strlen($vatNumber) != 12) && (strlen($vatNumber) != 9)) {
            return false;
        }
        if (!is_numeric($vatNumber)) {
            return false;
        }

        return true;
    }

    public function validateMT($vatNumber)
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }

        if ((int)substr($vatNumber, 0, 6) <= 100000) {
            return false;
        }

        $weights = array(3, 4, 6, 7, 8, 9);
        $checkval = 0;
        $checksum = (int)substr($vatNumber, -2, 2);
        for ($i = 0; $i < 6; $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }
        $checkval = intval(37 - ($checkval % 37));
        if ($checkval != $checksum) {
            return false;
        }

        return true;
    }

    public function validateNL($vatNumber)
    {
        if (strlen($vatNumber) != 12) {
            return false;
        }
        if (strtoupper($vatNumber[9]) != 'B') {
            return false;
        }
        if ((int)$vatNumber[10] == 0 && (int)$vatNumber[11] == 0) {
            return false;
        }
        $checksum = (int)$vatNumber[8];
        $checkval = 0;
        for ($i = 2; $i <= 9; $i++) {
            $checkval += (int)$vatNumber[9 - $i] * $i;
        }
        $checkval = ($checkval % 11) > 9 ? 0 : ($checkval % 11);
        if ($checkval != $checksum) {
            return false;
        }

        return true;
    }

    protected function validatePL($vatNumber)
    {
        if (strlen($vatNumber) != 10) {
            return false;
        }
        $weights = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
        $checksum = (int)$vatNumber[9];
        $checkval = 0;
        for ($i = 0; $i < count($weights); $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }
        $checkval = $checkval % 11;
        if ($checkval != $checksum) {
            return false;
        }

        return true;
    }

    protected function validatePT($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }
        $checksum = (int)$vatNumber[8];
        $checkval = 0;
        for ($i = 2; $i < 10; $i++) {
            $checkval += (int)$vatNumber[9 - $i] * $i;
        }
        $checkval = (11 - ($checkval % 11)) > 9 ? 0 : (11 - ($checkval % 11));
        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }

    protected function validateRO($vatNumber)
    {
        if (strlen($vatNumber) < 2 || strlen($vatNumber) > 10) {
            return false;
        }

        $vatNumber = str_pad($vatNumber, 10, "0", STR_PAD_LEFT);

        $weights = array(7, 5, 3, 2, 1, 7, 5, 3, 2);
        $checksum = (int)$vatNumber[9];
        $checkval = 0;
        for ($i = 0; $i < count($weights); $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }

        $checkval = ($checkval * 10) % 11;
        if ($checkval == 10) {
            $checkval = 0;
        }

        if ($checkval != $checksum) {
            return false;
        }

        return true;
    }

    protected function validateSE($vatNumber)
    {
        if (strlen($vatNumber) != 12) {
            return false;
        }
        if ((int)substr($vatNumber, -2) < 1 || (int)substr($vatNumber, -2) > 94) {
            return false;
        }
        $checksum = (int)$vatNumber[9];
        $checkval = 0;
        for ($i = 1; $i < 10; $i++) {
            $checkval += $this->crossSum((int)$vatNumber[9 - $i] * ($this->isEven($i) ? 1 : 2));
        }
        if ($checksum != (($checkval % 10) == 0 ? 0 : 10 - ($checkval % 10))) {
            return false;
        }

        return true;
    }

    protected function validateSI($vatNumber)
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }
        if (intval($vatNumber[0]) == 0) {
            return false;
        }
        $checksum = (int)$vatNumber[7];
        $checkval = 0;
        for ($i = 2; $i <= 8; $i++) {
            $checkval += (int)$vatNumber[8 - $i] * $i;
        }
        $checkval = ($checkval % 11) == 10 ? 0 : 11 - ($checkval % 11);
        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }

    protected function validateSK($vatNumber)
    {
        if (strlen($vatNumber) != 10) {
            return false;
        }
        if (intval($vatNumber[0]) == 0) {
            return false;
        }
        if (((int)$vatNumber[2] == 0) ||
            ((int)$vatNumber[2] == 1) ||
            ((int)$vatNumber[2] == 5) ||
            ((int)$vatNumber[2] == 6)
        ) {
            return false;
        }
        if (($vatNumber % 11) != 0) {
            return false;
        }

        return true;
    }

    protected function validateGB($vatNumber)
    {
        //This format applies to Government departments and Health authorities
        if (strlen($vatNumber) == 5) {
            if ((substr($vatNumber, 0, 2) != "GD") && (substr($vatNumber, 0, 2) != "HA")) {
                return false;
            }

            if ((substr($vatNumber, 0, 2) == "GD")) {
                if ((int)substr($vatNumber, 2, 3) > 499) {
                    return false;
                }
            }

            if ((substr($vatNumber, 0, 2) == "HA")) {
                if ((int)substr($vatNumber, 2, 3) < 500) {
                    return false;
                }
            }
        }

        if (strlen($vatNumber) != 9 && strlen($vatNumber) != 12) {
            return false;
        }

        $weights = array(8, 7, 6, 5, 4, 3, 2);
        $checkval = 0;
        for ($i = 0; $i < count($weights); $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }
        $checkval += (int)substr($vatNumber, 7, 2);

        $R1 = $checkval % 97;
        $R2 = ($R1 + 55) % 97;

        if ($R1 * $R2) {
            return false;
        }

        return true;
    }

    /**
     * @param string $val
     * @return int
     */
    private function crossSum($val)
    {
        $val = (string)$val;
        $sum = 0;
        for ($i = 0; $i < strlen($val); $i++) {
            $sum += (int)$val[$i];
        }

        return $sum;
    }

    /**
     * @param int $val
     * @return bool
     */
    private function isEven($val)
    {
        return ($val / 2 == floor($val / 2)) ? true : false;
    }
}

<?php

declare (strict_types=1);

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

    protected $alphabet = 'WABCDEFGHIJKLMNOPQRSTUV';

    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 8 && strlen($vatNumber) != 9) {
            return false;
        }

        return $this->validateIENew($vatNumber)
            || $this->validateIEOld($vatNumber);
    }

    /**
     * @param string $vatNumber
     *
     * @return bool
     */
    private function validateIEOld(string $vatNumber): bool
    {
        $transform = ['0', substr($vatNumber, 2, 5), $vatNumber[0], $vatNumber[7]];
        $vat_id = join('', $transform);

        return $this->validateIENew($vat_id);
    }

    /**
     * @param string $vatNumber
     *
     * @return bool
     */
    private function validateIENew(string $vatNumber): bool
    {
        $checksum = strtoupper(substr($vatNumber, 7, 1));
        $checkNumber = substr($vatNumber, 0, 8);
        $checkVal = 0;

        for ($i = 2; $i <= 8; $i++) {
            $checkVal += (int)$checkNumber[8 - $i] * $i;
        }

        if (strlen($vatNumber) == 9) {
            $checkVal += (9 * strpos($this->alphabet, $vatNumber[8]));
        }

        $checkVal = ($checkVal % 23);

        if ($checkVal == 0) {
            return $checksum == 'W';
        }

        $checkChar = 'A';
        for ($i = $checkVal - 1; $i > 0; $i--) {
            $checkChar++;
        }

        return $checkChar == $checksum;
    }
}

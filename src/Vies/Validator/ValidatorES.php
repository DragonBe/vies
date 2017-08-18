<?php
/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

/**
 * Class ValidatorES
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1, C9 Alphanumeric from 0 to 9 or A to Z
 *      C2 .. C8 Numeric from 0 to 9
 *
 * Rules:
 * C1
 *      IF C9 Alphabetic then:
 *          C1 = A, B, C, D, E, F, G, H, N, P, Q, R, S, W
 *      IF C9 Numeric then:
 *          C1 = A, B, C, D, E, F, G, H, J, U, V
 *
 * C9
 *      IF C9 Alphabetic and C1 = A, B, C, D, E, F, G, H, N, P, Q, R, S, W then
 *          S1 = C3 + C5 +C7
 *          S2 = D2 + D4 + D6 + D8, where Di = int(Ci/5) + (2 * Ci)modulo10
 *          R = 10 – (S1 + S2) modulo 10
 *          C9 = Check Character(R)
 *          Check Character: 1-A, 2-B, 3-C, 4-D, 5-E, 6-F, 7-G, 8-H, 9-I, 10-J
 *
 *      If C9 numeric
 *          S1 = C3 + C5 + C7
 *          S2 = D2 + D4 + D6 + D8, where Di = int(Ci/5) + (2 * Ci)modulo10
 *          R = 10 – (S1 + S2) modulo 1
 *          C9 = (R)modulo10
 */
class ValidatorES extends ValidatorAbstract
{
    /**
     * Allowed C1 if C9 is Alphabetic
     *
     * @var array
     */
    protected $allowedC1Alphabetic = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'N', 'P', 'Q', 'R', 'S', 'W'];

    /**
     * Allowed C1 if C9 is Numeric
     *
     * @var array
     */
    protected $allowedC1Numeric = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'U', 'V'];

    /**
     * Check Character: 1-A, 2-B, 3-C, 4-D, 5-E, 6-F, 7-G, 8-H, 9-I, 10-J
     *
     * @var array
     */
    protected $checkCharacter = [
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
        5 => 'E',
        6 => 'F',
        7 => 'G',
        8 => 'H',
        9 => 'I',
        10 => 'J'
    ];

    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        if (! is_numeric(substr($vatNumber, 1, 6))) {
            return false;
        }

        $checksum = $vatNumber[8];
        $fieldC1 = $vatNumber[0];

        if (ctype_alpha($checksum) && in_array($fieldC1, $this->allowedC1Alphabetic)) {
            // Juridical entities other than national ones
            $checkval = $this->validateJuridical($vatNumber);
        } elseif (ctype_digit($checksum) && in_array($fieldC1, $this->allowedC1Numeric)) {
            // National juridical entities
            $checkval = $this->validateNational($vatNumber);
        } else {
            return false;
        }

        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }

    /**
     * @param $vatNumber
     * @return string
     */
    private function validateJuridical($vatNumber)
    {
        $checkval = 0;

        for ($i = 2; $i <= 8; $i++) {
            $checkval += $this->crossSum((int)$vatNumber[9 - $i] * ($this->isEven($i) ? 2 : 1));
        }

        $checkval = 10 - ($checkval % 10);
        return $this->checkCharacter[$checkval];
    }

    /**
     * @param $vatNumber
     * @return string
     */
    private function validateNational($vatNumber)
    {
        $checkval = 0;

        for ($i = 2; $i <= 8; $i++) {
            $checkval += $this->crossSum((int)$vatNumber[9 - $i] * ($this->isEven($i) ? 2 : 1));
        }

        $checkval = 10 - ($checkval % 10);
        return $checkval % 10;
    }
}

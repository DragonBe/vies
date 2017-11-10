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
 * Class ValidatorDK
 * @package DragonBe\Vies\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 *
 * Range:
 *      C1 > 0
 *
 * Rules:
 * R
 *      R = (2*C1 + 7*C2 + 6*C3 + 5*C4 + 4*C5 + 3*C6 + 2*C7 + C8)
 *
 *      R is divisible by 11
 */
class ValidatorDK extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }

        $checksum = $this->sumWeights([2, 7, 6, 5, 4, 3, 2, 1], $vatNumber);

        return ($checksum % 11) === 0;
    }
}

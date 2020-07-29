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
 * Class ValidatorHR
 * @package DragonBe\Vies\Validator
 */
class ValidatorHR extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }

        if (! ctype_digit($vatNumber)) {
            return false;
        }

        $product = 10;

        for ($i = 0; $i < 10; $i++) {
            $sum = ($vatNumber[$i] + $product) % 10;
            $sum = ($sum == 0) ? 10 : $sum;
            $product = (2 * $sum) % 11;
        }

        return ($product + (int) $vatNumber[10]) % 10 == 1;
    }
}

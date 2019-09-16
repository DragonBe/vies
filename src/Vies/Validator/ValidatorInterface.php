<?php

declare (strict_types=1);

/**
 * \DragonBe\Vies
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace DragonBe\Vies\Validator;

interface ValidatorInterface
{
    /**
     * Prepares the given VAT number for the validation.
     *
     * @param string $vatNumber
     *
     * @return string
     */
    public static function sanitize(string $vatNumber): string;

    /**
     * @param string $vatNumber
     *
     * @return bool
     */
    public function validate(string $vatNumber): bool;
}

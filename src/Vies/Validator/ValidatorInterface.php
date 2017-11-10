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
     * @param string $vatNumber
     *
     * @return bool
     */
    public function validate(string $vatNumber): bool;
}

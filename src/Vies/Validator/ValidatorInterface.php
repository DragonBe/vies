<?php
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
     * @return bool
     */
    public function validate($vatNumber): bool;
}

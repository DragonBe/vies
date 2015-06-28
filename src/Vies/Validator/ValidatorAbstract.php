<?php

namespace DragonBe\Vies\Validator;

abstract class ValidatorAbstract implements ValidatorInterface
{
    /**
     * @param string $val
     * @return int
     */
    protected function crossSum($val)
    {
        $val = (string)$val;
        $sum = 0;
        $count = strlen($val);
        for ($i = 0; $i < $count; $i++) {
            $sum += (int)$val[$i];
        }

        return $sum;
    }

    /**
     * @param int $val
     * @return bool
     */
    protected function isEven($val)
    {
        return ($val / 2 == floor($val / 2)) ? true : false;
    }

    /**
     * @param array $weights
     * @param int $start
     * @param string $vatNumber
     * @return int
     */
    protected function sumWeights(array $weights, $vatNumber, $start = 0)
    {
        $checkval = 0;
        $count = count($weights);
        for ($i = $start; $i < $count; $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }

        return $checkval;
    }
}

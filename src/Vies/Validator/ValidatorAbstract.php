<?php

declare (strict_types=1);


namespace DragonBe\Vies\Validator;

abstract class ValidatorAbstract implements ValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    abstract public function validate(string $vatNumber): bool;

    /**
     * @param int $val
     *
     * @return int
     */
    protected function crossSum(int $val): int
    {
        $reducer = function (int $sum, string $char): int {
            return  $sum + (int) $char;
        };

        return array_reduce(str_split((string) $val), $reducer, 0);
    }

    /**
     * @param int $val
     *
     * @return bool
     */
    protected function isEven(int $val): bool
    {
        return $val / 2 == floor($val / 2);
    }

    /**
     * @param array $weights
     * @param string $vatNumber
     * @param int $start
     *
     * @return int
     */
    protected function sumWeights(array $weights, string $vatNumber, int $start = 0): int
    {
        $checkval = 0;
        $count = count($weights);
        for ($i = $start; $i < $count; $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }

        return $checkval;
    }
}

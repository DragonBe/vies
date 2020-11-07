<?php

declare (strict_types=1);


namespace DragonBe\Vies\Validator;

abstract class ValidatorAbstract implements ValidatorInterface
{
    const DEFAULT_WEIGHT_START = 0;
    const DEFAULT_MODULO = 11;
    const DEFAULT_VAT_POSITION = 9;

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
        return $val % 2 === 0;
    }

    /**
     * @param array $weights
     * @param string $vatNumber
     * @param int $start
     *
     * @return int
     */
    protected function sumWeights(array $weights, string $vatNumber, int $start = self::DEFAULT_WEIGHT_START): int
    {
        $checkVal = 0;
        $count = count($weights);
        for ($i = $start; $i < $count; $i++) {
            $checkVal += (int)$vatNumber[$i] * $weights[$i];
        }

        return $checkVal;
    }

    /**
     * A method to calculate the value to check the
     * checksum of a VAT number
     *
     * @param $vatNumber
     * @param $weights
     * @param int $restModulo
     * @param int $vatNumberPosition
     * @return bool
     */
    protected function checkValue(
        $vatNumber,
        $weights,
        $restModulo = self::DEFAULT_MODULO,
        $vatNumberPosition = self::DEFAULT_VAT_POSITION,
        $weightStart = self::DEFAULT_WEIGHT_START
    ) {
        $checkVal = $this->sumWeights($weights, $vatNumber);
        if ($checkVal % 11 == 10) {
            $weights = [3, 4, 5, 6, 7, 8, 9, 10];
            $checkVal = $this->sumWeights($weights, $vatNumber, $weightStart);

            $checkVal = ($checkVal % 11) == 10 ? 0 : ($checkVal % 11);
        } else {
            $checkVal = $checkVal % $restModulo;
        }
        return $checkVal == (int) $vatNumber[$vatNumberPosition];
    }
}

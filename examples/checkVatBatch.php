<?php

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$vies = new Vies();

if (false === $vies->getHeartBeat()->isAlive()) {

    echo 'Back-end VIES service is not available at the moment, please try again later.' . PHP_EOL;

} else {

    $splQueue = new SplQueue();

    foreach (vatinProvider() as $vatin) {
        $splQueue->enqueue($vatin);
    }

    foreach ($splQueue as $item) {

        $vatin = $splQueue->dequeue();

        echo 'Validating ' . $vatin . '... ';

        $countryCode = substr($vatin, 0, 2);
        $vatNumber   = substr($vatin, 2);
        $vatNumber   = $vies->filterVat($vatNumber);

        try {
            $result = $vies->validateVat($countryCode, $vatNumber);     // Validation routine worked as expected.
            echo ($result->isValid()) ? 'VALID' : 'INVALID';
        } catch (ViesServiceException $e) {                             // Recoverable exception. There is probably a temporary problem
            echo $e->getMessage();                                      // with back-end VIES service. Try again. Add VATIN back to queue.
            $splQueue->enqueue($vatin);
        } catch (ViesException $e) {                                    // Unrecoverable exception. Invalid country code etc.
            echo $e->getMessage();                                      // Do not try again.
        }

        echo PHP_EOL;
    }
}


/**
 * Load an array of VATINs (country code + VAT number)
 *
 * Source:
 * http://www.braemoor.co.uk/software/vattest.php
 *
 * @return array
 */
function vatinProvider(): array
{
    $filename = realpath(__DIR__ . '/vatins.php');

    if (is_readable($filename)) {
        $ret = include $filename;
    } else {
        $ret = [];
    }

    return $ret;
}

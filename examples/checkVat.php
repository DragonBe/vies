<?php

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$vies = new Vies();

if (false === $vies->getHeartBeat()->isAlive()) {

    echo 'Back-end VIES service is not available at the moment, please try again later.' . PHP_EOL;

} else {

    $vatNumberProvider = [
        [
            'BE' => '0811231190',       // valid
            'HR' => '20649144807'
        ],
        [
            'BE' => '1234567890',       // invalid
            'ES' => '9999999999',
        ],
        [
            'AA' => '1234567890',       // invalid country code
            'NO' => '1234567890'
        ],
    ];

    foreach ($vatNumberProvider as $vatNumbers) {

        foreach ($vatNumbers as $countryCode => $vatNumber) {

            echo 'Validating ' . $countryCode . $vatNumber . '... ';

            try {
                $result = $vies->validateVat($countryCode, $vatNumber); // - Validation routine worked as expected.
                echo ($result->isValid()) ? 'Valid' : 'Invalid';        //
            } catch (ViesServiceException $e) {                         // - Recoverable exception.
                echo $e->getMessage();                                  //   There is probably a temporary problem with back-end VIES service.
            } catch (ViesException $e) {                                // - Unrecoverable exception.
                echo $e->getMessage();                                  //   Invalid country code etc.
            }

            echo PHP_EOL;

        }
    }
}

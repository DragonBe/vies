<?php

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$vies = new Vies();

if (false === $vies->getHeartBeat()->isAlive()) {

    echo 'Back-end VIES service is not available at the moment, please try again later.' . PHP_EOL;

} else {
    $vatDataProvider = [
        [
            'countryCode' => 'BE',                    // Country code
            'vatId' => '0811231190',                  // VAT ID
            'traderName' => 'In2it',                  // Trader name
            'traderCompanyType' => 'VOF',             // Trader company type
            'traderStreet' => 'Battelsesteenweg 134', // Trader street address
            'traderPostcode' => '2800',               // Trader postcode
            'traderCity' => 'Mechelen'                // Trader city
        ],
        [
            'countryCode' => 'HR',                    // Country code
            'vatId' => '20649144807',                 // VAT ID
            'traderName' => 'Aning Usluge',           // Trader name
            'traderCompanyType' => 'DOO',             // Trader company type
            'traderStreet' => 'Zeleni Trg 4',         // Trader street address
            'traderPostcode' => '10000',              // Trader postcode
            'traderCity' => 'Zagreb'                  // Trader city
        ],
    ];

    foreach ($vatDataProvider as $trader) {
        try {
            $response = $vies->validateVat(
                $trader['countryCode'],
                $trader['vatId'],
                $trader['countryCode'], // duplicate for requester VAT country code
                $trader['vatId'],       // duplicate for requester VAT ID
                $trader['traderName'],
                $trader['traderCompanyType'],
                $trader['traderStreet'],
                $trader['traderPostcode'],
                $trader['traderCity']
            );

            echo sprintf('Company %s with VAT ID %s%s is %s (validation ID: %s)',
                $response->getName(),
                $response->getCountryCode(),
                $response->getVatNumber(),
                $response->isValid() ? 'Valid' : 'Not valid',
                $response->getIdentifier()
            ) . PHP_EOL;
            echo sprintf('Company name: %s (%s)',
                $response->getName(),
                '' === $response->getNameMatch() ? 'No match returned' : $response->getNameMatch()
            ) . PHP_EOL;
            echo sprintf('Company address: %s (%s)',
                $response->getAddress(),
                '' === $response->getStreetMatch() ? 'No match returned' : $response->getStreetMatch()
            ) . PHP_EOL;
        } catch (ViesException $viesException) {
            echo 'An error occurred: ' . $viesException->getMessage();
        }
    }
}

<?php
/**
 * Here's a practical example how you can use this functionality
 * easily in your applications.
 *
 * Here's a basis of railway companies within the EU. Unfortunately
 * they don't all publish their VAT identification number (or I don't
 * recognise it as such) clearly on their website.
 *
 */
use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$vies = new Vies();

if (false === $vies->getHeartBeat()->isAlive()) {

    echo 'Back-end VIES service is not available at the moment, please try again later.' . PHP_EOL;
    exit(1);

}

$companies = [
    [
        'country_code' => 'BE',
        'vat_id' => '0203430576',
        'trader_name' => 'B-Rail',
        'trader_company_type' => 'NV',
        'trader_street' => 'Frankrijkstraat 65',
        'trader_postcode' => '1060',
        'trader_city' => 'Sint-Gillis',
    ],
    [
        'country_code' => 'NL',
        'vat_id' => '803979988B01',
        'trader_name' => 'NS Groep',
        'trader_company_type' => 'NV',
        'trader_street' => 'Laan van Puntenburg 100',
        'trader_postcode' => '3511ER',
        'trader_city' => 'Utrecht',
    ],
    [
        'country_code' => 'DE',
        'vat_id' => '811569869',
        'trader_name' => 'Deutsche Bahn',
        'trader_company_type' => 'AG',
        'trader_street' => 'Potsdamer Platz 2',
        'trader_postcode' => '10785',
        'trader_city' => 'Berlin',
    ],
    [
        'country_code' => 'FR',
        'vat_id' => '35552049447',
        'trader_name' => 'Societe Nationale des Chemins de fer Francais',
        'trader_company_type' => '',
        'trader_street' => '2, place aux Étoiles',
        'trader_postcode' => '93200',
        'trader_city' => 'Saint-Denis',
    ],
    [
        'country_code' => 'ES',
        'vat_id' => 'A86868189',
        'trader_name' => 'Renfe Viajeros',
        'trader_company_type' => 'SA',
        'trader_street' => 'Avda. Ciudad de Barcelona, 8',
        'trader_postcode' => '28007',
        'trader_city' => 'Madrid',
    ],
    [
        'country_code' => 'PT',
        'vat_id' => '500498601',
        'trader_name' => 'Comboios de Portugal',
        'trader_company_type' => 'SA',
        'trader_street' => 'Calçada do Duque, n.º 20',
        'trader_postcode' => '1249-109',
        'trader_city' => 'Lisboa',
    ],
    [
        'country_code' => 'IT',
        'vat_id' => '04983351000',
        'trader_name' => 'POL-RAIL',
        'trader_company_type' => 'SRL',
        'trader_street' => 'Viale dello Scalo San Lorenzo, 16',
        'trader_postcode' => '00185',
        'trader_city' => 'Roma',
    ],
    [
        'country_code' => 'AT',
        'vat_id' => 'U58044244',
        'trader_name' => 'Rail Cargo Austria',
        'trader_company_type' => 'AG',
        'trader_street' => 'Hauptbahnhof 2',
        'trader_postcode' => '1100',
        'trader_city' => 'Vienna',
    ],
    [
        'country_code' => 'EL',
        'vat_id' => '999645865',
        'trader_name' => 'Trainose',
        'trader_company_type' => 'SA',
        'trader_street' => '1-3 Karolou',
        'trader_postcode' => '10437',
        'trader_city' => 'Athens',
    ],
    [
        'country_code' => 'PL',
        'vat_id' => '1132316427',
        'trader_name' => 'PKP POLSKIE LINIE KOLEJOWE SPÓŁKA AKCYJNA',
        'trader_company_type' => '',
        'trader_street' => 'TARGOWA 74',
        'trader_postcode' => '03-734',
        'trader_city' => 'WARSZAWA',
    ],
    [
        'country_code' => 'LV',
        'vat_id' => '40003032065',
        'trader_name' => 'Valsts akciju sabiedrība "Latvijas dzelzceļš"',
        'trader_company_type' => '',
        'trader_street' => 'Gogoļa iela 3,',
        'trader_postcode' => 'LV-1050',
        'trader_city' => 'Riga',
    ],
];

foreach ($companies as $company) {
    try {
        $vatResult = $vies->validateVat(
            $company['country_code'],        // Trader country code
            $company['vat_id'],              // Trader VAT ID
            'BE',                            // Requester country code
            '0811231190',                    // Requester VAT ID
            $company['trader_name'],         // Trader name
            $company['trader_company_type'], // Trader company type
            $company['trader_street'],       // Trader street address
            $company['trader_postcode'],     // Trader postcode
            $company['trader_city']          // Trader city
        );
    } catch (ViesException $viesException) {
        echo 'Cannot process VAT validation: ' . $viesException->getMessage();
        continue;
    } catch (ViesServiceException $viesServiceException) {
        echo 'Cannot process VAT validation: ' . $viesServiceException->getMessage();
        continue;
    }

    echo ($vatResult->isValid() ? 'Valid' : 'Not valid') . PHP_EOL;
    echo 'Identifier: ' . $vatResult->getIdentifier() . PHP_EOL;
    echo 'Date and time: ' . $vatResult->getRequestDate()->format('d/m/Y H:i') . PHP_EOL;
    echo 'Company name: ' . $vatResult->getName() . PHP_EOL;
    echo 'Company address: ' . $vatResult->getAddress() . PHP_EOL;

    echo 'Trader name match: ' . $vatResult->getNameMatch() . PHP_EOL;
    echo 'Trader company type match: ' . $vatResult->getCompanyTypeMatch() . PHP_EOL;
    echo 'Trader street match: ' . $vatResult->getStreetMatch() . PHP_EOL;
    echo 'Trader postcode match: ' . $vatResult->getPostcodeMatch() . PHP_EOL;
    echo 'Trader city match: ' . $vatResult->getCityMatch() . PHP_EOL;
    echo PHP_EOL;
}

exit (0);

# VIES

Component using the European Commission (EC) VAT Information Exchange System (VIES) to verify and validate VAT registration numbers in the EU, using PHP and Composer.

The `Vies` class provides functionality to make a SOAP call to VIES and returns an object `CheckVatResponse` containing the following information:

- Country code (string): a 2-character notation of the country code
- VAT registration number (string): contains the complete registration number without the country code
- Date of request (DateTime): the date when the request was made
- Valid (boolean): flag indicating the registration number was valid (TRUE) or not (FALSE)
- Name (string): registered company name (if provided by EC member state)
- Address (string): registered company address (if provided by EC member state)

Stated on the European Commission website:
> To make an intra-Community supply without charging VAT, you **should ensure** that the person to whom you are supplying the goods is a taxable person in another Member State, and that the goods in question have left, or will leave your Member State to another MS. VAT-number should also be in the invoice.

More information at http://ec.europa.eu/taxation_customs/vies/faqvies.do#item16

[![Travis-CI Build status](https://api.travis-ci.org/DragonBe/vies.png)](https://travis-ci.org/DragonBe/vies) [![SensioLabs Insights](https://insight.sensiolabs.com/projects/21b019ce-dd1d-4d16-8b74-880b9ee5e795/mini.png)](https://insight.sensiolabs.com/projects/21b019ce-dd1d-4d16-8b74-880b9ee5e795) [![CodeClimate Analysis](https://d3s6mut3hikguw.cloudfront.net/github/DragonBe/vies/badges/gpa.svg)](https://codeclimate.com/github/DragonBe/vies) [![CodeClimate CodeCoverage](https://d3s6mut3hikguw.cloudfront.net/github/DragonBe/vies/badges/coverage.svg)](https://codeclimate.com/github/DragonBe/vies) [![CodeShip CI](https://codeship.com/projects/304718e0-8d01-0132-6960-7671d147512f/status?branch=master)](https://codeship.com/projects/60548) [![Build Status](https://status.continuousphp.com/git-hub/DragonBe/vies?token=e8721fe8-0619-4789-9691-33021709f42f)](https://continuousphp.com/git-hub/DragonBe/vies)

## GDPR and privacy regulation of VAT within the EU

On May 25, 2018 the General Data Protection Regulation or GDPR becomes law within all 28 European Member States. Is this VIES service package going to be compliant with GDPR? 

In short: yes. 

The longer answer is that this VIES package only interacts with the service for VAT ID verification provided by the European Commission. VAT validation is mandatory in European countries and therefor this service is allowed as lawfulness and legal basis. Please read more about this in [European DPO-3816.1](http://ec.europa.eu/dpo-register/details.htm?id=40647). This service does not store any data itself or collects more information than what's strictly required by law and provided by the EC VIES service.

When you have implemented this service package in your own project, be sure that you're making sure you're just store the VAT ID, the timestamp of validation, the result of validation and optionally the given validation ID provided by the EC VIES service.

## Requirements

- Minimum PHP version: 7.1
- Recommended PHP version: 7.2
- Extension: soap
- Extension: pcntl

Please read the [release notes](https://github.com/DragonBe/vies/releases) for details.

## Installation

This project is on [Packagist](https://packagist.org/packages/dragonbe/vies)!

To install the latest stable version use `composer require dragonbe/vies`.

To install specifically a version (e.g. 2.0.4), just add it to the command above, for example `composer require dragonbe/vies:2.0.4`

## Usage

```php
<?php

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$vies = new Vies();

if (false === $vies->getHeartBeat()->isAlive()) {

    echo 'Service is not available at the moment, please try again later.' . PHP_EOL;

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
```

## Community involvement

Here's a list of products or projects that have included this VIES package

- [Symfony bundle](https://github.com/MyOnlineStore/ViesBundle) by [MyOnlineStore](https://www.myonlinestore.com)

If you have a product or a project that's using this package and you want some attribution for your work, send me an [email](mailto://dragonbe+github@gmail.com) or ping me on [Twitter](https://www.twitter.com/DragonBe) or [Facebook](https://www.facebook.com/dragonbe).

## Licence

DragonBe\Vies is released under the MIT Licence. See the bundled [LICENCE](LICENCE) file for details.

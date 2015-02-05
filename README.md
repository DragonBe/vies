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

[![Travis-CI Build status](https://api.travis-ci.org/DragonBe/vies.png)](https://travis-ci.org/DragonBe/vies) [![SensioLabs Insights](https://insight.sensiolabs.com/projects/21b019ce-dd1d-4d16-8b74-880b9ee5e795/mini.png)](https://insight.sensiolabs.com/projects/21b019ce-dd1d-4d16-8b74-880b9ee5e795) [![CodeClimate Analysis](https://d3s6mut3hikguw.cloudfront.net/github/DragonBe/vies/badges/gpa.svg)](https://codeclimate.com/github/DragonBe/vies) [![CodeClimate CodeCoverage](https://d3s6mut3hikguw.cloudfront.net/github/DragonBe/vies/badges/coverage.svg)](https://codeclimate.com/github/DragonBe/vies) [![CodeShip CI](https://codeship.com/projects/304718e0-8d01-0132-6960-7671d147512f/status?branch=master)](https://codeship.com/projects/60548)

# Installation

This project is on [Packagist](https://packagist.org/packages/dragonbe/vies)!

To install the latest stable version use `composer require dragonbe/vies`.

To install the a specific version (e.g. 1.0.0), just add to your `composer.json` the following:

```json
"require": {
    "dragonbe/vies": "1.0.3"
}
```


# Usage

```php
<?php
use \DragonBe\Vies\Vies;
use \DragonBe\Vies\ViesException;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$vies = new Vies();
if (false === $vies->getHeartBeat()->isAlive()) {

    echo 'Service is not available at the moment, please try again later.' . PHP_EOL;

} else {

//  Using my own VAT to verify, should be valid
    $result = $vies->validateVat('BE', '0811231190');
    echo ($result->isValid() ? 'VALID' : 'INVALID') . ' VAT number' . PHP_EOL;

//  Using bogus VAT to verify, should be invalid
    $result = $vies->validateVat('BE', '1234567890');
    echo ($result->isValid() ? 'VALID' : 'INVALID') . ' VAT number' . PHP_EOL;

//  Catching exceptions for invalid country codes
    try {
        $result = $vies->validateVat('AA', '1234567890');
    } catch (ViesException $exception) {
        echo 'Invalid arguments provided' . PHP_EOL;
    }
}
```

# Roadmap

- Pre-validation of VAT numbers **before** hitting the service for better performance

# Licence

DragonBe\Vies is released under the MIT Licence. See the bundled [LICENSE](LICENSE) file for details.

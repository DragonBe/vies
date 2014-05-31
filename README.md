# VIES

Component using the European Commission (EC) VAT Information Exchange System (VIES) to verify and validate VAT registration numbers in the EU, using PHP and Composer.

The `Vies` class provides functionality to make a SOAP call to VIES and returns an object `CheckVatResponse` containing the following information:
- Country code (string): a 2-character notation of the country code
- VAT registration number (string): contains the complete registration number without the country code
- Date of request (string): the date when the request was made
- Valid (boolean): flag indicating the registration number was valid (TRUE) or not (FALSE)

Stated on the European Commission website:
> To make an intra-Community supply without charging VAT, you **should ensure** that the person to whom you are supplying the goods is a taxable person in another Member State, and that the goods in question have left, or will leave your Member State to another MS. VAT-number should also be in the invoice.

More information at http://ec.europa.eu/taxation_customs/vies/faqvies.do#item16

[![Travis-CI Build status](https://api.travis-ci.org/DragonBe/vies.png)](https://travis-ci.org/DragonBe/vies) [![SensioLabs Insights](https://insight.sensiolabs.com/projects/21b019ce-dd1d-4d16-8b74-880b9ee5e795/mini.png)](https://insight.sensiolabs.com/projects/21b019ce-dd1d-4d16-8b74-880b9ee5e795)

# Installation

This project is on [Packagist](https://packagist.org/packages/dragonbe/vies)!

To install the latest from master, just add to your `composer.json` the following:

    "require-dev": {
        "dragonbe/vies": "dev-master"
    }

To install the a specific version (e.g. 1.0.0), just add to your `composer.json` the following:

    "require": {
        "dragonbe/vies": "1.0.0"
    }


# Usage

     <?php

     use \DragonBe\Vies\Vies;

     require_once dirname(__DIR__) . '/vendor/autoload.php';

     $vies = new Vies();

     // Using my own VAT to verify, should be valid
     $result = $vies->validateVat('BE', '0811231190');
     var_dump($result->isValid());

     // Using bogus VAT to verify, should be invalid
     $result = $vies->validateVat('BE', '1234567890');
     var_dump($result->isValid());

# Roadmap

- Add a heartbeat tool for checking if the VIES service is alive.

# Licence

DragonBe\Vies is released under the MIT Licence. See the bundled [LICENSE](LICENSE) file for details.

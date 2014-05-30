# VIES

This is a service provided by the European Union to validate VAT numbers of companies registered within the EU.

It either returns a successful validation with optional company name and registered address, or a simple false

More information at http://ec.europa.eu/taxation_customs/vies/faqvies.do#item16

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

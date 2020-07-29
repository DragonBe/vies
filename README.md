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

[![Travis-CI Build status](https://api.travis-ci.org/DragonBe/vies.png)](https://travis-ci.org/DragonBe/vies) [![SensioLabs Insights](https://insight.sensiolabs.com/projects/21b019ce-dd1d-4d16-8b74-880b9ee5e795/mini.png)](https://insight.sensiolabs.com/projects/21b019ce-dd1d-4d16-8b74-880b9ee5e795) [![CodeClimate Analysis](https://d3s6mut3hikguw.cloudfront.net/github/DragonBe/vies/badges/gpa.svg)](https://codeclimate.com/github/DragonBe/vies) [![CodeClimate CodeCoverage](https://d3s6mut3hikguw.cloudfront.net/github/DragonBe/vies/badges/coverage.svg)](https://codeclimate.com/github/DragonBe/vies) [![CodeShip CI](https://codeship.com/projects/304718e0-8d01-0132-6960-7671d147512f/status?branch=master)](https://codeship.com/projects/60548) [![Build Status](https://status.continuousphp.com/git-hub/DragonBe/vies?token=e8721fe8-0619-4789-9691-33021709f42f)](https://continuousphp.com/git-hub/DragonBe/vies) [![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=dragonbe-vies&metric=alert_status)](https://sonarcloud.io/dashboard?id=dragonbe-vies)

## GDPR and privacy regulation of VAT within the EU

On May 25, 2018 the General Data Protection Regulation or GDPR becomes law within all 28 European Member States. Is this VIES service package going to be compliant with GDPR? 

In short: yes. 

The longer answer is that this VIES package only interacts with the service for VAT ID verification provided by the European Commission. VAT validation is mandatory in European countries and therefor this service is allowed as lawfulness and legal basis. Please read more about this in [European DPO-3816.1](http://ec.europa.eu/dpo-register/details.htm?id=40647). This service does not store any data itself or collects more information than what's strictly required by law and provided by the EC VIES service.

When you have implemented this service package in your own project, be sure that you're making sure you're just store the VAT ID, the timestamp of validation, the result of validation and optionally the given validation ID provided by the EC VIES service.

## Requirements

- Minimum PHP version: 7.1
- Recommended PHP version: 7.4
- Extension: soap
- Extension: pcntl
- Extension: ctype

Please read the [release notes](https://github.com/DragonBe/vies/releases) for details.

## Installation

This project is on [Packagist](https://packagist.org/packages/dragonbe/vies)!

To install the latest stable version use `composer require dragonbe/vies`.

To install specifically a version (e.g. 2.0.4), just add it to the command above, for example `composer require dragonbe/vies:2.0.4`

## Usage

Here's a usage example you can immediately execute on the command line (or in cron, worker or whatever) as this will most likely be your most common usecase.

### 1. Setting it up

```php
<?php

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$vies = new Vies();
```

### 2. See if the VIES service is alive

```php
if (false === $vies->getHeartBeat()->isAlive()) {

    echo 'Service is not available at the moment, please try again later.' . PHP_EOL;
    exit(1);
}
```

#### If using a proxy, you can now use the following approach

```php
$vies = new Vies();
$options = [
    'proxy_host' => '127.0.0.1',
    'proxy_port' => '8888',
];
$vies->setOptions($options);

$heartBeat = new \DragonBe\Vies\HeartBeat('tcp://' . $options['proxy_host'], $options['proxy_port']);
$vies->setHeartBeat($heartBeat);

$isAlive = $vies->getHeartBeat()->isAlive();
```

### 3. Validate VAT

Now that we know the service is alive, we can start validating VAT ID's

#### 3.1. Simple usage

```php
$vatResult = $vies->validateVat(
    'BE',           // Trader country code 
    '0203430576',   // Trader VAT ID
    'BE',           // Requester country code 
    '0811231190'    // Requester VAT ID
);
```

#### 3.2. Advanced usage

```php
$vatResult = $vies->validateVat(
    'BE',                 // Trader country code 
    '0203430576',         // Trader VAT ID
    'BE',                 // Requester country code 
    '0811231190'          // Requester VAT ID
    'B-Rail',             // Trader name
    'NV',                 // Trader company type
    'Frankrijkstraat 65', // Trader street address
    '1060',               // Trader postcode
    'Sint-Gillis'         // Trader city
);
```

#### 3.3. Result methods

##### 3.3.1. Is the VAT ID valid?

The most important functionality is to see if the VAT ID is valid

```php
echo ($vatResult->isValid() ? 'Valid' : 'Not valid') . PHP_EOL;

// Result: Valid
```

##### 3.3.2. Retrieve the VAT validation identifier

```php
echo 'Identifier: ' . $vatResult->getIdentifier() . PHP_EOL;

// Result: Identifier: WAPIAAAAWaXGj4Ra
```

##### 3.3.3. Retrieve validation date

**Note: VIES service returns date and timezone, but no time**

```php
echo 'Date and time: ' . $vatResult->getRequestDate()->format('r') . PHP_EOL;

// Result: Date and time: Sat, 31 Aug 2019 00:00:00 +0200
```

##### 3.3.4. Retrieve official trader name (not always available)

```php
echo 'Company name: ' . $vatResult->getName() . PHP_EOL;

// Result: Company name: NV OR NATIONALE MAATSCHAPPIJ DER BELGISCHE SPOORWEGEN
```

##### 3.3.5. Retrieve official trader street (not always available)

```php
echo 'Company address: ' . $vatResult->getAddress() . PHP_EOL;

// Result: Company address: FRANKRIJKSTRAAT 56
           1060 SINT-GILLIS (BIJ-BRUSSEL)
```

##### 3.3.6. Retrieve a match for trader name (not always available)

```php
echo 'Trader name match: ' . $vatResult->getNameMatch() . PHP_EOL;

// Result: Trader name match:
```

##### 3.3.7. Retrieve a match for trader company type (not always available)

```php
echo 'Trader company type match: ' . $vatResult->getCompanyTypeMatch() . PHP_EOL;

// Result: Trader company type match:
```

##### 3.3.8. Retrieve a match for trader street (not always available)

```php
echo 'Trader street match: ' . $vatResult->getStreetMatch() . PHP_EOL;

// Result: Trader street match:
```

##### 3.3.9. Retrieve a match for trader postcode (not always available)

```php
echo 'Trader postcode match: ' . $vatResult->getPostcodeMatch() . PHP_EOL;

// Result: Trader postcode match:
```

##### 3.3.10. Retrieve a match for trader city (not always available)

```php
echo 'Trader city match: ' . $vatResult->getCityMatch() . PHP_EOL;

// Result: Trader city match:
```

### Example code

```php
<?php

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$vies = new Vies();

$company = [
   'country_code' => 'BE',
   'vat_id' => '0203430576',
   'trader_name' => 'B-Rail',
   'trader_company_type' => 'NV',
   'trader_street' => 'Frankrijkstraat 65',
   'trader_postcode' => '1060',
   'trader_city' => 'Sint-Gillis',
];

try {
    $vatResult = $vies->validateVat(
        $company['country_code'],        // Trader country code
        $company['vat_id'],              // Trader VAT ID
        'BE',                            // Requester country code (your country code)
        '0811231190',                    // Requester VAT ID (your VAT ID)
        $company['trader_name'],         // Trader name
        $company['trader_company_type'], // Trader company type
        $company['trader_street'],       // Trader street address
        $company['trader_postcode'],     // Trader postcode
        $company['trader_city']          // Trader city
    );
} catch (ViesException $viesException) {
    echo 'Cannot process VAT validation: ' . $viesException->getMessage();
    exit (2);
} catch (ViesServiceException $viesServiceException) {
    echo 'Cannot process VAT validation: ' . $viesServiceException->getMessage();
    exit (2);
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
```

When you run this, you will get the following result:

```
Valid
Identifier: WAPIAAAAWaYR0O8D
Date and time: 21/10/2018 02:00
Company name: NV OR NATIONALE MAATSCHAPPIJ DER BELGISCHE SPOORWEGEN
Company address: FRANKRIJKSTRAAT 56
1060 SINT-GILLIS (BIJ-BRUSSEL)
Trader name match:
Trader company type match:
Trader street match:
Trader postcode match:
Trader city match:

```

## Community involvement

Here's a list of products or projects that have included this VIES package

- [Symfony bundle](https://github.com/MyOnlineStore/ViesBundle) by [MyOnlineStore](https://www.myonlinestore.com)
- [sandwich/vies-bundle](https://packagist.org/packages/sandwich/vies-bundle)

If you have a product or a project that's using this package and you want some attribution for your work, send me an [email](mailto://dragonbe+github@gmail.com) or ping me on [Twitter](https://www.twitter.com/DragonBe) or [Facebook](https://www.facebook.com/dragonbe).

## Referenced on the web

- [Microsoft Dynamics GP - Dynamics GP real time EU tax registration number validation using VIES](http://timwappat.info/post/2013/08/22/Dynamics-GP-real-time-EU-tax-registration-number-validation-using-VIES)
- [Popular RIA law eu projects](https://libraries.io/search?keywords=RIA%2Claw%2Ceu)
- [PHP Code Examples - HotExamples.com](https://hotexamples.com/examples/dragonbe.vies/Vies/validateVat/php-vies-validatevat-method-examples.html)

## Clarification on exceptions

For Greece the [international country ISO code](https://www.iso.org/obp/ui/#iso:code:3166:GR) is **GR**, but for VAT IDN's they use the prefix **EL**. Thanks to [Johan Wilfer](https://github.com/johanwilfer) for [reporting this](https://github.com/DragonBe/vies/issues/57).

## Licence

DragonBe\Vies is released under the MIT Licence. See the bundled [LICENCE](LICENCE) file for details.

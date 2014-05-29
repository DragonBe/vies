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
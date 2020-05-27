<?php

namespace DragonBe;

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;

require_once __DIR__ . '/../../vendor/autoload.php';

$pdo = new \PDO('sqlite:' . __DIR__ . '/vatqueue.db');
$validationStmt = $pdo->prepare('SELECT `id`, `vat_id` FROM `vat_validation` WHERE `validated` = "" ORDER BY `id` DESC LIMIT 1');
$updateVatId = $pdo->prepare('UPDATE `vat_validation` SET `validated` = ?, `result` = ?, `reason` = ?, `name` = ?, `address` = ?, `identifier` = ? WHERE `id` = ?');

function debug($message = ''): void
{
    $debug = false;
    if ($debug) {
        echo '[DEBUG] >>> ' . $message . PHP_EOL;
    }
}

$vies = new Vies();
do {
    if (!$vies->getHeartBeat()->isAlive()) {
        debug('Service is down, sleeping for 30 minutes');
        sleep(1800); // sleep for 30 minutes
        continue;
    }
    $validationStmt->execute();

    $row = $validationStmt->fetch(\PDO::FETCH_ASSOC);
    if (false === $row) {
        debug('No new data found');
        sleep(60);
        continue;
    }
    $id = (int) $row['id'];
    $vatin = $row['vat_id'];
    $vatCountry = substr($vatin, 0, 2);
    $vatNumber = substr($vatin, 2);
    $vatNumber = $vies->filterVat($vatNumber);
    $reason = '';

    try {
        $result = $vies->validateVat($vatCountry, $vatNumber);
        debug('Result: ' . var_export($result, 1));
    } catch (ViesServiceException $viesServiceException) {
        // Connection was broken or other weird things went on, let's wait a few
        debug('ViesServiceException: ' . $viesServiceException->getMessage());
        sleep(60);
        continue;
    } catch (ViesException $viesException) {
        // Something went wrong with the validation, add to log and notify service operators
        debug('ViesException: ' . $viesException->getMessage());
        $reason = $viesException->getMessage();
    }

    $updateVatId->bindValue(1, $result->getRequestDate()->format('Y-m-d H:i:s O'));
    $updateVatId->bindValue(2, $result->isValid() ? 'valid' : 'invalid');
    $updateVatId->bindValue(3, $reason);
    $updateVatId->bindValue(4, $result->getName());
    $updateVatId->bindValue(5, $result->getAddress());
    $updateVatId->bindValue(6, $result->getIdentifier());
    $updateVatId->bindValue(7, $id);

    $updateVatId->execute();

} while (true);

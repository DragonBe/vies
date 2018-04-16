<?php

namespace DragonBe;

$pdo = new \PDO('sqlite:' . __DIR__ . '/vatqueue.db');
$isAlreadyValidatedStmt = $pdo->prepare('SELECT `id` FROM `vat_validation` WHERE `vat_id` = ?');
$addToQueueStmt = $pdo->prepare('INSERT INTO `vat_validation` (`vat_id`) VALUES (?)');
$updateQueueStmt = $pdo->prepare('UPDATE `vat_validation` SET `validated` = "" WHERE `id` = ?');

if ([] !== $_GET && array_key_exists('vatid', $_GET)) {
    $vatId = filter_var($_GET['vatid'], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
    $vatId = str_replace(['.', '-', ' ', '_'], '', $vatId);

    $isAlreadyValidatedStmt->bindValue(1, $vatId);
    $isAlreadyValidatedStmt->execute();

    $result = $isAlreadyValidatedStmt->fetchColumn(0);
    if (false === $result) {
        $addToQueueStmt->bindValue(1, $vatId);
        $addToQueueStmt->execute();
    } else {
        $updateQueueStmt->bindValue(1, $result);
        $updateQueueStmt->execute();
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
}

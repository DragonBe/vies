<?php

namespace DragonBe;

$vatIdList = [];
$pdo = new \PDO('sqlite:' . __DIR__ . '/vatqueue.db');
$listStmt = $pdo->query('SELECT * FROM `vat_validation` ORDER BY `id` DESC');

$vatIdList = $listStmt->fetchAll(\PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Listing VAT ID's</title>
    </head>
    <body>
        <h1>Listing VAT ID's</h1>

        <p><a href="/queue.php">Add new VAT ID</a></p>

        <table>
            <tr>
                <th>VAT ID</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php foreach ($vatIdList as $vatIdEntry): ?>
                <tr>
                    <td><?php echo $vatIdEntry['vat_id'] ?></td>
                    <td><?php $date = new \DateTime($vatIdEntry['validated']); echo $date->format('d-m-Y') ?></td>
                    <td><?php echo ucfirst($vatIdEntry['result']) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </body>
</html>

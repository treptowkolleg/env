<?php

use TreptowKolleg\DatabaseContainer;
use TreptowKolleg\Environment;
use TreptowKolleg\Interface\DatabaseInterface;

require 'vendor/autoload.php';

$env = new Environment();
$env->addContainer(new DatabaseContainer());

foreach ($env->getContainer("database")->getAttributes() as $attribute => $value) {
    echo "$attribute: $value\n";
}
$dbContainer = $env->getContainer("database");
if($dbContainer instanceof DatabaseInterface) {
    $pdo = $dbContainer->getPDO();
    $statement = $pdo->query("SELECT 5 + 1");
    $statement->setFetchMode(\PDO::FETCH_ASSOC);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        foreach ($row as $key => $value) {
            echo "$key = $value\n";
        }
    }
}




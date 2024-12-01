<?php

use TreptowKolleg\DatabaseContainer;
use TreptowKolleg\Environment;

require 'vendor/autoload.php';

$env = new Environment();
$env->addContainer(new DatabaseContainer());


$dbContainer = $env->getContainer("database");
if($pdo = $env->getDatabaseObject()) {
    $statement = $pdo->query("SELECT 5 + 1");
    $statement->setFetchMode(\PDO::FETCH_ASSOC);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        foreach ($row as $key => $value) {
            echo "$key = $value\n";
        }
    }
} else {
    echo "Kein PDO-Objekt da!";
}




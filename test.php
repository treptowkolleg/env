<?php

use TreptowKolleg\DatabaseContainer;
use TreptowKolleg\Environment;

require 'vendor/autoload.php';

$environment = new Environment();
$environment->addContainer(new DatabaseContainer());


echo $environment->getContainer("database")->getAttribute("DB_USER");




# Environment & Filesystem Manager

<!-- TOC -->
* [Environment & Filesystem Manager](#environment--filesystem-manager)
  * [Installation](#installation)
  * [Usage](#usage)
    * [Env Files](#env-files)
    * [Environment](#environment)
    * [Add Attribute Containers](#add-attribute-containers)
    * [Implement Own Container](#implement-own-container)
    * [Get Container Parameter](#get-container-parameter)
  * [Example](#example)
<!-- TOC -->

## Installation

````shell
composer require treptowkolleg/env
````

## Usage

### Env Files

DotEnv files (``.env``) are used to store risky credentials out of client code.
Make sure ``.env`` is added to ``.gitignore`` **before commit**.

````dotenv
# db credentials
DB_HOST=localhost
DB_NAME=databaseName
DB_USER=databaseUsername
DB_PASS=1234

# custom vars
VAR_1=x
VAR_2=y
VAR_3=z
````

### Environment

| Parameter | Type                       | Options                                         | Description                                     |
|-----------|----------------------------|-------------------------------------------------|-------------------------------------------------|
| path      | ``TreptowKolleg\Env\Path`` | ``LOCAL_DIR, USER_DIR, PUBLIC_DIR, CUSTOM_DIR`` | where to look for ``.env.local`` or ``.env``    |
| subDir    | ``string``                 |                                                 | path to sub directory                           |
| customEnv | ``string``                 |                                                 | if using ``CUSTOM_DIR`` wich **ENV**-var to use |


````php
<?php

require "vendor/autoload.php";

// projects root dir
$environment = new \TreptowKolleg\Environment();

// projects root dir . /data
$environment = new \TreptowKolleg\Environment(subDir: "data");

// systems user dir | HOME for Linux/macOS | HOMEPATH for Windows
$environment = new \TreptowKolleg\Environment(\TreptowKolleg\Path::USER_DIR);
````

### Add Attribute Containers

````php
<?php

// parse database credentials from .env.local or .env 
$environment->addContainer(new \TreptowKolleg\DatabaseContainer());

````

### Implement Own Container

````php
<?php

class MyContainer extends \TreptowKolleg\AttributeContainer
{
    public function __construct()
    {
        parent::__construct("my_container", ["MY_VAR1", "MY_VAR2", "MY_VAR3"]);
    }
}

$environment->addContainer(new MyContainer());
````

### Get Container Parameter

````php
<?php

$environment->getContainer("my_container")->getAttribute("MY_VAR1");
````

The ``DatabaseContainer`` also implements ``DatabaseInterface``. That mentioned you can
use special methods to instantiate a ``PDO``-Object.

````php
<?php

$dbContainer = $environment->getContainer('database');
if($dbContainer instanceof \TreptowKolleg\Interface\DatabaseInterface) {
    $pdo = $dbContainer->getPDO();
    $statement = $pdo->query("SELECT 5 + 1");
    // continue sql queries
}

// or much simpler:
if($pdo = $environment->getDatabaseObject()) {
    $statement = $pdo->query("SELECT 5 + 1");
    // continue sql queries
}
````

## Example

````php
<?php


use TreptowKolleg\DatabaseContainer;
use TreptowKolleg\Environment;

require 'vendor/autoload.php';

$env = new Environment();
$env->addContainer(new DatabaseContainer());

// Alle Attribute des Containers "database" ausgeben
foreach ($env->getContainer("database")->getAttributes() as $attribute => $value) {
    echo "$attribute: $value\n";
}

// SQL-Abfrage durchführen, falls Datenbank-Verbindung hergestellt werden konnte
if($pdo = $env->getDatabaseObject()) {
    $statement = $pdo->query("SELECT 5 * 2");
    $statement->execute();
    $statement->setFetchMode(\PDO::FETCH_ASSOC);
    $rows = $statement->fetchAll();

    foreach ($rows as $column => $value) {
        echo "$column: $value\n";
    }
}
````
# Environment & Filesystem Manager

## Installation

````shell
composer require treptowkolleg/env
````

## Usage

### Env Files

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

$database = $environment->getContainer("database");

if($database implements \TreptowKolleg\DatabaseInterface) {
    $pdo = new PDO($database->getDSN(), $database->getUser(), $database->getPassword());
    // continue processing db queries
}
````
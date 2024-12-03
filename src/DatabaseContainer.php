<?php

namespace TreptowKolleg\Environment;

use TreptowKolleg\Interface\DatabaseInterface;

class DatabaseContainer extends AttributeContainer implements DatabaseInterface
{

    public function __construct(array $attributes = null)
    {
        parent::__construct("database", $attributes ?? ["DB_HOST", "DB_NAME", "DB_USER", "DB_PASS"]);
    }

    public function getDSN(): ?string
    {
        $host =  $this->getAttribute("DB_HOST") ?? exit("DB_HOST is not set in .env or .env.local file");
        $dbName = $this->getAttribute("DB_NAME") ?? exit("DB_NAME is not set in .env or .env.local file");
        return "mysql:host=$host;dbname=$dbName";
    }

    public function getUser(): ?string
    {
        return $this->getAttribute("DB_USER") ?? exit("DB_USER is not set in .env or .env.local file");
    }

    public function getPassword(): ?string
    {
        return $this->getAttribute("DB_PASS");
    }


    public function getPDO(): \PDO
    {
        try {
            return new \PDO($this->getDSN(), $this->getUser(), $this->getPassword());
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
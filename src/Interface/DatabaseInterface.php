<?php

namespace TreptowKolleg\Environment\Interface;

interface DatabaseInterface
{

    public function getDSN(): ?string;

    public function getUser(): ?string;

    public function getPassword(): ?string;

    public function getPDO(): \PDO;

}
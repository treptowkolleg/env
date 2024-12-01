<?php

namespace TreptowKolleg\Interface;

interface DatabaseInterface
{

    public function getDSN(): ?string;

    public function getUser(): ?string;

    public function getPassword(): ?string;

}
<?php

namespace TreptowKolleg;

class DatabaseContainer extends AttributeContainer
{

    public function __construct(array $attributes = null)
    {
        parent::__construct("database", $attributes ?? ["DB_HOST", "DB_USER", "DB_PASS", "DB_NAME"]);
    }

}
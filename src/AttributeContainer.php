<?php

namespace TreptowKolleg;

class AttributeContainer
{

    private string $name;
    private array $attributes = [];
    public function __construct(string $name, array $attributes = [])
    {
        $this->name = $name;
        foreach ($attributes as $attribute) $this->addAttribute($attribute);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function addAttribute(string $name): static
    {
        if($value = getenv($name)) {
            $this->attributes[$name] = $value;
        } else {
            $this->attributes[$name] = null;
        }
        return $this;
    }

    public function getAttribute(string $name): ?string
    {
        return $this->attributes[$name] ?? null;
    }

}
<?php

class Category
{
    public function __construct(private string $label, private ?int $id = null)
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
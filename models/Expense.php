<?php

class Expense
{
    public function __construct( private string $title, private float $amount, private string $date, private int $userId, private int $categoryId, private ?int $id = null) 
    {
        
    }

    public function getTitle(): string 
    { 
        return $this->title; 
    }

    public function getAmount(): float 
    { 
        return $this->amount; 
    }

    public function getDate(): string 
    { 
        return $this->date; 
    }

    public function getUserId(): int 
    {
        return $this->userId; 
    }

    public function getCategoryId(): int 
    { 
        return $this->categoryId; 
    }

    public function getId(): ?int 
    { 
        return $this->id; 
    }
}
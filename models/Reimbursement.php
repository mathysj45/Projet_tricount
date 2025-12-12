<?php

class Reimbursement
{
    public function __construct( private float $amount, private string $date, private int $from_user_id, private int $to_user_id, private ?int $id = null) 
    {
        
    }
    
    public function getAmount(): float 
    { 
        return $this->amount; 
    }
    
    public function getDate(): string 
    { 
        return $this->date; 
    }
    
    public function getFromUserId(): int 
    {
        return $this->from_user_id; 
    }
    
    public function getToUserId(): int 
    { 
        return $this->to_user_id; 
    }

    public function getId(): ?int 
    { 
        return $this->id; 
    }
}
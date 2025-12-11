<?php

class ExpenseManager extends AbstractManager
{
    public function create(Expense $expense) : void
    {
        $query = $this->db->prepare('INSERT INTO expense (title, amount, date, user_id, category_id) VALUES (:title, :amount, :date, :user_id, :category_id)');
        $parameters = [
            "title" => $expense->getTitle(),
            "amount" => $expense->getAmount(),
            "date" => $expense->getDate(),
            "user_id" => $expense->getUserId(),
            "category_id" => $expense->getCategoryId()
        ];
        $query->execute($parameters);
    }
}
<?php

class ExpenseManager extends AbstractManager
{
    public function findAll() : array
    {
        $sql = "SELECT e.*, u.username, c.label as category_label 
                FROM expense e
                JOIN user u ON e.user_id = u.id
                JOIN category c ON e.category_id = c.id
                ORDER BY e.date DESC";
                
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $expenses = [];

        foreach($result as $item)
        {
            $expense = new Expense(
                $item["title"], 
                (float)$item["amount"], 
                $item["date"], 
                $item["user_id"], 
                $item["category_id"], 
                $item["id"]
            );
            
            $expense->payerName = $item['username'];
            $expense->categoryLabel = $item['category_label'];
            
            $expenses[] = $expense;
        }
        return $expenses;
    }

    public function create(Expense $expense, array $participants) : void
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
        
        $expenseId = $this->db->lastInsertId();

        foreach($participants as $participantId)
        {
            $query2 = $this->db->prepare('INSERT INTO expense_participant (expense_id, user_id) VALUES (:expense_id, :user_id)');
            $query2->execute([
                "expense_id" => $expenseId,
                "user_id" => $participantId
            ]);
        }
    }
}
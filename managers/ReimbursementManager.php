<?php

class ReimbursementManager extends AbstractManager
{

    public function create(Reimbursement $reimbursement, ?int $expenseId = null): void
    {
        $query = $this->db->prepare('
            INSERT INTO reimbursement (amount, date, from_user_id, to_user_id, expense_id) 
            VALUES (:amount, :date, :from_user_id, :to_user_id, :expense_id)
        ');
        
        $parameters = [
            'amount' => $reimbursement->getAmount(),
            'date' => $reimbursement->getDate(),
            'from_user_id' => $reimbursement->getFromUserId(),
            'to_user_id' => $reimbursement->getToUserId(),
            'expense_id' => $expenseId
        ];
        
        $query->execute($parameters);
    }

    public function findAllByUserId(int $userId): array
    {
        $query = $this->db->prepare('
            SELECT * FROM reimbursement 
            WHERE from_user_id = :id OR to_user_id = :id
            ORDER BY date DESC
        ');
        
        $query->execute(['id' => $userId]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $reimbursements = [];
        foreach ($result as $item) {
            $reimbursements[] = new Reimbursement(
                $item['amount'], 
                $item['date'], 
                $item['from_user_id'], 
                $item['to_user_id'],
                $item['id']
            );
        }
        
        return $reimbursements;
    }

    public function findAllWithNames(int $userId): array
    {
        $query = $this->db->prepare('
            SELECT 
                r.id,
                r.amount,
                r.date,
                r.from_user_id,
                r.to_user_id,
                u_from.username AS from_username, 
                u_to.username AS to_username
            FROM reimbursement r
            JOIN user u_from ON r.from_user_id = u_from.id
            JOIN user u_to ON r.to_user_id = u_to.id
            WHERE r.from_user_id = :id OR r.to_user_id = :id
            ORDER BY r.date DESC;');
        $query->execute(['id' => $userId]);
        
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalsByUser(int $userId): array
    {
        $totals = [
            'given' => [],
            'received' => []
        ];

        $sqlGiven = '
            SELECT u.username, SUM(r.amount) as total_amount
            FROM reimbursement r
            JOIN user u ON r.to_user_id = u.id
            WHERE r.from_user_id = :me
            GROUP BY u.username
        ';
        $q1 = $this->db->prepare($sqlGiven);
        $q1->execute(['me' => $userId]);
        $totals['given'] = $q1->fetchAll(PDO::FETCH_ASSOC);

        $sqlReceived = '
            SELECT u.username, SUM(r.amount) as total_amount
            FROM reimbursement r
            JOIN user u ON r.from_user_id = u.id
            WHERE r.to_user_id = :me
            GROUP BY u.username
        ';
        $q2 = $this->db->prepare($sqlReceived);
        $q2->execute(['me' => $userId]);
        $totals['received'] = $q2->fetchAll(PDO::FETCH_ASSOC);

        return $totals;
    }
}

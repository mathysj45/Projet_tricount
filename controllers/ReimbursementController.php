<?php

class ReimbursementController extends AbstractController
{
    private function checkLoggedIn(): void
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php?route=login');
            exit;
        }
    }

    public function details() : void
    {
        $this->checkLoggedIn();
        $userId = $_SESSION['user']->getId(); 
        
        $reimbursementManager = new ReimbursementManager();
        $totals = $reimbursementManager->getTotalsByUser($userId);
        
        $this->render('reimbursement/_reimbursement.html.twig', [
            'totals' => $totals
        ]);
    }

    public function add() : void
    {
        $this->checkLoggedIn();
        $errors = [];
        $userManager = new UserManager();
        $users = $userManager->findAll();
        $preselectedExpenseId = null;
        $preselectedAmount = "";
        $preselectedToUser = "";

        if (isset($_GET['expense_id'])) 
        {
            $expenseManager = new \ExpenseManager();
            $preselectedExpenseId = $_GET['expense_id'];
        }

        if (!empty($_POST))
        {
            $amount = (float) $_POST['amount'];
            $date = $_POST['date'];
            $toUserId = (int) $_POST['to_user_id'];
            $expenseId = !empty($_POST['expense_id']) ? (int) $_POST['expense_id'] : null;
            $fromUserId = $_SESSION['user']->getId();

            if (empty($amount) || empty($date) || empty($toUserId)) 
            {
                $errors[] = "Tous les champs sont obligatoires.";
            }

            if ($toUserId == $fromUserId) 
            {
                $errors[] = "Vous ne pouvez pas vous rembourser vous-même (ce serait trop beau).";
            }

            if (empty($errors)) 
            {
                $reimbursement = new Reimbursement($amount, $date, $fromUserId, $toUserId);
                $reimbursementManager = new ReimbursementManager();
                $reimbursementManager->create($reimbursement, $expenseId);

                if ($expenseId) 
                {
                    $expenseManager = new ExpenseManager();
                    $expense = $expenseManager->findById($expenseId);
                    $totalReimbursed = $expenseManager->getTotalReimbursedForExpense($expenseId);
                    if ($expense && $totalReimbursed >= $expense->getAmount())
                    {
                        $expenseManager->markAsSettled($expenseId);
                    }
                }

                $this->redirect('index.php?route=dashboard');
            }
        }

        $this->render('reimbursement/add.html.twig', [
            'users' => $users,
            'errors' => $errors,
            'expense_id' => $preselectedExpenseId
        ]);
    }
}
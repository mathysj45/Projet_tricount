<?php

class ExpenseController extends AbstractController
{
    public function add() : void
    {
        if (!isset($_SESSION['user'])) 
        {
            $this->redirect('index.php?route=login');
        }

        $errors = [];
        
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->findAll();

        $userManager = new UserManager();
        $users = $userManager->findAll();

        if(!empty($_POST)) {
            $title = $_POST['title'];
            $amount = $_POST['amount'];
            $date = $_POST['date']; 
            $categoryId = $_POST['category'];
            $participantsIds = $_POST['participants'] ?? [];
            $userId = $_SESSION['user']->getId();

            if(empty($title) || empty($amount) || empty($date) || empty($categoryId)) 
            {
                $errors[] = "Tous les champs sont obligatoires";
            }

            if(empty($participantsIds))
            {
                $errors[] = "Vous devez sélectionner au moins une personne concernée.";
            }

            if(empty($errors)) 
            {
                $expense = new Expense($title, (float)$amount, $date, $userId, (int)$categoryId);
                
                $expenseManager = new ExpenseManager();
                $expenseManager->create($expense, $participantsIds);
                
                $this->redirect('index.php?route=dashboard'); 
            }
        }

        $this->render('expense/add.html.twig', [
            'categories' => $categories,
            'users' => $users,
            'errors' => $errors
        ]);
    }
}
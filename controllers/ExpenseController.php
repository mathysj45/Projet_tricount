<?php

class ExpenseController extends AbstractController
{
    public function add() : void
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php?route=login');
        }

        $errors = [];
        
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->findAll();

        if(!empty($_POST)) {
            $title = $_POST['title'];
            $amount = $_POST['amount'];
            $date = $_POST['date']; 
            $categoryId = $_POST['category'];
            
            $userId = $_SESSION['user']->getId();

            if(empty($title) || empty($amount) || empty($date) || empty($categoryId)) {
                $errors[] = "Tous les champs sont obligatoires";
            }

            if(empty($errors)) {
                $expense = new Expense($title, (float)$amount, $date, $userId, (int)$categoryId);
                
                $expenseManager = new ExpenseManager();
                $expenseManager->create($expense);
                
                $this->redirect('index.php?route=home'); 
            }
        }

        $this->render('expense/add.html.twig', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    }
}
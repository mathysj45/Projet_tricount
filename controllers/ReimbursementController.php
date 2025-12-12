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

        if (!empty($_POST)) {
            $amount = $_POST['amount'];
            $date = $_POST['date'];
            $toUserId = $_POST['to_user_id'];
            $fromUserId = $_SESSION['user']->getId();

            if (empty($amount) || empty($date) || empty($toUserId)) {
                $errors[] = "Tous les champs sont obligatoires.";
            }

            if ($toUserId == $fromUserId) {
                $errors[] = "Vous ne pouvez pas vous rembourser vous-même (ce serait trop beau).";
            }

            if (empty($errors)) {
                $reimbursement = new Reimbursement(
                    (float)$amount, 
                    $date, 
                    $fromUserId, 
                    (int)$toUserId
                );
                
                $manager = new ReimbursementManager();
                $manager->create($reimbursement);

                $this->redirect('index.php?route=reimbursement');
            }
        }

        $this->render('reimbursement/add.html.twig', [
            'users' => $users,
            'errors' => $errors
        ]);
    }
}
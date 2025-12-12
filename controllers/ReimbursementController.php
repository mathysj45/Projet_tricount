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
        $reimbursements = $reimbursementManager->findAllByUserId($userId);
        $this->render('reimbursement/_reimbursement.html.twig', [
            'reimbursements' => $reimbursements
        ]);
    }
}
?>
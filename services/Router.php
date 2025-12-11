<?php

class Router
{
    private AuthController $ac;
    private UserController $uc;
    private ExpenseController $ec;
    public function __construct()
    {
        $this->ac = new AuthController();
        $this->uc = new UserController();
        $this->ec = new ExpenseController();
    }

    public function handleRequest() : void
    {
        if(!empty($_GET['route'])) {
            if($_GET['route'] === 'login') {
                $this->ac->login();
            }
            else if($_GET['route'] === 'register') {
                $this->ac->register();
            }
            else if($_GET['route'] === 'logout') {
                $this->ac->logout();
            }
            else if($_GET['route'] === 'profile') {
                $this->uc->profile();
            }
            else if($_GET['route'] === 'update') {
                $this->uc->update();
            }
            else if($_GET['route'] === 'list') {
                $this->uc->list();
            }
            else if($_GET['route'] === 'expense') {
                $this->uc->expense();
            }
            else if($_GET['route'] === 'reimbursement') {
                $this->uc->reimbursement();
            }
            else if($_GET['route'] === 'add_expense') {
                $this->ec->add();
            }
            else
            {
                $this->ac->notFound();
            }
        }
        else
        {
            $this->ac->home();
        }
    }
}
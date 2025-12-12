<?php

class UserController extends AbstractController
{
    private function checkAdmin() : void
    {
        if (!isset($_SESSION['user'])) 
        {
            $this->redirect('index.php?route=login');
            exit;
        }
    }

    private function checkLoggedIn(): void
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php?route=login');
            exit;
        }
    }

    public function profile() : void
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php?route=login');
            exit;
        }

        $this->render('member/profile.html.twig', []);
    }

    public function create() :void
    {
        $this->checkAdmin();
        $errors=[];
        if(!empty($_POST))
        {
            $email = $_POST["email"];
            $passwordEnClair = $_POST["password"];
            $confirmPasswordEnClair = $_POST['confirmPassword'];
            $username = $_POST['username'];

            if((empty($email)) || (empty($passwordEnClair)) || (empty($confirmPasswordEnClair)) || (empty($username)))
            {
                $errors[] = "Tout les champs sont obligatoires";
            }

            if (($passwordEnClair !== $confirmPasswordEnClair))
            {
                $errors[]="Je détecte un problème dans vos passwords: ils ne sont pas pareils bip-boup";
            }
                
            if (empty($errors))
            {
                $userManager = new UserManager();
                $emailExist = $userManager->findByEmail($email);
                if ($emailExist)
                {
                    $errors[] = "IL a déjà un compte petit menteur.... (╬▔皿▔)╯";
                }
            }
            if (empty($errors))
            {
                $userManager = new UserManager(); 
                $hashedPassword = password_hash($passwordEnClair,PASSWORD_DEFAULT);
                $user= new User($email, $hashedPassword, $username);
                $userManager->create($user);
            }
        }

        $this->render('admin/users/create.html.twig', [
            'errors'=> $errors
        ]);
    }

    public function update() : void
    {
        $this->checkAdmin();
        if(isset($_GET['id'])) 
        {
            $id = (int)$_GET['id'];
            $userManager = new UserManager();
            $userToUpdate = $userManager->findById($id);

            $this->render('admin/users/update.html.twig', [
                'user' => $userToUpdate
            ]);
        }

        if (!empty($_POST)) 
        {
            $email = $_POST['email'];
            $passwordEnClair = $_POST['password'];
            $username = $_POST['username'];
            $role = $_POST['role'];

            $userToUpdate->setEmail($email);
            $userToUpdate->setUsername($username);
            $usertToUpdate->setRole($role);

            if (!empty($passwordEnClair)) 
            {
                $hashedPassword = password_hash($passwordEnClair, PASSWORD_DEFAULT);
                $userToUpdate->setPassword($hashedPassword);
            }

            $userManager->update($userToUpdate);
            $this->redirect("index.php?route=list");
        }

        $this->render('admin/users/update.html.twig', [
            'user' => $userToUpdate
        ]);
    }

    public function delete() : void
    {
        $this->checkAdmin();
        $userManager = new UserManager();
        $users = $userManager->findAll();

          if(isset($_GET['id'])) 
        {
            $userManager = new UserManager();
            $userToDelete = $userManager->findById($_GET['id']);

            if ($userToDelete) 
            {
                $userManager->delete($userToDelete);
            }
            $this->redirect("index.php?route=list");
        }
    }

    public function list() : void
    {
        $this->checkAdmin();
        $userManager = new UserManager();
        $users = $userManager->findAll();
        $this->render('admin/users/index.html.twig', ['users'=>$users]);
    }

    public function show() : void
    {
        $this->checkAdmin();

        if(isset($_GET['id'])) 
        {
            $id = (int)$_GET['id'];
            $userManager = new UserManager();
            $user = $userManager->findById($id);

            $this->render('admin/users/show.html.twig', [
                'user' => $user
            ]);
        }
    }

    public function member() : void
    {
        $this->checkLoggedIn();
        $userManager = new UserManager();
        $users = $userManager->findAll();
        $this->render('member/member.html.twig', ['users' => $users]);
        
    }
}

<?php

class AuthController extends AbstractController
{
    public function home() : void
    {
        $this->render('home/home.html.twig', []);
    }

    public function login() : void
    {
        $errors=[];
        if(!empty($_POST))
        {
            $email = $_POST["email"];
            $passwordEnClair = $_POST["password"];

            if( (empty($email)) || (empty($passwordEnClair)))
            {
                $errors[] = "Tout les champs sont obligatoires";
            }
                
            if (empty($errors))
            {
                $userManager = new UserManager();
                $user = $userManager->findByEmail($email);
                if ($user !== null && password_verify($passwordEnClair, $user->getPassword()))
                {
                    $_SESSION['user'] = $user;
                    $this->redirect('index.php?route=profile');
                }
                else
                {
                    $errors[] = "Le mot de passe ou l'email est incorrect chef (T_T)";
                }
            }
        }

        $this->render('auth/login.html.twig', [
            'errors'=> $errors
        ]);
    }


    public function logout() : void
    {
        session_destroy();
        $this->redirect('index.php');
    }

    public function register() : void
    {
        $errors=[];
        if(!empty($_POST))
        {
            $email = $_POST["email"];
            $passwordEnClair = $_POST["password"];
            $username = $_POST["username"];
            $confirmPasswordEnClair = $_POST['confirmPassword'];

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
                    $errors[] = "Tu as déjà un compte petit menteur.... (╬▔皿▔)╯";
                }
            }
            if (empty($errors))
            {
                $userManager = new UserManager(); 
                $hashedPassword = password_hash($passwordEnClair,PASSWORD_DEFAULT);
                $user= new User($email, $hashedPassword, $username);
                $userManager->create($user);
                $this->redirect('index.php?route=login');
            }
        }

        $this->render('auth/register.html.twig', [
            'errors'=> $errors
        ]);
        
    }

    public function notFound() : void
    {
        $this->render('error/notFound.html.twig', []);
    }
}
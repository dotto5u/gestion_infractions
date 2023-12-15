<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    include_once "../modele/conducteurDAO.class.php";
    
    $identifiants = [];
    $erreur = "";
    $titre = "Connexion";

    $identifiants["login"] = isset($_POST['login'])?trim($_POST['login']):null;
    $identifiants["mdp"] = isset($_POST['mdp'])?trim($_POST['mdp']):null;

    function existeConducteur(array $identifiants): bool
    {   
        $existe = false;
        $conducteurDAO = new ConducteurDAO();

        $conducteur = $conducteurDAO->getByNumPermis($identifiants["login"]);

        if ($identifiants["login"] === $conducteur->getNumPermis() && password_verify($identifiants["mdp"], $conducteur->getMdp()))
        {
            $existe = true;
        }
        return $existe;
    }
    
    if (isset($_POST["connexion"])) 
    {   
        $bd = new Connexion();

        if (existeConducteur($identifiants) || $bd->estAdmin($identifiants["login"], $identifiants["mdp"]))
        {
            session_start();
            
            $_SESSION["open"]  = true;
            $_SESSION["login"] = $identifiants["login"]; 

            if ($bd->estAdmin($identifiants["login"], $identifiants["mdp"]))
            {
                $_SESSION["admin"] = true;
            } 

            header("location: inf_liste.php");
        }
        else
        {
            $erreur = "identifiant ou mot de passe incorrect";
        }
    }

    include_once "../vue/login.view.php";
?>
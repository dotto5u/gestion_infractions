<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    include_once "../modele/delitDAO.class.php";
    include_once "../modele/delitByInfractionDAO.class.php";

    session_start();

    if (!isset($_SESSION["open"]))
    {
        header("location: logout.php");
    }

    $estAdmin = isset($_SESSION["admin"]);

    $op     = isset($_SESSION["op_delit"])?$_SESSION["op_delit"]:(isset($_GET["op"])?$_GET["op"]:"");
    $ajout  = $op == 'a';
    $suppr  = $op == 's';
    $id_inf = isset($_SESSION["id_inf"])?$_SESSION["id_inf"]:(isset($_GET["id_inf"])?$_GET["id_inf"]:"");
    $id_del = isset($_SESSION["id_del"])?$_SESSION["id_del"]:(isset($_GET["id_del"])?$_GET["id_del"]:"");
    
    if (($ajout && $id_inf != "" && $id_del == "" || $op != "" && $id_inf != "" && $id_del != "") && $estAdmin)
    {   
        $delitDAO = new DelitDAO();
        $delitByInfractionDAO = new DelitByInfractionDAO();

        $_SESSION["op_delit"] = $op;
        $_SESSION["id_inf"] = $id_inf;
        $_SESSION["id_del"] = $id_del;

        $titre = "Nouveau delit";

        $valeurs = ["id" => isset($_POST["listeDelit"])?$_POST["listeDelit"]:""];

        $erreurs = ["delit" => ""];

        $retour = false;

        if (isset($_POST["Valider"]))
        {   
            if (!isset($valeurs["id"]) || $valeurs["id"] == "")
            {
                $erreurs["delit"] = "Aucun délit sélectionné.";
            }
            else
            {                   
                array_push($_SESSION["id_delit"], $valeurs["id"]);

                $retour = true;
            }
        }
        else if (isset($_POST["Annuler"]))
        {
            $retour = true;
        }

        if ($suppr)
        {        
            // suppression de l'id du délit dans la variable $_SESSION["id_delit"]

            unset($_SESSION["id_delit"][array_search($id_del, $_SESSION["id_delit"])]);

            $retour = true;
        }

        if ($retour)
        {   
            unset($_SESSION["op_delit"]);
            unset($_SESSION["id_inf"]);
            unset($_SESSION["id_del"]);

            header("location: inf_edit.php?op=".urlencode($_SESSION["op_inf"])."&id=$id_inf");
        }

        // affichage de la liste des délits

        $libelles = [];

        $delits = $delitDAO->getAll();

        foreach ($delits as $delit)
        {   
            if (!in_array($delit->getIdDelit(), $_SESSION["id_delit"]))
            {
                $libelles[$delit->getIdDelit()] = $delit->getNature(); 
            }
        }

        include_once "../vue/inf_delit_edit.view.php";
    }
    else
    {
        header("location: inf_liste.php");
    }
?>
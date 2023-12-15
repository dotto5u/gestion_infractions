<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    include_once "../modele/infractionDAO.class.php";
    include_once "../modele/conducteurDAO.class.php";
    include_once "../modele/vehiculeDAO.class.php";
    include_once "../modele/delitDAO.class.php";
    include_once "../modele/delitByInfractionDAO.class.php";

    session_start();

    if (!isset($_SESSION["open"]))
    {
        header("location: logout.php");
    }

    $estAdmin = isset($_SESSION["admin"]);

    $op 	= isset($_SESSION["op_inf"])?$_SESSION["op_inf"]:(isset($_GET["op"])?$_GET["op"]:"");
    $visu   = $op == 'v';
    $ajout 	= $op == 'a';
    $modif 	= $op == 'm';
    $suppr 	= $op == 's';
    $id     = isset($_SESSION["id"])?$_SESSION["id"]:(isset($_GET["id"])?$_GET["id"]:"");

    // accès à la page en fonction des privilèges de l'utilisateur et seulement si l'op et l'id sont bien passés en paramètre

    if (($ajout && $id == "" || $op != "" && $id != "") && ($estAdmin || !$estAdmin && $visu))
    {      
        $infractionDAO = new InfractionDAO();
        $conducteurDAO = new ConducteurDAO();
        $vehiculeDAO   = new VehiculeDAO();
        $delitDAO      = new DelitDAO();     
        $delitByInfractionDAO = new DelitByInfractionDAO();

        $titre = $visu?"Détail de l'infraction":($ajout?"Nouvelle infraction":($modif?"Modification d'une infraction":"Suppression de l'infraction")); 

        $valeurs = [];

        // on passe par la variable $_SESSION pour stocker les informations de l'infraction afin de les garder en mémoire après un post 

        $valeurs["id"] = isset($_POST["infId"])?$_POST["infId"]:$id;
        $valeurs["date"] = isset($_POST["infDate"])?$_POST["infDate"]:(isset($_SESSION["date"])?$_SESSION["date"]:"");
        $valeurs["immat"] = isset($_POST["infImmat"])?trim($_POST["infImmat"]):(isset($_SESSION["immat"])?$_SESSION["immat"]:"");
        $valeurs["permis"] = isset($_POST["infPermis"])?trim($_POST["infPermis"]):(isset($_SESSION["permis"])?$_SESSION["permis"]:"");
        $valeurs["id_delit"] = isset($_SESSION["id_delit"])?$_SESSION["id_delit"]:[];
        
        $valeurs["detailVehicule"] = "\r\n";
        $valeurs["detailProprio"] = "\r\n";
        $valeurs["detailConducteur"] = "\r\n";

        // mise à jour de la variable $_SESSION 
        
        $_SESSION["op_inf"] = $op;
        $_SESSION["id"] = $valeurs["id"];
        $_SESSION["date"] = $valeurs["date"];
        $_SESSION["immat"] = $valeurs["immat"];
        $_SESSION["permis"] = $valeurs["permis"];
        $_SESSION["id_delit"] = $valeurs["id_delit"];

        $erreurs = ["date" => "", "immat" => "", "permis" => "", "delit" => ""];

        $baliseDelitSuppr = "";
        $btnAjouterDelit  = "";
        $btnRetourValiderAnnuler = $visu?'<input type="submit" id="retour" class="retour" name="Retour" value="Retour"/>'
                                        :'<input type="submit" id="valider" name="Valider" value="Valider"/>&emsp;<input type="submit" id="annuler" name="Annuler" value="Annuler"/>';

        $retour = false;

        if (isset($_POST["Valider"]))
        {   
            // vérification de chaque zone de saisie

            if (!isset($valeurs["date"]) || strlen($valeurs["date"]) == 0)
            {
                $erreurs["date"] = "La date de l'infraction doit être renseignée.";
            } 
            else if ($valeurs["date"] > date("Y-m-d"))
            {
                $erreurs["date"] = "La date d'infraction doit être antérieure ou égale à la date du jour.";
            }

            if (!isset($valeurs["immat"]) || strlen($valeurs["immat"]) == 0)
            {
                $erreurs["immat"] = "L'immatriculation doit être renseignée.";
            }
            else if ($vehiculeDAO->getByNumImmat($valeurs["immat"])->getNumImmat() == "")
            {
                $erreurs["immat"] = "Numéro d'immatriculation inconnu.";
            }

            if (isset($valeurs["permis"]) && strlen($valeurs["permis"]) != 0)
            {   
                if ($conducteurDAO->getByNumPermis($valeurs["permis"])->getNumPermis() == "")
                {
                    $erreurs["permis"] = "Numéro de permis inconnu.";
                }
            }

            if ($valeurs["id_delit"] == [])
            {
                $erreurs["delit"] = "L'infraction doit comporter au moins un délit.";
            }

            // ajout ou modification d'une infraction s'il n y a pas d'erreurs

            $nbErreurs = 0;

            foreach ($erreurs as $erreur)
            {
                if ($erreur != "") $nbErreurs++;
            }

            if ($nbErreurs == 0)
            {
                $infraction = new Infraction($valeurs["id"], $valeurs["date"], $valeurs["immat"], $valeurs["permis"]);

                if ($ajout)	
                {
                    $infractionDAO->insert($infraction);
                }	
                else 
                {			
                    $infractionDAO->update($infraction);

                    // si un délit n'est pas dans la table des délits mais qu'il existe dans la bdd, on met à jour la bdd en le supprimant.

                    $delits = $delitDAO->getAll();

                    foreach ($delits as $delit)
                    {   
                        if (!in_array($delit->getIdDelit(), $_SESSION["id_delit"]))
                        {
                            if ($delitByInfractionDAO->existe($valeurs["id"], $delit->getIdDelit())) 
                            {
                                $delitByInfractionDAO->delete($valeurs["id"], $delit->getIdDelit());
                            }
                        }
                    }
                }

                // ajout de chaque nouveau délit dans la bdd s'il n'y est pas déjà

                foreach($_SESSION["id_delit"] as $id_delit)
                {
                    if (!$delitByInfractionDAO->existe($valeurs["id"], $id_delit))
                    {
                        $delit = $delitDAO->getByIdDelit($id_delit);

                        $delitByInfraction = new DelitByInfraction($valeurs["id"], $delit);

                        $delitByInfractionDAO->insert($delitByInfraction);
                    }
                }
                $retour = true;
            }
        }
        else if (isset($_POST["Annuler"]) || isset($_POST["Retour"]))
        {
            $retour = true;
        }

        else if (isset($_POST["AjoutDelit"]))
        {
            header("location: inf_delit_edit.php?op=a&id_inf=".$valeurs["id"]."");
        }
        else if (isset($_POST["SupprDelit"]))
        {
            header("location: inf_delit_edit.php?op=s&id_inf=".$valeurs["id"]."&id_del=".$_POST["SupprDelit"]."");
        }

        else if ($ajout && ($valeurs["id"] == "" && $valeurs["date"] == "" && $valeurs["immat"] == "" && $valeurs["permis"] == "" && $valeurs["id_delit"] == []))
        {   
            // initialisation de valeurs par defaut en cas d'ajout si l'utilisateur rentre pour la 1er fois sur la page

            $valeurs["id"] = $infractionDAO->getNewIdInf();
            
            $_SESSION["date"] = $valeurs["date"] = date("Y-m-d");
        }

        else if (!$ajout && ($valeurs["date"] == "" && $valeurs["immat"] == "" && $valeurs["permis"] == "" && $valeurs["id_delit"] == []))
        {   
            // affichage des informations dans les zones de saisie si l'utilisateur rentre pour la 1er fois sur la page

            $infraction = $infractionDAO->getByIdInf($valeurs["id"]);

            $valeurs["id"] = $infraction->getIdInf();

            $_SESSION["date"] = $valeurs["date"] = $infraction->getDateInf(); 
            $_SESSION["immat"] = $valeurs["immat"] = $infraction->getNumImmat();
            $_SESSION["permis"] = $valeurs["permis"] = $infraction->getNumPermis();

            foreach($delitByInfractionDAO->getByIdInf($valeurs["id"]) as $delitByInfraction)
            {
                array_push($_SESSION["id_delit"], $delitByInfraction->getDelit()->getIdDelit());
            }
            $valeurs["id_delit"] = $_SESSION["id_delit"];
        }

        if ($ajout || $modif)
        {
            $baliseDelitSuppr = "<th></th>";
            $btnAjouterDelit  = "<tr><td colspan='3' style='text-align:right'><input type='submit' name='AjoutDelit' value='Ajouter un delit' class='ajout'></td></tr>";
        }
        
        if ($suppr)
        {   
            $delitByInfractionDAO->deleteByIdInf($valeurs["id"]);
            $infractionDAO->delete($valeurs["id"]);

            $retour = true;
        }

        if ($retour)
        {       
            // suppression des informations de l'infraction en mémoire lors d'un retour

            unset($_SESSION["op_inf"]);
            unset($_SESSION["id"]);
            unset($_SESSION["date"]);
            unset($_SESSION["immat"]);
            unset($_SESSION["permis"]);
            unset($_SESSION["id_delit"]);

            header("location: inf_liste.php");
        }

        // affichage des détails du véhicule et de son propriétaire

        if ($valeurs["immat"] != "")
        {
            $vehicule = $vehiculeDAO->getByNumImmat($valeurs["immat"]);
            $conducteur = $conducteurDAO->getByNumPermis($vehicule->getNumPermis());

            if ($vehicule->getNumImmat() != "") 
            {       
                $valeurs["detailVehicule"] = "le ".date("d/m/Y", strtotime($vehicule->getDateImmat()))."\r\n".$vehicule->getMarque()." ".$vehicule->getModele();

                $valeurs["detailProprio"] = "propriétaire : \r\n".$conducteur->getNom()." ".$conducteur->getPrenom()."\r\npermis obtenu le ".date("d/m/Y", strtotime($conducteur->getDatePermis()));
            }
            else
            {
                $valeurs["detailVehicule"] = "véhicule inconnu";
            }
        }

        // affichage des détails du conducteur

        if ($valeurs["permis"] != "")
        {
            $conducteur = $conducteurDAO->getByNumPermis($valeurs["permis"]);

            if ($conducteur->getNumPermis() != "") 
            {       
                $valeurs["detailConducteur"] = $conducteur->getNom()." ".$conducteur->getPrenom()."\r\npermis obtenu le ".date("d/m/Y", strtotime($conducteur->getDatePermis()));
            }
            else
            {
                $valeurs["detailConducteur"] = "conducteur inconnu";
            }
        }

        // affichage des délits de l'infraction

        $lignes = [];

        $montant = 0;

        foreach($valeurs["id_delit"] as $id_delit)
        {
            $delit = $delitDAO->getByIdDelit($id_delit);

            $ch = "";

            $ch .= "<td>".$delit->getNature()."</td>";
            $ch .= "<td>".$delit->getTarif()."</td>";

            if (!$visu)
            {   
                $ch .= "<td><button type='submit' name='SupprDelit' value='".$id_delit."' style='border: none'><img src='../vue/style/corbeille.png'></td>";
            }
            $lignes[] = "<tr>$ch</tr>";

            $montant += (int)$delit->getTarif();
        }
        $labelDelit = "Délits constatés : $montant euro"; 

        if ($montant > 0)
        {
            $labelDelit .= "s";
        }

        include_once "../vue/inf_edit.view.php";
    }
    else
    {
        header("location: inf_liste.php");
    }
?>
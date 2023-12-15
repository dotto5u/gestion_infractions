<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    include_once "../modele/infractionDAO.class.php";
    include_once "../modele/conducteurDAO.class.php";
    include_once "../modele/delitByInfractionDAO.class.php";

    session_start();

    $estAdmin = isset($_SESSION["admin"]);

    // ouverture de la page seulement si une session est ouverte

    if (!isset($_SESSION["open"]))
    {
        header("location: logout.php");
    }
    else
    {   
        // initialisation de chaque variable

        $infractionDAO = new InfractionDAO();
        $conducteurDAO = new ConducteurDAO();
        $delitByInfractionDAO = new DelitByInfractionDAO();

        $titre = "Liste des infractions";

        $balisesModifSuppr = "";    // cette variable permet d'afficher les balises <th> de la table des infractions seulement si l'utilisateur est administrateur
        $btnAjouterInfraction = ""; // cette variable permet d'afficher le bouton pour ajouter une infraction seulement si l'utilisateur est administrateur
        $info_json = ""; // cette variable contient les informations après l'ajout d'infractions avec le fichier json

        // gestion des infractions en fonction du statut de l'utilisateur (administrateur ou non)
        
        if ($estAdmin)
        {
            $infractions = $infractionDAO->getAll();

            $balisesModifSuppr = "<th></th><th></th>";
            $btnAjouterInfraction = 
            "<tr>
                <td colspan='2' style='text-align:left' ><a href='inf_json.php' class='ajout'>Ajouter avec json</a></td>
                <td colspan='6' style='text-align:right' ><a href='inf_edit.php?op=a' class='ajout'>Ajouter une infraction</a></td>
            </tr>";
        }
        else
        {
            $infractions = $infractionDAO->getByNumPermis($_SESSION["login"]);
        }

        $lignes = [];

        // création de chaque ligne de la table des infractions

        foreach ($infractions as $infraction)
        {
            $ch  = '<td><a href="inf_edit.php?op=v&id='.urlencode($infraction->getIdInf()).'"><img src="../vue/style/visu.png"></a></td>';

            $ch .= "<td>".$infraction->getIdInf()."</td>";
            $ch .= "<td>".$infraction->getDateInf()."</td>";
            $ch .= "<td>".$infraction->getNumImmat()."</td>";

            $conducteur = $conducteurDAO->getByNumPermis($infraction->getNumPermis());

            $ch .= "<td>".$conducteur->getNumPermis()." ".$conducteur->getNom()." ".$conducteur->getPrenom()."</td>";

            $ch .= "<td>".$delitByInfractionDAO->getMontantTotal($delitByInfractionDAO->getByIdInf($infraction->getIdInf()))." €</td>";

            // on ajoute les boutons pour modifier ou supprimer une infraction si l'utilisateur est administrateur

            if ($estAdmin)
            {
                $ch .= '<td><a href="inf_edit.php?op=m&id='.urlencode($infraction->getIdInf()).'"><img src="../vue/style/modification.png"></a></td>';
                $ch .= '<td><a href="inf_edit.php?op=s&id='.urlencode($infraction->getIdInf()).'"><img src="../vue/style/corbeille.png"></a></td>';
            }

            $lignes[] = "<tr>$ch</tr>";
        }
        unset($infractions);

        // affichage seulement si l'administrateur a ajouté des infractions avec le fichier json

        if (isset($_SESSION["ajout_json"]))
        {
            $info_json = "<section>
                            <label></label>
                            <ul>".$_SESSION["ajout_json"]."</ul>
                          </section>";

            unset($_SESSION["ajout_json"]);
        }

        include_once "../vue/inf_liste.view.php";
    }
?>
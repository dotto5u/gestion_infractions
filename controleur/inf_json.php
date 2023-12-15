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

    if ($estAdmin)
    {
        $infractionDAO = new InfractionDAO();
        $conducteurDAO = new ConducteurDAO();
        $vehiculeDAO   = new VehiculeDAO();
        $delitDAO      = new DelitDAO();     
        $delitByInfractionDAO = new DelitByInfractionDAO();

        $_SESSION["ajout_json"] = "";

        $nomFichier = "infractions_ext.json";

        $info = "";

        if (file_exists("../$nomFichier"))
        {
            $fichier = file_get_contents("../$nomFichier");

            $inf_string = json_decode($fichier);

            if ($inf_string != null)
            {
                for ($i = 0; $i < count($inf_string); $i++)
                {   
                    // test sur chaque donnée pour vérifier s'il y a des erreurs

                    $erreur = 0;

                    if (isset($inf_string[$i]->date_inf))
                    {
                        if ($inf_string[$i]->date_inf > date("d/m/Y"))
                        {
                            $info .= "<li>La date de l'infraction ".($i+1)." doit être antérieure ou égale à la date du jour</li>";
                            $erreur++;
                        }
                    }
                    else
                    {
                        $info .= "<li>La date de l'infraction ".($i+1)." n'est pas renseignée</li>";
                        $erreur++;
                    }

                    if (isset($inf_string[$i]->num_immat))
                    {
                        if (!$vehiculeDAO->existe($inf_string[$i]->num_immat))
                        {
                            $info .= "<li>Le numéro d'immatriculation de l'infraction ".($i+1)." n'existe pas</li>";
                            $erreur++;
                        }
                    }
                    else
                    {
                        $info .= "<li>Le numéro d'immatriculation de l'infraction ".($i+1)." n'est pas renseigné</li>";
                        $erreur++;
                    }

                    if (isset($inf_string[$i]->num_permis))
                    {   
                        if ($inf_string[$i]->num_permis != "")
                        {
                            if (!$conducteurDAO->existe($inf_string[$i]->num_permis))
                            {
                                $info .= "<li>Le numéro de permis de l'infraction ".($i+1)." n'existe pas</li>";
                                $erreur++;
                            }
                        }
                    }
                    else
                    {
                        $info .= "<li>Le numéro de permis de l'infraction ".($i+1)." n'est pas renseigné</li>";
                        $erreur++;
                    }

                    if (isset($inf_string[$i]->délits))
                    {
                        for ($j = 0; $j < count($inf_string[$i]->délits); $j++)
                        {
                            if (!$delitDAO->existe($inf_string[$i]->délits[$j]))
                            {
                                $info .= "<li>Le délit ".($j+1)." de l'infraction ".($i+1)." n'est pas renseigné</li>";
                                $erreur++;
                            }
                        }
                    }
                    else
                    {
                        $info .= "<li>Les délits de l'infraction ".($i+1)." ne sont pas renseignés<li>";
                        $erreur++;
                    }

                    // ajout des infractions et des délits

                    if ($erreur == 0)
                    {
                        $infraction = new Infraction($infractionDAO->getNewIdInf(), date("Y-m-d", strtotime($inf_string[$i]->date_inf))
                                                    ,$inf_string[$i]->num_immat, $inf_string[$i]->num_permis, $inf_string[$i]->délits);

                        $infractionDAO->insert($infraction);

                        foreach ($inf_string[$i]->délits as $id_delit)
                        {   
                            $delitByInfraction = new DelitByInfraction($infraction->getIdInf(), $delitDAO->getByIdDelit($id_delit));

                            $delitByInfractionDAO->insert($delitByInfraction);
                        } 

                        $info .= "<li>L'infraction ".($i+1)." a été ajoutée avec succès !</li><br>";
                    }
                } 
            }
            else
            {
                $info .= "<li>Erreur lors de la lecture du fichier</li>";
            }
        }
        else
        {
            $info .= "<li>Le fichier 'infractions_ext.json' n'existe pas</li>"; 
        }

        $_SESSION["ajout_json"] = $info;

        header("location: inf_liste.php");
    }
    else
    {
        header("location: inf_liste.php");
    }
?>
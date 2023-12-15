<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Edition d'une infraction</title>
        <link type="text/css" rel="stylesheet" href="../vue/style/style.css">
    </head>
    <body>
        <?php include_once "../vue/header.php" ?>

        <section>
            <label></label>
            <h1><?php echo $titre ?></h1>     
        </section>

        <form action="inf_edit.php" method="post">
            <section>
                <label for="infInf">numéro :</label>
                <div>
                    <input id="infId" size="8" type="text" name="infId" value="<?php echo $valeurs["id"] ?>" readonly="readonly">
                </div>
            </section>

            <section>
                <label for="infDate">date :</label>
                <div>
                    <?php
                        echo "<input  id='infDate' type='date' name='infDate' value='".$valeurs["date"]."' ";
                        if (!$ajout)
                        {
                            echo "readonly='readonly'";
                        }
                        echo ">";
                    ?>
                    <br/>
                    <span class="erreur"><?= $erreurs["date"] ?></span>
                </div>
            </section>

            <section class="inf_rubrique">
                <label for="infImmat">n°immat :</label>
                <div>
                    <?php
                        echo "<input  id='infImmat' size='15' type='text' name='infImmat' value='".$valeurs["immat"]."' ";
                        if ($visu || $suppr)
                        {
                            echo "readonly='readonly'";
                        }
                        echo ">";
                    ?>                    
                    <br/>
                    <span class="erreur"><?= $erreurs["immat"] ?></span>
                </div>
                <label class="inf_commentaire"><?= $valeurs["detailVehicule"] ?></label>
                <label class="inf_commentaire"><?= $valeurs["detailProprio"] ?></label>
            </section class="inf_rubrique">
            <br/>
            <section class="inf_rubrique">
                <label for="infPermis">n°permis :</label>
                <div>
                    <?php
                        echo "<input  id='infPermis' size='15' type='text' name='infPermis' value='".$valeurs["permis"]."' ";
                        if ($visu || $suppr)
                        {
                            echo "readonly='readonly'";
                        }
                        echo ">";
                    ?>  
                    <br/>
                    <span class="erreur"><?= $erreurs["permis"] ?></span>
                </div>
                <label class="inf_commentaire"><?= $valeurs["detailConducteur"] ?></label>
            </section>

            <section>
                <label></label>
                <h1>Liste des délits</h1>
            </section>

            <section>
                <label></label>
                <label style="text-align:left"><?= $labelDelit ?></label>
            </section>

            <section>
                <label></label>
                <div>
                    <table name="table_delit">
                        <thead>
                            <tr>
                                <th>nature</th>
                                <th>tarif</th>
                                <?php echo $baliseDelitSuppr ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($lignes as $ligne)
                                {
                                    echo $ligne;
                                }
                            ?>
                        </tbody>
                        <?php echo $btnAjouterDelit ?>
                    </table>
                    <span class="erreur"><?= $erreurs["delit"] ?></span>
                </div>
            </section>
            <br/>
            <section>
                <label>&nbsp;</label>
                <div>
                    <?php echo $btnRetourValiderAnnuler ?>
                </div>
            </section>
        </form>
    </body>
</html>
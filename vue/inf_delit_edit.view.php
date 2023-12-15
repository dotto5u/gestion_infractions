<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title><?php echo $titre ?></title>
        <link type="text/css" rel="stylesheet" href="../vue/style/style.css">
    </head>
    <body>
        <?php include_once "../vue/header.php" ?>
        <section>
            <label></label>
            <h1><?php echo $titre ?></h1>     
        </section>

        <form action="inf_delit_edit.php" method="post">
            <section>
                <label>délits :</label>
                <div>
                    <select name="listeDelit">
                        <?php
                            foreach($libelles as $cle => $libelle)
                            {
                                echo "<option value='$cle'>".$libelle."</option>";
                            }
                        ?>
                    </select>
                    <br>
                    <span class="erreur"><?= $erreurs["delit"] ?></span>
                <div>
            </section>

            <section>
                <label>&nbsp;</label>
                <div>
                    <input type="submit" id="valider" name="Valider" value="Valider" />
                    &emsp;
                    <input type="submit" id="annuler" name="Annuler" value="Annuler" />
                </div>
            </section>
        </form>
    </body>
</html>
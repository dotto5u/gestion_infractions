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

        <form action="inf_liste.php" method="post">
            <section>
                <label></label>
                <table id="table_inf">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Numéro</th>
                            <th>Le</th>
                            <th>Véhicule</th>
                            <th>Conducteur</th>
                            <th>Montant</th>
                            <?php echo $balisesModifSuppr ?>
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
                    <?php echo $btnAjouterInfraction ?>
                </table>
            </section>
            <br>
            <?php echo $info_json ?>
        </form>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title><?php echo $titre ?></title>
        <link type="text/css" rel="stylesheet" href="../vue/style/style.css">
    </head>
    <body>
        <section>
            <label></label>
            <h1><?php echo $titre ?></h1>     
        </section>

        <form action="login.php" method="post">
            <section>
                <label for="id">Identifiant :</label>
                <div>
                    <input id="login" type="text" name="login" value="<?= $identifiants['login'] ?>">      
                <div>
            </section>

            <section>
                <label for="mdp">Mot de passe :</label>
                <div>
                    <input id="mdp" type="password" name="mdp" value="<?= $identifiants['mdp'] ?>">
                    <br>
                    <label class="erreur"><?= $erreur ?></label>      
                <div>
            </section>

            <section>
                <label></label>
                <div>
                    <input type="submit" name="connexion" value="Connexion" class="ajout">      
                <div>
            </section>
        </form>
    </body>
</html>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <?php require './vues/components/header.php'; ?>

    <form action="./resetMdpAction" method="POST">
        <h1>Réinitialiser le mot de passe</h1>
        <br>
        <label for="oldMdp">Ancien mot de passe<span class="rouge">*</span> : </label>
        <input type="password" name="oldMdp" id="oldMdp" require>
        <br>
        <br>
        <label for="newMdp">Nouveau mot de passe<span class="rouge">*</span> : </label>
        <input type="password" name="newMdp" id="newMdp" require>
        <br>
        <br>
        <label for="newMdp2">Confirmer le nouveau mot de passe<span class="rouge">*</span> : </label>
        <input type="password" name="newMdp2" id="newMdp2" require>
        <br>
        <br>
        <input type="submit" value="Réinitialiser">
    </form>

    <?php 
    if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
            case "1":
                echo "<p class='error'>Veuillez remplir tout les champs</p>";
                break;
            case "2":
                echo "<p class='error'>Le nouveau mot de passe et la confirmation du mot de passe étaient différents</p>";
                break;
            case "3":
                echo "<p class='error'>Le mot de passe actuel n'est pas correct</p>";
                break;
        }
    }
    ?>

</body>

</html>
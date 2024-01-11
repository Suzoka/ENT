<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil administrateur</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/admin.css">
    <link rel="icon" type="image/png" href="../img/logo-UGE.png">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <div class="bodyadmin">
        <h1>Espace administrateur</h1>
        <a href="./gestionUsers" class="adminlink">
            <div class="adminbtn">
                <h2>Gestion des utilisateurs</h2>
            </div>
        </a>
        <a href="./gestionGroupes" class="adminlink">
            <div class="adminbtn">
                <h2>Gestion des groupes</h2>
            </div>
        </a>
        <a href="./gestionClasses" class="adminlink">
            <div class="adminbtn">
                <h2>Gestion des evaluations</h2>
            </div>
        </a>
        <a href="./messagerie" class="adminlink">
            <div class="adminbtn">
                <h2>Messagerie</h2>
            </div>
        </a>
    </div>
</body>

</html>
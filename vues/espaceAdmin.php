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
        <a href="./gestionUsers" class="adminlink">
            <div class="adminbtn">
                <p>gestion des utilisateurs</p>
            </div>
        </a>
        <a href="./gestionGroupes" class="adminlink">
            <div class="adminbtn">
                <p>gestion des groupes</p>
            </div>
        </a>
        <a href="./gestionClasses" class="adminlink">
            <div class="adminbtn">
                <p>gestion des evaluations</p>
            </div>
        </a>
        <a href="./messagerie" class="adminlink">
            <div class="adminbtn">
                <p>messagerie</p>
            </div>
        </a>
    </div>
</body>

</html>
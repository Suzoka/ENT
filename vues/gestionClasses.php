<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des classes</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <div class="title">
        <button class="back">
            <p class="sr-only">Retour en arrière</p>
        </button>
        <div class="filAriane"></div>
        <h1>Sélection de la classe</h1>
    </div>
    <div class="dynamic">

    </div>

    <div class="popup">
        <h2>Titre</h2>
        <div class="formulaire">

        </div>
        <div class="boutons">
            <button class="cancelAll">Tout annuler</button>
        </div>
    </div>

    <script src="../scripts/gestionClasses.js"></script>
</body>

</html>
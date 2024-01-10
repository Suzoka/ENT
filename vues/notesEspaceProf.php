<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des notes</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/notesEspaceProf.css">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <section class="grid">
        <div class="panel customScroll" id="prof<?php echo $_SESSION["login"]; ?>">
            <div class="title">
                <button class="back">
                    <p class="sr-only">Retour en arrière</p>
                </button>
                <div class="filAriane"></div>
                <h1>Sélection de la classe</h1>
            </div>
            <div class="dynamic">
            </div>
        </div>
        <?php require './vues/components/sidebar.php'; ?>
    </section>
    <div class="popup">
        <h2>Voici les modifications que vous avez effectuées</h2>
        <div class="modifs">

        </div>
        <div class="buttons">
            <button class="cancelAll">Tout annuler</button>
            <button class="confirm">Confirmer</button>
        </div>
    </div>

    <div class="newDevoirForm">
        <div class="contenuFormulaire">
            <h2>Création d'un nouveau devoir</h2>
            <div><label for="nom">Nom du devoir : </label><input type="text" id="nom"></div>
            <div><label for="coef">Coefficient : </label><input type="number" id="coef"></div>
            <div><button class="cancel">Annuler</button><button class="confirmNewDevoir">Créer le devoir</button></div>
        </div>
    </div>

    <script src="../scripts/notesEspaceProf.js" defer></script>
</body>

</html>
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

    <script src="../scripts/notesEspaceProf.js" defer></script>
</body>

</html>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes notes</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/notes.css">
</head>

<body>
    <?php require './vues/components/header.php'; ?>

    <h1>Mes notes</h1>
    <?php
    // echo getMoyenneMod(1, 1);
    foreach ($competences as $value) { ?>
        <div class="competence">
            <h2>
                <?php echo $value["nom_competence"]; ?>
            </h2>
            <p class="moyenneComp">
                <?php echo getMoyenneComp($value["id_competence"], $_SESSION["login"]) ?>
            </p>
            <div class="notes">
                <?php 
                
                ?>
            </div>
        </div>
    <?php } ?>
</body>

</html>
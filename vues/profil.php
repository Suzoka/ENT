<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil étudiants</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/profil.css">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <section class="grid">
        <div class="profil customScroll">
            <div class="character">
                <img src="<?php echo $image ?>" alt="">
                <div class="youu">
                    <div class="text">
                        <h1><?php echo $identite ?></h1>
                        <p><?php echo "num etud" ?></p>
                    </div>
<<<<<<< Updated upstream
                    <p><?php echo $classes; ?></p>
=======
                    <p class="class"><?php echo $classes; ?></p>
>>>>>>> Stashed changes
                    <p><?php echo "statut quand je saurrais comment l'appeller mdrrr" ?></p>
                </div>
            </div>
            <a class="bouton" href="#">telecharger mes certificat de scolaritée</a>
            <div class="contenu">
                <div class="zone moi">
                    <h2>A propos de moi</h2>
                    <p><?php echo "texte a recupe avec php j'imagine" ?></p>
                    <a class="bouton" href="#">telecharger mon Cv</a>
                </div>
                <div class="zone projets ">
                    <h2>mes projets</h2>
                    <div class="projets-list customScroll">
                        <div class="projet"></div>
                        <div class="projet"></div>
                        <div class="projet"></div>
                        <div class="projet"></div>
                        <div class="projet"></div>
                        <!-- <?php echo "" ?> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
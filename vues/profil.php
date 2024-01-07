<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de
        <?php echo getIdentite($targetedUser); ?>
    </title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/profil.css">
</head>

<body <?php if (!isset($_SESSION["login"])) {
    echo "class='fullscreen'";
} ?>>
    <?php require './vues/components/header.php'; ?>
    <?php if (isset($_SESSION["login"])) { ?>
        <section class="grid">
        <?php } ?>
        <div class="profil customScroll">
            <div class="character">
                <img src="<?php echo getImage($targetedUser); ?>" alt="">
                <div class="youu">
                    <div class="text">
                        <h1>
                            <?php echo getIdentite($targetedUser); ?>&nbsp;<span><?php echo $userInfo["numEtud"]; ?></span>
                        </h1>
                    </div>
                    <p>
                        <?php echo getClasses($targetedUser); ?>
                    </p>
                    <p>
                        <?php echo $userInfo["statut"] ?>
                    </p>
                </div>
                <?php if (isset($_SESSION["login"]) && $targetedUser == $_SESSION["login"]) { ?>
                    <a class="edit" id="edit" href="./editProfil">
                        <p class="sr-only">Modifier mon profil</p>
                    </a>
                <?php } ?>
            </div>
            <?php if (isset($_SESSION["login"]) && $targetedUser == $_SESSION["login"]) { ?>
                <a class="bouton" href="#">Telecharger mes certificat de scolaritée</a>
            <?php } ?>
            <div class="contenu">
                <div class="zone moi customScroll">
                    <h2>À propos de moi</h2>
                    <p>
                        <?php echo $userInfo["description"] == null ? "<i>L'utilisateur n'a mis aucune description.</i>" : $userInfo["description"]; ?>
                    </p>
                    <?php if (file_exists("./docs/cv/cv" . $userInfo["id"] . ".pdf")) { ?><a class="bouton" href="../scripts/download.php?file=<?php echo "cv".$userInfo["id"] ?>" download="CV <?php echo getIdentite($targetedUser); ?>.pdf">Telecharger le Cv</a><?php } ?>
                </div>
                <div class="zone projets ">
                    <div class="headerProjet">
                        <h2>Mes projets</h2>
                        <?php if (isset($_SESSION["login"]) && $targetedUser == $_SESSION["login"]) { ?>
                            <button class="newProjet" id="newProjet">
                                <p class="sr-only">Ajouter un nouveau projet</p>
                            </button>
                        <?php } ?>
                    </div>
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
        <?php if (isset($_SESSION["login"])) { ?>
        </section>
    <?php } ?>
</body>

</html>
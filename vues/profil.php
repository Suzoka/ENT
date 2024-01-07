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
                            <?php echo getIdentite($targetedUser); ?>&nbsp;<span>
                                <?php echo $userInfo["numEtud"]; ?>
                            </span>
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
                <div class="boutons selfBoutons">
                    <a class="bouton" href="../scripts/downloadCS"
                        download="Certificat de scolaritée <?php echo $identite; ?>.txt">Telecharger mon certificat de
                        scolaritée</a>
                    <a class="bouton" href="./resetPassword">Réinitialiser mon mot de passe</a>
                </div>
            <?php } ?>
            <div class="contenu">
                <div class="zone moi customScroll">
                    <h2>À propos de moi</h2>
                    <p>
                        <?php echo $userInfo["description"] == null ? "<i>L'utilisateur n'a mis aucune description.</i>" : $userInfo["description"]; ?>
                    </p>
                    <?php if (file_exists("./docs/cv/cv" . $userInfo["id"] . ".pdf")) { ?><a class="bouton"
                            href="../scripts/downloadCV.php?file=<?php echo "cv" . $userInfo["id"] ?>"
                            download="CV <?php echo getIdentite($targetedUser); ?>.pdf">Telecharger le Cv</a>
                    <?php } ?>
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
                        <?php
                        $projets = getProjets($targetedUser);
                        if ($projets->rowCount() == 0) {
                            echo "<br><i>L'utilisateur n'a pas encore ajouté de projet.</i>";
                        } else {
                            foreach ($projets->fetchAll(PDO::FETCH_ASSOC) as $projet) { ?>
                                <div class="projet">
                                    <img src="../img/projets/projet<?php echo $projet["id_projet"]; ?>.png" alt="">
                                    <div class="textes">
                                        <h3>
                                            <?php echo $projet["nom_projet"]; ?>
                                        </h3>
                                        <p>
                                            <?php echo getThemes($projet["id_projet"]) ?>
                                        </p>
                                    </div>
                                    <a href="<?php echo $projet["lien_projet"]; ?>" target="_blank" class="bouton">Voir le
                                        projet</a>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($_SESSION["login"])) { ?>
        </section>
    <?php } ?>

    <?php
    if (isset($_SESSION["login"]) && $targetedUser == $_SESSION["login"]) { ?>
        <div class="newProjetForm">
            <form class="contenuFormulaire" method="POST" action="./createProject" enctype="multipart/form-data">
                <h2>Ajout d'un nouveau projet</h2>
                <br>
                <div><label for="nom">Nom du projet : </label><input type="text" id="nom" name="nom" require></div>
                <br>
                <div><label for="lien">Lien du projet : </label><input type="text" id="lien" name="lien" require></div>
                <br>
                <div><label for="theme">Thème du projet : </label><input type="text" id="themes" name="themes"></div>
                <p>Veuillez séparer les différents thèmes par un ";"</p>
                <br>
                <div><label for="picture">Image du projet : </label><input type="file" id="picture" name="picture"
                        accept="image/png, image/jpeg" require></div>
                <br>
                <div class="boutons"><button class="cancel">Annuler</button><input type="submit" class="confirmNewProjet"
                        value="Créer le projet"></div>
            </form>
        </div>

        <script src="../scripts/profil.js" defer></script>
    <?php } ?>
</body>

</html>
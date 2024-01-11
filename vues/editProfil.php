<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification du Profil</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/profil.css">
    <link rel="icon" type="image/png" href="../img/logo-UGE.png">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <!-- page profil, mais avec des inputs, pour pouvoir modifier les informations -->
    <form action="./updateProfil" method="POST" enctype="multipart/form-data">
        <section class="grid">
            <div class="profil customScroll">
                <div class="character">
                    <div class="img">
                        <img src="<?php echo $image; ?>" alt="">
                        <label for="image"><span class="sr-only">Modifier la photo de profil</span></label>
                        <input type="file" name="image" id="image" accept="image/png, image/jpeg">
                    </div>
                    <div class="youu">
                        <div class="text">
                            <h1>
                                <?php echo $identite; ?>&nbsp;<span>
                                    <?php echo $userInfo["numEtud"]; ?>
                                </span>
                            </h1>
                        </div>
                        <p>
                            <?php echo $classes; ?>
                        </p>
                        <input type="text" name="statut" value="<?php echo $userInfo["statut"] ?>">
                    </div>
                    <input type="submit" value="Enregistrer">
                </div>
                <label for="CV">Uploader mon CV</label>
                <input type="file" name="CV" id="CV" accept="application/pdf">
                <div class="contenu">
                    <div class="zone moi customScroll">
                        <h2>À propos de moi</h2>
                        <textarea placeholder="Description"
                            name="description"><?php echo $userInfo["description"]; ?></textarea>
                    </div>
                    <div class="zone projets ">
                        <div class="headerProjet">
                            <h2>Mes projets</h2>
                        </div>
                        <div class="projets-list customScroll">
                            <?php
                            //Afficher tous les projets de l'utilisateur
                            $projets = getProjets($_SESSION["login"]);
                            //Si l'utilisateur n'a pas de projet, afficher un message
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
            <?php require './vues/components/sidebar.php'; ?>
        </section>
    </form>
</body>

</html>
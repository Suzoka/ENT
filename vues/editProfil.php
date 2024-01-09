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
                <input type="file" name="CV" id="CV" accept="application/pdf">
                <div class="contenu">
                    <div class="zone moi customScroll">
                        <h2>À propos de moi</h2>
                        <textarea placeholder="Description" name="description"><?php echo $userInfo["description"]; ?></textarea>
                    </div>
                    <div class="zone projets ">
                        <h2>Mes projets</h2>
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
    </form>
</body>

</html>
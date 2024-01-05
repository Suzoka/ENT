<!-- //TODO : Afficher les coefficients -->

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
    <section class="grid">
        <div class="bulletin customScroll">
            <h1>Mes notes</h1>
            <?php
            foreach ($competences as $comp) { ?>
                <div class="competence">
                    <div class="title">
                        <h2>
                            <?php echo $comp["nom_competence"]; ?>
                        </h2>
                        <p class="moyenneComp">
                            <?php echo formatNote(getMoyenneComp($comp["id_competence"], $_SESSION["login"])); ?>
                        </p>
                    </div>
                    <div class="notes">
                        <?php
                        foreach (getAllModsOfComp($comp["id_competence"]) as $mod) { ?>
                            <div class="title"><button class="developMod" id="<?php echo $comp["id_competence"].$mod["id_module"] ?>">
                                    <h3><?php echo $mod["nom_module"] ?></h3>
                                </button>
                                <p class="moyenneMod">
                                <?php echo formatNote(getMoyenneMod($mod["id_module"], $_SESSION["login"])); ?>
                                </p>
                            </div>
                            <div class="devoirs data<?php echo $comp["id_competence"].$mod["id_module"] ?>">
                                <?php 
                                foreach (getAllDevoirsOfMod($mod["id_module"], $_SESSION["login"])->fetchAll(PDO::FETCH_ASSOC) as $devoir) { ?>
                                <div class="title noteDevoir"><div class="devoirInfos">
                                    <h4><?php echo $devoir["nom_devoir"]; ?></h4><p class="coef">coefficient <?php echo $devoir["coef_devoir"]; ?></p>
                                </div><p><?php echo formatNote($devoir["valeur"]); ?></p></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <script src="../scripts/notes.js" defer></script>
</body>

</html>
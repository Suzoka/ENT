<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil étudiants</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/espaceEtudiant.css">
    <link rel="stylesheet" href="../style/edt-espaceEtudiant.css">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <section class="grid">
        <div class="panel customScroll">
            <?php require './vues/components/accedt.php'; ?>
            <div class="pnl vsclr">
                <p>vie scolaire</p>
            </div>
            <div class="pnl notes">
                <a href="./notes">note</a>
            </div>
        </div>
        <?php require './vues/components/sidebar.php'; ?>
    </section>
</body>

</html>
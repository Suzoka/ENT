<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil professeurs</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/espaceProf.css">
    <link rel="stylesheet" href="../style/edt-espaceEtudiant.css">
    <link rel="icon" type="image/png" href="../img/logo-UGE.png">
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
                <h3><a href="./notes">Entrer des notes</a></h3>
                <div class="classes">
                    liste des classes
                </div>
            </div>
        </div>
        <?php require './vues/components/sidebar.php'; ?>
    </section>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes messages</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/messagerie.css">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <br><br><br>
    messagerie
    <form action="./sendMessage?to=<?php echo $to;?>" method="POST">
        <input type="text" name="message" id="message" required>
        <input type="submit" value="Envoyer">
    </form>
</body>

</html>
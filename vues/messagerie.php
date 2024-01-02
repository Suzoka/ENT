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
    <section class="gridMessages">
        <div class="flexMessages">
            <div class="receiverInfos"><div><img src="<?php echo $imageReceiver; ?>" alt=""><h1><?php echo $identiteReceiver ?></h1></div><p><?php echo $classesReceiver; ?></p></div>
            <div class="conversation">
                <div class="messages customScroll">
                    <?php
                    foreach ($conversation as $message) {
                        if ($message["ext_id_sender"] == $_SESSION["login"]) {
                            echo "<div class='message moi' style='--img: url($image)'>";
                        } else {
                            echo "<div class='message' style='--img: url($imageReceiver);'>";
                        }
                        echo "<p>" . $message["message"] . "</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
            <form action="./sendMessage?to=<?php echo $to;?>" method="POST">
                <input type="text" name="message" id="message" placeholder="Envoyer un chat" required>
                <input type="submit" value="Envoyer">
            </form>
        </div>
        <div class="contacts"></div>
    </section>
</body>

</html>
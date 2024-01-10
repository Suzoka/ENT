<!-- // TODO : Messages non lus (ajouter un booléen à la DB et compter le nombre de non lus, à l'ouverture de la conversation changer le booléen) -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes messages</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/messagerie.css">
    <link rel="icon" type="image/png" href="../img/logo-UGE.png">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <section class="grid">
        <div class="flexMessages">
            <div class="receiverInfos">
                <a href="./profil?user=<?php echo $to; ?>">
                    <!-- Si une conversation est ouverte, afficher le profil de l'interlocuteur -->
                    <?php if ($to != null) { ?><img src="<?php echo $imageReceiver; ?>" alt="">
                    <?php } ?>
                    <h1>
                        <?php echo $identiteReceiver ?>
                    </h1>
                </a>
                <p>
                    <?php echo $classesReceiver; ?>
                </p>
            </div>
            <div class="conversation">
                <div class="messages customScroll">
                    <?php
                    // Si aucune conversation n'est ouverte, afficher un message de prévention
                    if ($to == null) {
                        echo "<p class='newConv'>Pour créer une conversation, recherchez votre destinataire dans la barre à droite</p>";
                    }
                    //Afficher tout les messages de la conversation
                    foreach ($conversation as $i => $message) {
                        //Si le message vient de moi, mettre le message à droite
                        if ($message["ext_id_sender"] == $_SESSION["login"]) {
                            //Si c'est le premier message de l'utilisateur dans un enchainement de messages, ajouter la classe first
                            if ($i == 0 || $conversation[$i - 1]["ext_id_sender"] != $_SESSION["login"]) {
                                echo "<div class='message moi first' style='--img: url($image)'>";
                            } else {
                                echo "<div class='message moi' style='--img: url($image)'>";
                            }
                            //Si le message vient de l'interlocuteur, mettre le message à gauche
                        } else {
                            //Si c'est le premier message de l'utilisateur dans un enchainement de messages, ajouter la classe first
                            if ($i == 0 || $conversation[$i - 1]["ext_id_sender"] != $to) {
                                echo "<div class='message first' style='--img: url($imageReceiver);'>";
                            } else {
                                echo "<div class='message' style='--img: url($imageReceiver);'>";
                            }
                        }
                        //Afficher le message
                        echo "<p>" . $message["message"] . "</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
            <form action="./sendMessage?to=<?php echo $to; ?>" method="POST" class="msg">
                <label for="message" class="sr-only">Envoyer un chat</label>
                <textarea name="message" id="message" placeholder="Envoyer un chat" required <?php if ($to == null) {
                    //Si aucune conversation n'est ouverte, désactiver la zone de texte
                    echo 'disabled="disabled"';
                } ?>></textarea>
                <label for="send" class="sr-only">Envoyer</label>
                <input type="submit" value="Envoyer" id="send" <?php if ($to == null) {
                    //Si aucune conversation n'est ouverte, désactiver le bouton d'envoi
                    echo 'disabled="disabled"';
                } ?>>
            </form>
        </div>
        <div class="side">
            <div class="contacts customScroll">
                <form class="recherche">
                    <p class="erreur">Ceci est un message d'erreur</p>
                    <label for="recherche" class="sr-only">Rechercher/créer une conversation</label>
                    <input type="search" list="usr" name="recherche" placeholder="Rechercher/créer une conversation"
                        id="recherche">
                </form>
                <datalist id="usr" class="usr"></datalist>
                <?php
                //Afficher toutes les conversations en cours de l'utilisateur
                foreach ($historique as $contact) { ?>
                    <a href="./messagerie?to=<?php echo $contact["other_user_id"]; ?>" class="contact 
                    <?php
                    //Mettre en avant le contact actuel
                    if (isset($to)) {
                        if ($contact["other_user_id"] == $to) {
                            echo "current\" aria-current=\"page";
                        } ?>
                        ">
                            <img src=" <?php echo getImage($contact["other_user_id"]); ?>" alt="">
                            <div>
                                <h2>
                                    <?php echo getIdentite($contact["other_user_id"]); ?>
                                </h2>
                                <p>
                                    <?php echo $contact["message"]; ?>
                                </p>
                            </div>
                        </a>
                    <?php }
                } ?>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js"></script>
    <script src="../scripts/messagerie.js" defer></script>
</body>

</html>
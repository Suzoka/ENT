<div class="contacts customScroll">
    <form class="recherche">
        <p class="erreur">Ceci est un message d'erreur</p>
        <label for="recherche" class="sr-only">Rechercher/créer une conversation</label>
        <input type="search" list="usr" name="recherche" placeholder="Rechercher/créer une conversation" id="recherche">
    </form>
    <datalist id="usr" class="usr"></datalist>
    <?php
    //Afficher toutes les conversations en cours de l'utilisateur
    foreach ($historique as $contact) { ?>
        <a href="./messagerie?to=<?php echo $contact["other_user_id"]; ?>"
            class="contact <?php if (isset($to)) {
                if ($contact["other_user_id"] == $to) {
                    echo "current\" aria-current=\"page";
                }
            } ?>">
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
    <?php } ?>
</div>
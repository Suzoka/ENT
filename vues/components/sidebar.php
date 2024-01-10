<div class="right">
    <div class="racc">
        <!-- <div class="pnl mails-rcnt"></div> -->
        <!-- <div class="pnl app"></div> -->
    </div>
    <div class="pnl chat">
        <h3><a href="./messagerie">messagerie</a></h3>
        <div class="ctct">
            <div class="contacts customScroll">
                <form class="recherche">
                    <p class="erreur">Ceci est un message d'erreur</p>
                    <input type="search" list="usr" name="recherche" placeholder="Rechercher/créer une conversation">
                </form>
                <datalist id="usr" class="usr"></datalist>
                <?php
                $historique = getAllConversation($_SESSION["login"])->fetchAll(PDO::FETCH_ASSOC);
                

                foreach ($historique as $contact) { ?>
                    <a href="./messagerie?to=<?php echo $contact["other_user_id"]; ?>"
                        class="contact <?php if ($contact["other_user_id"] == $to) {
                            echo "current\" aria-current=\"page";
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
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js"></script>
<script src="../scripts/messagerie.js" defer></script>

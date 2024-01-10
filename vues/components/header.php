<!-- Code du header -->
<header>
    <nav>
        <a href="./accueil" class="logo">
            <img src="../img/logo-UGE.png" alt="accueil">
            <div class="text">
                <p>Intranet étudiant</p>
                <p>Université Gustave Eiffel</p>
            </div>
        </a>
        <!-- Si l'utilisateur est connecté, on affiche le lien vers son profil, sa classe et le bouton de déconnexion -->
        <?php if (isset($_SESSION['login'])) { ?>
            <a href="./profil" class="user">
                <img src="<?php echo $image ?>" alt="">
                <div class="text">
                    <p>
                        <?php echo $identite ?>
                    </p>
                    <p>
                        <?php echo $classes; ?>
                    </p>
                </div>
            </a>
            <a href="./deconnexion" class="logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="43" height="38" viewBox="0 0 43 38" fill="none">
                    <path
                        d="M31.9167 8.57967L28.9792 11.5172L34.3543 16.913H13.1667V21.0797H34.3543L28.9792 26.4547L31.9167 29.413L42.3334 18.9963M4.83342 4.413H21.5001V0.246338H4.83342C2.54175 0.246338 0.666748 2.12134 0.666748 4.413V33.5797C0.666748 35.8713 2.54175 37.7463 4.83342 37.7463H21.5001V33.5797H4.83342V4.413Z"
                        fill="white" />
                </svg>
                <p class="sr-only">Déconnection</p>
            </a>
        <?php } ?>
    </nav>
</header>
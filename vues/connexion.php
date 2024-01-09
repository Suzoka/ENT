<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/connexion.css">
    <link rel="icon" type="image/png" href="../img/logo-UGE.png">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <h1>Connectez vous !</h1>

    <div class="flexbox">
        <div class="card">
            <h2>Vous êtes étudiant-e ?</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque placerat, felis eu imperdiet
                ultrices, sapien dolor placerat nunc, nec laoreet magna enim id ex. Morbi et euismod elit. In at lectus
                nec tortor hendrerit rutrum in sit amet sapien. Vivamus id erat quis erat elementum blandit et non
                nulla. Vestibulum id ex. Morbi et euismod elit. In at lectus nec tortor hendrerit rutrum in sit amet
                sapien. Vivamus id erat quis erat elementum blandit et non nulla. Vestibulum </p>
            <button class="connexionButton displayPopup">Se connecter</button>
        </div>
        <div class="card">
            <h2>Vous êtes professeur-e ?</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque placerat, felis eu imperdiet
                ultrices, sapien dolor placerat nunc, nec laoreet magna enim id ex. Morbi et euismod elit. In at lectus
                nec tortor hendrerit rutrum in sit amet sapien. Vivamus id erat quis erat elementum blandit et non
                nulla. Vestibulum id ex. Morbi et euismod elit. In at lectus nec tortor hendrerit rutrum in sit amet
                sapien. Vivamus id erat quis erat elementum blandit et non nulla. Vestibulum </p>
            <button class="connexionButton displayPopup">Se connecter</button>
        </div>
    </div>
    <footer><button class="displayPopup">Connexion espace administrateur</button></footer>

    <div id="popup0" class="formulaire">
        <h2>Authentification espace étudiant</h2>
        <p>Renseignez votre identifiant et votre mot de passe</p>
        <?php
        if (isset($_GET["error"])) {
            echo "<p class='erreur'> Erreur : ";
            if ($_GET["error"] == "usr!0") {
                echo "Il se peut que votre session ai expirée";
            } else if ($_GET["error"] == "usr!1") {
                echo "Identifiant ou mot de passe incorrect";
            } else {
                echo "Erreur inconnue";
            }
            echo "</p>";
        }
        ?>
        <form action="./checkConnectionUsr" method="POST">
            <div class="champ">
                <input type="text" name="identifiant" id="identifiantUsr" required>
                <label for="identifiantUsr">Identifiant<span class="rouge">*</span></label>
            </div>
            <br>
            <div class="champ">
                <input type="password" name="mdp" id="mdpUsr" required>
                <label for="mdpUsr">Mot de passe<span class="rouge">*</span></label>
            </div>

            <input type="submit" value="Se connecter" class="connexionButton">
        </form>
        <button class="backButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="49" height="49" viewBox="0 0 49 49" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.78937 23.7344L14.8225 29.7675C15.0482 29.9778 15.2292 30.2314 15.3547 30.5131C15.4802 30.7949 15.5477 31.099 15.5532 31.4074C15.5586 31.7158 15.5019 32.0222 15.3864 32.3082C15.2709 32.5942 15.0989 32.854 14.8808 33.0721C14.6627 33.2902 14.4029 33.4621 14.1169 33.5776C13.8309 33.6932 13.5246 33.7499 13.2161 33.7445C12.9077 33.739 12.6036 33.6715 12.3219 33.546C12.0401 33.4204 11.7865 33.2394 11.5763 33.0138L1.62312 23.0606L0 21.4375L1.62312 19.8144L11.5763 9.86127C12.0117 9.45555 12.5876 9.23467 13.1826 9.24517C13.7776 9.25567 14.3454 9.49672 14.7662 9.91755C15.187 10.3384 15.4281 10.9061 15.4386 11.5012C15.4491 12.0962 15.2282 12.6721 14.8225 13.1075L8.78937 19.1406H39.0469C41.6866 19.1406 44.2182 20.1893 46.0848 22.0558C47.9514 23.9224 49 26.454 49 29.0938C49 31.7335 47.9514 34.2651 46.0848 36.1317C44.2182 37.9983 41.6866 39.0469 39.0469 39.0469H32.9219C32.3127 39.0469 31.7285 38.8049 31.2977 38.3742C30.867 37.9434 30.625 37.3592 30.625 36.75C30.625 36.1408 30.867 35.5566 31.2977 35.1259C31.7285 34.6951 32.3127 34.4531 32.9219 34.4531H39.0469C40.4683 34.4531 41.8314 33.8885 42.8365 32.8834C43.8416 31.8783 44.4062 30.5152 44.4062 29.0938C44.4062 27.6724 43.8416 26.3092 42.8365 25.3041C41.8314 24.299 40.4683 23.7344 39.0469 23.7344H8.78937Z"
                    fill="#1B4965" />
            </svg>
            <p class="sr-only">Button de retour</p>
        </button>
    </div>

    <div id="popup1" class="formulaire">
        <h2>Authentification espace professeur</h2>
        <p>Renseignez votre identifiant et votre mot de passe</p>
        <?php
        if (isset($_GET["error"])) {
            echo "<p class='erreur'> Erreur : ";
            if ($_GET["error"] == "prof!0") {
                echo "Il se peut que votre session ai expirée";
            } else if ($_GET["error"] == "prof!1") {
                echo "Identifiant ou mot de passe incorrect";
            } else {
                echo "Erreur inconnue";
            }
            echo "</p>";
        }
        ?>
        <form action="./checkConnectionProf" method="POST">
            <div class="champ">
                <input type="text" name="identifiant" id="identifiantProf" required>
                <label for="identifiantProf" >Identifiant<span class="rouge">*</span></label>
            </div>
            <br>
            <div class="champ">
                <input type="password" name="mdp" id="mdpProf" required>
                <label for="mdpProf">Mot de passe<span class="rouge">*</span></label>
            </div>

            <input type="submit" value="Se connecter" class="connexionButton">
        </form>
        <button class="backButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="49" height="49" viewBox="0 0 49 49" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.78937 23.7344L14.8225 29.7675C15.0482 29.9778 15.2292 30.2314 15.3547 30.5131C15.4802 30.7949 15.5477 31.099 15.5532 31.4074C15.5586 31.7158 15.5019 32.0222 15.3864 32.3082C15.2709 32.5942 15.0989 32.854 14.8808 33.0721C14.6627 33.2902 14.4029 33.4621 14.1169 33.5776C13.8309 33.6932 13.5246 33.7499 13.2161 33.7445C12.9077 33.739 12.6036 33.6715 12.3219 33.546C12.0401 33.4204 11.7865 33.2394 11.5763 33.0138L1.62312 23.0606L0 21.4375L1.62312 19.8144L11.5763 9.86127C12.0117 9.45555 12.5876 9.23467 13.1826 9.24517C13.7776 9.25567 14.3454 9.49672 14.7662 9.91755C15.187 10.3384 15.4281 10.9061 15.4386 11.5012C15.4491 12.0962 15.2282 12.6721 14.8225 13.1075L8.78937 19.1406H39.0469C41.6866 19.1406 44.2182 20.1893 46.0848 22.0558C47.9514 23.9224 49 26.454 49 29.0938C49 31.7335 47.9514 34.2651 46.0848 36.1317C44.2182 37.9983 41.6866 39.0469 39.0469 39.0469H32.9219C32.3127 39.0469 31.7285 38.8049 31.2977 38.3742C30.867 37.9434 30.625 37.3592 30.625 36.75C30.625 36.1408 30.867 35.5566 31.2977 35.1259C31.7285 34.6951 32.3127 34.4531 32.9219 34.4531H39.0469C40.4683 34.4531 41.8314 33.8885 42.8365 32.8834C43.8416 31.8783 44.4062 30.5152 44.4062 29.0938C44.4062 27.6724 43.8416 26.3092 42.8365 25.3041C41.8314 24.299 40.4683 23.7344 39.0469 23.7344H8.78937Z"
                    fill="#1B4965" />
            </svg>
            <p class="sr-only">Button de retour</p>
        </button>
    </div>

    <div id="popup2" class="formulaire">
        <h2>Authentification espace administrateur</h2>
        <p>Renseignez votre identifiant et votre mot de passe</p>
        <?php
        if (isset($_GET["error"])) {
            echo "<p class='erreur'> Erreur : ";
            if ($_GET["error"] == "admin!0") {
                echo "Il se peut que votre session ai expirée";
            } else if ($_GET["error"] == "admin!1") {
                echo "Identifiant ou mot de passe incorrect";
            } else {
                echo "Erreur inconnue";
            }
            echo "</p>";
        }
        ?>
        <form action="./checkConnectionAdmin" method="POST">
            <div class="champ">
                <input type="text" name="identifiant" id="identifiantAdmin" required>
                <label for="identifiantAdmin">Identifiant<span class="rouge">*</span></label>
            </div>
            <br>
            <div class="champ">
                <input type="password" name="mdp" id="mdpAdmin" required>
                <label for="mdpAdmin">Mot de passe<span class="rouge">*</span></label>
            </div>

            <input type="submit" value="Se connecter" class="connexionButton">
        </form>
        <button class="backButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="49" height="49" viewBox="0 0 49 49" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.78937 23.7344L14.8225 29.7675C15.0482 29.9778 15.2292 30.2314 15.3547 30.5131C15.4802 30.7949 15.5477 31.099 15.5532 31.4074C15.5586 31.7158 15.5019 32.0222 15.3864 32.3082C15.2709 32.5942 15.0989 32.854 14.8808 33.0721C14.6627 33.2902 14.4029 33.4621 14.1169 33.5776C13.8309 33.6932 13.5246 33.7499 13.2161 33.7445C12.9077 33.739 12.6036 33.6715 12.3219 33.546C12.0401 33.4204 11.7865 33.2394 11.5763 33.0138L1.62312 23.0606L0 21.4375L1.62312 19.8144L11.5763 9.86127C12.0117 9.45555 12.5876 9.23467 13.1826 9.24517C13.7776 9.25567 14.3454 9.49672 14.7662 9.91755C15.187 10.3384 15.4281 10.9061 15.4386 11.5012C15.4491 12.0962 15.2282 12.6721 14.8225 13.1075L8.78937 19.1406H39.0469C41.6866 19.1406 44.2182 20.1893 46.0848 22.0558C47.9514 23.9224 49 26.454 49 29.0938C49 31.7335 47.9514 34.2651 46.0848 36.1317C44.2182 37.9983 41.6866 39.0469 39.0469 39.0469H32.9219C32.3127 39.0469 31.7285 38.8049 31.2977 38.3742C30.867 37.9434 30.625 37.3592 30.625 36.75C30.625 36.1408 30.867 35.5566 31.2977 35.1259C31.7285 34.6951 32.3127 34.4531 32.9219 34.4531H39.0469C40.4683 34.4531 41.8314 33.8885 42.8365 32.8834C43.8416 31.8783 44.4062 30.5152 44.4062 29.0938C44.4062 27.6724 43.8416 26.3092 42.8365 25.3041C41.8314 24.299 40.4683 23.7344 39.0469 23.7344H8.78937Z"
                    fill="#1B4965" />
            </svg>
            <p class="sr-only">Button de retour</p>
        </button>
    </div>

    <script src="../scripts/connexion.js" defer></script>
</body>

</html>
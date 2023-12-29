<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/connexion.css">
</head>

<body>

    <h1>Connectez vous !</h1>

    <div class="flexbox">
        <div class="card">
            <h2>Vous êtes étudiant-e ?</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque placerat, felis eu imperdiet
                ultrices, sapien dolor placerat nunc, nec laoreet magna enim id ex. Morbi et euismod elit. In at lectus
                nec tortor hendrerit rutrum in sit amet sapien. Vivamus id erat quis erat elementum blandit et non
                nulla. Vestibulum id ex. Morbi et euismod elit. In at lectus nec tortor hendrerit rutrum in sit amet
                sapien. Vivamus id erat quis erat elementum blandit et non nulla. Vestibulum </p>
            <button>Se connecter</button>
        </div>
        <div class="card">
            <h2>Vous êtes professeur-e ?</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque placerat, felis eu imperdiet
                ultrices, sapien dolor placerat nunc, nec laoreet magna enim id ex. Morbi et euismod elit. In at lectus
                nec tortor hendrerit rutrum in sit amet sapien. Vivamus id erat quis erat elementum blandit et non
                nulla. Vestibulum id ex. Morbi et euismod elit. In at lectus nec tortor hendrerit rutrum in sit amet
                sapien. Vivamus id erat quis erat elementum blandit et non nulla. Vestibulum </p>
            <button>Se connecter</button>
        </div>
    </div>

    <div id="popup0" class="formulaire">
        <h2>Authentification espace étudiant</h2>
        <p>Renseignez votre identifiant et votre mot de passe</p>
        <form action="./checkConnectionUsr">
            <div class="champ">
                <input type="text" name="identifiant" id="identifiant" required>
                <label for="identifiant">Identifiant</label>
            </div>
            <br>
            <div class="champ">
                <input type="password" name="mdp" id="mdp" required>
                <label for="mdp">Mot de passe</label>
            </div>

            <input type="submit" value="Se connecter">
        </form>
        <button></button>
    </div>

    <div id="popup1" class="formulaire">
        <h2>Authentification espace professeur</h2>
        <p>Renseignez votre identifiant et votre mot de passe</p>
        <form action="./checkConnectionProf">
            <div class="champ">
                <input type="text" name="identifiant" id="identifiant" required>
                <label for="identifiant">Identifiant</label>
            </div>
            <br>
            <div class="champ">
                <input type="password" name="mdp" id="mdp" required>
                <label for="mdp">Mot de passe</label>
            </div>

            <input type="submit" value="Se connecter">
        </form>
        <button></button>
    </div>

    <script src="../scripts/connexion.js" defer></script>
</body>

</html>
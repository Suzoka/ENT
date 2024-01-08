<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>

<body>
    <?php require './vues/components/header.php'; ?>
    <br><br>
    <form action="./newUser" method="POST" class="newUser">
        <label for="nom">Nom<span class="rouge">*</span></label>
        <input type="text" name="nom" id="nom" required>
        <br><br>
        <label for="prenom">Prénom<span class="rouge">*</span></label>
        <input type="text" name="prenom" id="prenom" required>
        <br><br>
        <label for="dateNaissance">Date de naissance</label>
        <input type="date" name="dateNaissance" id="dateNaissance">
        <br><br>
        <label for="numEtudiant">Numéro étudiant<span class="rouge">*</span></label>
        <input type="number" name="numEtudiant" id="numEtudiant" required>
        <br><br>
        <label for="role">Rôle<span class="rouge">*</span></label>
        <select name="role" id="role" required>
            <option value="1">Étudiant</option>
            <option value="2">Professeur</option>
            <option value="3">Administrateur</option>
        </select>
        <br><br>
        <input type="submit" value="Créer le compte">
        <?php if (isset($_GET["error"])) {
            echo "<p class='erreur'>Une erreur s'est produite</p>";
        } ?>
    </form>
    <br><br><br>
    <form class="recherche">
        <p class="erreur">Ceci est un message d'erreur</p>
        <label for="user" class="sr-only">Rechercher un utilisateur</label>
        <input type="search" list="usr" name="user" id="user">
    </form>
    <datalist id="usr" class="usr"></datalist>

    <?php if (isset($_GET["user"])) { ?>
        <form action="./updateUser?id=<?php echo $_GET["user"] ?>" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Numéro étudiant</th>
                        <th>Rôle</th>
                        <th>Reset MDP</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" value="<?php echo $user["nom"]; ?>" name="nom" require>
                        </td>
                        <td>
                            <input type="text" value="<?php echo $user["prenom"]; ?>" name="prenom" require>
                        </td>
                        <td>
                            <input type="date" value="<?php echo $user["date_naissance"]; ?>" name="date">
                        </td>
                        <td>
                            <input type="number" value="<?php echo $user["numEtud"]; ?>" name="numEtud">
                        </td>
                        <td>
                            <select name="role" id="role" name="role" required>
                                <option value="1" <?php echo $user["role"] == 1 ? "selected" : ""; ?>>Étudiant</option>
                                <option value="2" <?php echo $user["role"] == 2 ? "selected" : ""; ?>>Professeur</option>
                                <option value="3" <?php echo $user["role"] == 3 ? "selected" : ""; ?>>Administrateur</option>
                            </select>
                        </td>
                        <td><a class="reset" href="./resetAdminPassword?id=<?php echo $_GET["user"]; ?>">Reset</a></td>
                        <td><a class="delete" href="./deleteUser?id=<?php echo $_GET["user"]; ?>">Supprimer</a></td>
                    </tr>
            </table>
            <div class="saveSection">
                <input type="submit" class="save" value="Enregistrer">
            </div>
        </form>
    <?php } ?>

    <script src="../scripts/gestionUsers.js" defer></script>
</body>

</html>
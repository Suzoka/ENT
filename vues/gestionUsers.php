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
    <form action="./newUser" method="POST">
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
    <?php if(isset($_GET["error"])) {echo "<p class='erreur'>Une erreur s'est produite</p>";} ?>
</form>

<script src="../scripts/gestionUsers.js" defer></script>
</body>

</html>
<?php
session_start();
include('./scripts/script.php');


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$segments = explode('/', $path);

if (!isset($segments[2])) {
    $page = '';
} else {
    $page = $segments[2];
}

switch ($page) {
    case 'connexion':
        include './vues/connexion.php';
        break;
    case 'checkConnectionUsr':
        if (isset($_POST['identifiant']) && isset($_POST['mdp'])) {
            if (checkConnectionUsr($_POST['identifiant'], $_POST['mdp'])) {
                echo "Connexion réussie";
            } else {
                header('Location: ./connexion?error=usr!1');
            }
        } else {
            header('Location: ./connexion?error=usr!0');
        }
        break;
    case 'checkConnectionProf':
        if (isset($_POST['identifiant']) && isset($_POST['mdp'])) {
            if (checkConnectionProf($_POST['identifiant'], $_POST['mdp'])) {
                echo "Connexion réussie";
            } else {
                header('Location: ./connexion?error=prof!1');
            }
        } else {
            header('Location: ./connexion?error=prof!0');
        }
        break;
    case 'accueil':
        include './vues/accueil.php';
        break;
    default:
        // TODO : if user is connected, redirect to accueil.php, else redirect to connexion.php, 404 error ?
        include './vues/connexion.php';
        break;
}
?>
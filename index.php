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

if (isset($_SESSION['login'])) {
    $image = getImage($_SESSION['login']);
    $identite = getIdentite($_SESSION['login']);
    $classes = getClasses($_SESSION['login']);
    switch ($page) {
        case 'accueil':
            include './vues/accueil.php';
            break;
        case 'deconnexion':
            deconection();
            header('Location: ./connexion');
            break;
        case 'messagerie':
            include './vues/messagerie.php';
            break;
        default:
            include './vues/accueil.php';
            break;
    }
} else {
    switch ($page) {
        case 'connexion':
            include './vues/connexion.php';
            break;
        case 'checkConnectionUsr':
            if (isset($_POST['identifiant']) && isset($_POST['mdp'])) {
                if (checkConnectionUsr($_POST['identifiant'], $_POST['mdp'])) {
                    header('Location: ./accueil');
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
                    header('Location: ./espaceProf');
                } else {
                    header('Location: ./connexion?error=prof!1');
                }
            } else {
                header('Location: ./connexion?error=prof!0');
            }
            break;
        default:
            include './vues/connexion.php';
            break;
    }
}
?>
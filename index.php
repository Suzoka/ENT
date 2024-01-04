<?php
session_start();
include ('./scripts/database.php');
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
            if (isset($_GET["to"])) {
                $to = $_GET["to"];
            } else {
                $to = getLastConversation($_SESSION["login"]);
            }
            $imageReceiver = getImage($to);
            $identiteReceiver = getIdentite($to);
            $classesReceiver = getClasses($to);
            $conversation = getCurrentConversation($_SESSION["login"], $to)->fetchAll(PDO::FETCH_ASSOC);
            $historique = getAllConversation($_SESSION["login"])->fetchAll(PDO::FETCH_ASSOC);
            include './vues/messagerie.php';
            break;
        case 'sendMessage':
            if (isset($_POST["message"]) && isset($_GET["to"])) {
                if (sendMessage($_SESSION["login"], $_GET["to"], $_POST["message"])){
                    header("Location: ./messagerie?to={$_GET["to"]}");
                } else {
                    header('Location: ./messagerie?error=true');
                }
            } else {
                header('Location: ./messagerie?error=true');
            }
            break;
        case 'notes':
            $competences = getCompetences($_SESSION["login"])->fetchAll(PDO::FETCH_ASSOC);
            include './vues/notes.php';
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
<?php
session_start();
include('./scripts/database.php');
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
    $role = getRole($_SESSION['login'])->fetchColumn();
    switch ($page) {
        case 'accueil':
        default:
            switch ($role) {
                case 1:
                default:
                    include './vues/espaceEtudiant.php';
                    break;
                case 2:
                    include './vues/espaceProf.php';
                    break;
                case 3:
                    include './vues/espaceAdmin.php';
                    break;
            }
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
            if ($to != null) {
                $imageReceiver = getImage($to);
                $identiteReceiver = getIdentite($to);
                $classesReceiver = getClasses($to);
                $conversation = getCurrentConversation($_SESSION["login"], $to)->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $identiteReceiver = "<i>Vous n'avez pas de conversation en cours, veuillez en créer une</i>";
                $classesReceiver = "";
                $conversation = array();
            }
            $historique = getAllConversation($_SESSION["login"])->fetchAll(PDO::FETCH_ASSOC);
            include './vues/messagerie.php';
            break;
        case 'sendMessage':
            if (isset($_POST["message"]) && isset($_GET["to"])) {
                if (sendMessage($_SESSION["login"], $_GET["to"], $_POST["message"])) {
                    header("Location: ./messagerie?to={$_GET["to"]}");
                } else {
                    header('Location: ./messagerie?error=true');
                }
            } else {
                header('Location: ./messagerie?error=true');
            }
            break;
        case 'notes':
            switch ($role) {
                case 1:
                default:
                    $competences = getCompetences($_SESSION["login"])->fetchAll(PDO::FETCH_ASSOC);
                    include './vues/notes.php';
                    break;
                case 2:
                    include './vues/notesEspaceProf.php';
                    break;
                case 3:
                    include './vues/notesEspaceAdmin.php';
                    break;
            }
            break;
        case 'profil':
            if (isset($_GET["user"])) {
                $targetedUser = $_GET["user"];
            } else {
                $targetedUser = $_SESSION["login"];
            }
            $userInfo = getUser($targetedUser)->fetch(PDO::FETCH_ASSOC);
            include './vues/profil.php';
            break;
        case 'editProfil':
            $userInfo = getUser($_SESSION["login"])->fetch(PDO::FETCH_ASSOC);
            include './vues/editProfil.php';
            break;
        case 'updateProfil':
            if (isset($_POST) && !empty($_POST) && isset($_FILES)) {
                updateProfil($_SESSION["login"], $_POST, $_FILES);
                header('Location: ./profil');
            } else {
                header('Location: ./editProfil');
            }
            break;
        case 'createProject':
            if (isset($_POST["nom"]) && isset($_POST["lien"]) && isset($_FILES["picture"])) {
                createProjet($_SESSION["login"], $_POST, $_FILES["picture"]);
                header('Location: ./profil');
            } else {
                header('Location: ./profil');
            }
            break;
        case 'resetPassword':
            include './vues/resetPassword.php';
            break;
        case 'resetMdpAction':
            if (isset($_POST["oldMdp"]) && isset($_POST["newMdp"]) && isset($_POST["newMdp2"])) {
                if ($_POST["newMdp"] == $_POST["newMdp2"]) {
                    if (resetMdp($_SESSION["login"], $_POST["oldMdp"], $_POST["newMdp"])) {
                        header('Location: ./profil');
                    } else {
                        header('Location: ./resetPassword?error=3');
                    }
                } else {
                    header('Location: ./resetPassword?error=2');
                }
            } else {
                header('Location: ./resetPassword?error=1');
            }
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
                    header('Location: ./accueil');
                } else {
                    header('Location: ./connexion?error=prof!1');
                }
            } else {
                header('Location: ./connexion?error=prof!0');
            }
            break;
        case 'profil':
            if (isset($_GET["user"])) {
                $targetedUser = $_GET["user"];
            } else {
                header('Location: ./connexion');
            }
            $userInfo = getUser($targetedUser)->fetch(PDO::FETCH_ASSOC);
            include './vues/profil.php';
            break;
        default:
            include './vues/connexion.php';
            break;
    }
}
?>
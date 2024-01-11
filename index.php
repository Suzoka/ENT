<?php
// Démarrage de la session côté serveur
session_start();
// Connexion à la base de données
include('./scripts/database.php');
// Inclusion des fonctions
include('./scripts/script.php');

//Récupération du chemin de la page
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$segments = explode('/', $path);

//Récupération de la page
if (!isset($segments[2])) {
    $page = '';
} else {
    $page = $segments[2];
}

//Si l'utilisateur est connecté
if (isset($_SESSION['login'])) {
    //Récupération des informations de l'utilisateur
    $image = getImage($_SESSION['login']);
    $identite = getIdentite($_SESSION['login']);
    $classes = getClasses($_SESSION['login']);
    $role = getRole($_SESSION['login'])->fetchColumn();
    //Récupération de l'historique des conversations
    $historique = getAllConversation($_SESSION["login"])->fetchAll(PDO::FETCH_ASSOC);
    //Test de la page demandée
    switch ($page) {
        case 'accueil':
        default:
            //Affichage de la bonne page d'accueil en fonction du rôle de l'utilisateur
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
            //Si un destinataire est spécifié, aller à cette conversation, sinon, récupérer la dernière conversation
            if (isset($_GET["to"])) {
                $to = $_GET["to"];
            } else {
                $to = getLastConversation($_SESSION["login"]);
            }
            //Si une conversation a bien été trouvée, récupérer les informations de l'interlocuteur et de la conversation
            if ($to != null) {
                $imageReceiver = getImage($to);
                $identiteReceiver = getIdentite($to);
                $classesReceiver = getClasses($to);
                $conversation = getCurrentConversation($_SESSION["login"], $to)->fetchAll(PDO::FETCH_ASSOC);
            } else {
                //Sinon, préparer un message d'avertissement
                $identiteReceiver = "<i>Vous n'avez pas de conversation en cours, veuillez en créer une</i>";
                $classesReceiver = "";
                $conversation = array();
            }
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
            //Affichage de la bonne page de notes en fonction du rôle de l'utilisateur
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
                    header('Location: ./accueil');
                    break;
            }
            break;
        case 'profil':
            //Si un utilisateur est spécifié, afficher son profil, sinon, afficher le profil de l'utilisateur connecté
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
                //Vérification que les deux versions du nouveaux mots de passe sont identiques
                if ($_POST["newMdp"] == $_POST["newMdp2"]) {
                    //Vérification que l'ancien mot de passe est correct et réinitialisation du mot de passe
                    if (resetMdp($_SESSION["login"], $_POST["oldMdp"], $_POST["newMdp"])) {
                        header('Location: ./profil');
                    } else {
                        //Si l'ancien mot de passe est incorrect, afficher un message d'erreur
                        header('Location: ./resetPassword?error=3');
                    }
                //Sinon, afficher un message d'erreur
                } else {
                    header('Location: ./resetPassword?error=2');
                }
            } else {
                header('Location: ./resetPassword?error=1');
            }
            break;
        case 'gestionUsers':
            //Vérification que l'utilisateur est bien administrateur
            if ($role == 3) {
                //Si un utilisateur est spécifié, récupérer ses informations
                if (isset($_GET["user"])) {
                    $user = getUser($_GET["user"])->fetch(PDO::FETCH_ASSOC);
                }
                include './vues/gestionUsers.php';
            } else {
                header('Location: ./accueil');
            }
            break;
        case 'newUser':
            //Vérification que l'utilisateur est bien administrateur
            if ($role == 3) {
                if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["role"])) {
                    if (createUser($_POST)) {
                        //Si l'utilisateur a bien été créé, afficher un message de confirmation, avec le récapitulatif des informations et le mot de passe généré
                        echo "<a href='./gestionUsers'>Confirmer</a>";
                    } else {
                        header('Location: ./gestionUsers?error=true');
                    }
                } else {
                    header('Location: ./gestionUsers?error=true');
                }
            } else {
                header('Location: ./accueil');
            }
            break;
        case 'resetAdminPassword' :
            //Vérification que l'utilisateur est bien administrateur
            if ($role=3) {
                if (isset($_GET["id"])) {
                    if (resetAdminPassword($_GET["id"])) {
                        //Si le mot de passe a bien été réinitialisé, afficher un message de confirmation, avec le nouveau mot de passe généré
                        echo "<a href='./gestionUsers'>Confirmer</a>";
                    } else {
                        header('Location: ./gestionUsers?error=true');
                    }
                } else {
                    header('Location: ./gestionUsers?error=true');
                }
            } else {
                header('Location: ./accueil');
            }
            break;
        case 'deleteUser' :
            //Vérification que l'utilisateur est bien administrateur
            if ($role=3) {
                if (isset($_GET["id"])) {
                    if (deleteUser($_GET["id"])) {
                        header('Location: ./gestionUsers');
                    } else {
                        header('Location: ./gestionUsers?error=true');
                    }
                } else {
                    header('Location: ./gestionUsers?error=true');
                }
            } else {
                header('Location: ./accueil');
            }
            break;
        case 'gestionClasses':
            //Vérification que l'utilisateur est bien administrateur
            if ($role == 3) {
                include './vues/gestionClasses.php';
            } else {
                header('Location: ./accueil');
            }
            break;
        case 'gestionGroupes':
            //Vérification que l'utilisateur est bien administrateur
            if ($role == 3) {
                include './vues/gestionGroupes.php';
            } else {
                header('Location: ./accueil');
            }
            break;
    }
} else {
    //Si l'utilisateur n'est pas connecté, afficher la page de connexion, ou un profil s'il s'agit de la page demandée
    switch ($page) {
        case 'connexion':
            include './vues/connexion.php';
            break;
        case 'checkConnectionUsr':
            //Vérifie les identifiants de connexion d'un étudiant
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
            //Vérifie les identifiants de connexion d'un professeur
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
        case 'checkConnectionAdmin':
            //Vérifie les identifiants de connexion d'un administrateur
            if (isset($_POST['identifiant']) && isset($_POST['mdp'])) {
                if (checkConnectionAdmin($_POST['identifiant'], $_POST['mdp'])) {
                    header('Location: ./accueil');
                } else {
                    header('Location: ./connexion?error=admin!1');
                }
            } else {
                header('Location: ./connexion?error=admin!0');
            }
            break;
        case 'profil':
            //Si un utilisateur est spécifié, afficher son profil, sinon, afficher la page de connexion
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
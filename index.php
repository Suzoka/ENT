<?php
session_start();
require('./scripts/script.php');


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
        require './vues/connexion.php';
        break;
    case 'accueil':
        require './vues/accueil.php';
        break;
    default:
        // TODO : if user is connected, redirect to accueil.php, else redirect to connexion.php, 404 error ?
        require './vues/connexion.php';
        break;
}
?>
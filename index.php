<?php 
session_start();
require('./scripts/script.php');


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$segments = explode('/', $path);

$page = $segments[2];
var_dump($page);

switch ($page) {
    case 'connexion':
        require './vues/connexion.php';
        break;
    case 'accueil':
        require './vues/accueil.php';
        break;
    default:
        // TODO : if user is connected, redirect to accueil.php, else redirect to connexion.php
        require './vues/connexion.php';
        break;
}
?>
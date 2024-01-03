<?php
$serveur = "localhost";
$login = "";
$mdp = "";
$bdd = "ent";

$db = new PDO("mysql:host=$serveur;dbname=$bdd;charset=utf8mb4", $login, $mdp);
?>
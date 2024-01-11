<?php
//Copie vierge de la page qui permet la connection à la base de donnée
$serveur = "localhost";
$login = "";
$mdp = "";
$bdd = "ent";

$db = new PDO("mysql:host=$serveur;dbname=$bdd;charset=utf8mb4", $login, $mdp);
?>
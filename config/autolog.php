<?php

$host = "localhost";
$port = "3306";
$dbname = "the-league";
$connexionString = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8"; 

$user = "root";
$password = "";

$db = new PDO(
    $connexionString,
    $user,
    $password
);

$query = $db->prepare('SELECT * FROM `players`'); 
$parameters = [
];
$query->execute($parameters);
$user = $query->fetch(PDO::FETCH_ASSOC); //** demander a gemini : prompt("pourquoi mon code renvoie une page blanche ?) Reponse(Tu na pas mis de echo pour afficher le resultat de ta requete")

$user = $query->fetchAll(); 

if ($user) {
        echo "<pre>"; 
        print_r($user);
        echo "</pre>";
    } else {
        echo "Aucun joueur trouvé.";
    }
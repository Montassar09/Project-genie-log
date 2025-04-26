<?php
$servername = "localhost";
$username = "root"; // ou ton username
$password = "";     // ou ton password
$dbname = "espace_etudiant";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}
?>
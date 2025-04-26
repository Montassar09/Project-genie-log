<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If not, redirect them to the login page
    header("Location: admin_login.php");
    exit();
}

// If logged in, get the admin username
$admin_username = $_SESSION['admin_username'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Administrateur</title>
</head>
<body>
    <h1>Bienvenue, <?php echo $admin_username; ?></h1>
    <p>Vous êtes maintenant connecté à votre espace administrateur.</p>

    <!-- Add your dashboard content here -->
    <a href="logout.php">Se déconnecter</a>
</body>
</html>

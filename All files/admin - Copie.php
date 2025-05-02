<?php
include 'db.php';

// Handle adding an absence for a student
if (isset($_POST['ajouter_absence'])) {
    $etudiant = $_POST['etudiant'];
    $groupe = $_POST['groupe'];
    $classe = $_POST['classe'];
    $matiere = $_POST['matiere'];
    $date = $_POST['date'];
    $motif = $_POST['motif'];

    $conn->query("INSERT INTO absences (etudiant, groupe, classe, matiere, date_absence, motif) 
                  VALUES ('$etudiant', '$groupe', '$classe', '$matiere', '$date', '$motif')");
}

// Handle adding a course
if (isset($_POST['ajouter_cours'])) {
    $nom_cours = $_POST['nom_cours'];
    $groupe = $_POST['groupe'];
    $classe = $_POST['classe'];
    $professeur = $_POST['professeur'];
    $horaire = $_POST['horaire'];
    $date_cours = $_POST['date_cours'];

    $conn->query("INSERT INTO cours (nom_cours, groupe, classe, professeur, horaire, date_cours) 
                  VALUES ('$nom_cours', '$groupe', '$classe', '$professeur', '$horaire', '$date_cours')");
}

// Handle adding an absence for a professor
if (isset($_POST['ajouter_absence_prof'])) {
    $professeur = $_POST['professeur'];
    $groupe = $_POST['groupe'];
    $classe = $_POST['classe'];
    $matiere = $_POST['matiere'];
    $date = $_POST['date'];
    $motif = $_POST['motif'];

    // Insert professor absence into the 'absence_prof' table
    $conn->query("INSERT INTO absence_prof (professeur, groupe, classe, matiere, date_absence, motif) 
                   VALUES ('$professeur', '$groupe', '$classe', '$matiere', '$date', '$motif')");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background: #f0f2f5;
    }

    header {
        background-color: #4a69bd;
        color: white;
        padding: 20px;
        text-align: center;
        font-size: 28px;
        font-weight: bold;
    }

    .container {
        width: 90%;
        max-width: 1000px;
        margin: 30px auto;
    }

    .section {
        background: white;
        padding: 30px;
        margin-bottom: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    h2 {
        color: #4a69bd;
        margin-bottom: 20px;
    }

    form label {
        display: block;
        margin-top: 15px;
        color: #333;
        font-weight: 500;
    }

    input, select, button {
        width: 100%;
        padding: 12px;
        margin-top: 8px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
    }

    button {
        background-color: #4a69bd;
        color: white;
        font-weight: bold;
        border: none;
        margin-top: 20px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #3b5ca8;
    }
    </style>
</head>
<body>

<header>Espace Administrateur</header>

<div class="container">

    <!-- Ajouter un Cours -->
    <div class="section">
        <h2>Ajouter un Cours</h2>
        <form method="POST">
            <label>Nom du Cours:</label>
            <input type="text" name="nom_cours" required>

            <label>Groupe:</label>
            <input type="text" name="groupe" required>

            <label>Classe:</label>
            <input type="text" name="classe" required>

            <label>Professeur:</label>
            <input type="text" name="professeur" required>

            <label>Horaire:</label>
            <input type="text" name="horaire" required>

            <label>Date du Cours:</label>
            <input type="date" name="date_cours" required>

            <button type="submit" name="ajouter_cours">Ajouter</button>
        </form>
    </div>

    <!-- Ajouter une Absence Étudiant -->
    <div class="section">
        <h2>Ajouter une Absence</h2>
        <form method="POST">
            <label>Nom de l'Étudiant:</label>
            <input type="text" name="etudiant" required>

            <label>Groupe:</label>
            <input type="text" name="groupe" required>

            <label>Classe:</label>
            <input type="text" name="classe" required>

            <label>Matière:</label>
            <input type="text" name="matiere" required>

            <label>Date:</label>
            <input type="date" name="date" required>

            <label>Motif:</label>
            <input type="text" name="motif" required>

            <button type="submit" name="ajouter_absence">Ajouter</button>
        </form>
    </div>

    <!-- Ajouter une Absence Professeur -->
    <div class="section">
        <h2>Ajouter une Absence Professeur</h2>
        <form method="POST">
            <label>Nom du Professeur:</label>
            <input type="text" name="professeur" required>

            <label>Groupe:</label>
            <input type="text" name="groupe" required>

            <label>Classe:</label>
            <input type="text" name="classe" required>

            <label>Matière:</label>
            <input type="text" name="matiere" required>

            <label>Date:</label>
            <input type="date" name="date" required>

            <label>Motif:</label>
            <input type="text" name="motif" required>

            <button type="submit" name="ajouter_absence_prof">Ajouter</button>
        </form>
    </div>

</div>

</body>
</html>

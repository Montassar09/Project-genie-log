<?php
include 'db.php';

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
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Admin</title>
    
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

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th, td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f1f3f6;
        font-weight: bold;
        color: #333;
    }

    tr:hover {
        background-color: #f9f9f9;
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

    <h1>Espace Administrateur</h1>

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

        <button type="submit" name="ajouter_cours">Ajouter</button>
    </form>
</div>


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

</body>
</html>

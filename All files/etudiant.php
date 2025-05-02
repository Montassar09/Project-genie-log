<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Étudiant</title>
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
    </style>
</head>
<body>

<header>Espace Étudiant</header>

<div class="container">

    <!-- Cours -->
    <div class="section">
        <h2>Mes Cours</h2>
        <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Groupe</th>
                    <th>Classe</th>
                    <th>Professeur</th>
                    <th>Horaire</th>
                    <th>Date du Cours</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM cours");
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['nom_cours']}</td>
                            <td>{$row['groupe']}</td>
                            <td>{$row['classe']}</td>
                            <td>{$row['professeur']}</td>
                            <td>{$row['horaire']}</td>
                            <td>{$row['date_cours']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Absences Étudiants -->
    <div class="section">
        <h2>Mes Absences</h2>
        <table>
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Groupe</th>
                    <th>Classe</th>
                    <th>Matière</th>
                    <th>Date</th>
                    <th>Motif</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM absences");
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['etudiant']}</td>
                            <td>{$row['groupe']}</td>
                            <td>{$row['classe']}</td>
                            <td>{$row['matiere']}</td>
                            <td>{$row['date_absence']}</td>
                            <td>{$row['motif']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Absences Professeurs -->
    <div class="section">
        <h2>Absences des Professeurs</h2>
        <table>
            <thead>
                <tr>
                    <th>Professeur</th>
                    <th>Matière</th>
                    <th>Classe</th>
                    <th>Groupe</th>
                    <th>Date</th>
                    <th>Motif</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM absence_prof");
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['professeur']}</td>
                            <td>{$row['matiere']}</td>
                            <td>{$row['classe']}</td>
                            <td>{$row['groupe']}</td>
                            <td>{$row['date_absence']}</td>
                            <td>{$row['motif']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>

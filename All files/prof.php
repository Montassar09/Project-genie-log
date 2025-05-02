<?php
include 'dbb.php'; // fichier de connexion (doit contenir $conn = new mysqli(...))

function profExists($conn, $email) {
    $stmt = $conn->prepare("SELECT * FROM professeur WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (!profExists($conn, $email)) {
        $message = "Accès refusé : professeur non trouvé.";
    } else {
        if (isset($_POST['submit_seance'])) {
            $titre = $_POST['titre_seance'];
            $matiere = $_POST['matiere'];
            $date = $_POST['date_seance'];
            $heure = $_POST['heure'];
            $stmt = $conn->prepare("INSERT INTO seance (titre, matiere, date, heure) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $titre, $matiere, $date, $heure);
            $stmt->execute();
            $message = "Séance ajoutée avec succès.";
        }

        if (isset($_POST['submit_absence'])) {
            $titre = $_POST['titre_absence'];
            $date = $_POST['date_absence'];
            $stmt = $conn->prepare("INSERT INTO absence (titre, date) VALUES (?, ?)");
            $stmt->bind_param("ss", $titre, $date);
            $stmt->execute();
            $message = "Absence ajoutée avec succès.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déclaration professeur</title>
    <style>
        <?php include 'enligne.css'; ?>
    </style>
</head>
<body>

<?php if ($message): ?>
    <script>alert("<?= $message ?>");</script>
<?php endif; ?>

<h1>Déclaration de séance en ligne</h1>
<div class="container">
    <form method="POST">
        <div class="contact-form">
            <h2>Information personnel</h2>
            <input type="text" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" placeholder="ID" required>
            <input type="text" placeholder="Téléphone" required>
        </div>

        <div class="card">
            <h4>Ajouter une nouvelle séance</h4>
            <input type="text" name="titre_seance" placeholder="Titre de la séance" required>
            <input type="text" name="matiere" placeholder="Nom de matière" required>
            <input type="date" name="date_seance" required>
            <input type="time" name="heure" required>
            <button type="submit" name="submit_seance">Ajouter</button>
        </div>
    </form>
</div>

<hr>

<h1>Déclaration d'absence</h1>
<div class="container">
    <form method="POST">
        <div class="contact-form">
            <h2>Information personnel</h2>
            <input type="text" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" placeholder="ID" required>
            <input type="text" placeholder="Téléphone" required>
        </div>

        <div class="card">
            <h4>Cause d'absence</h4>
            <input type="text" name="titre_absence" placeholder="Titre de la séance" required>
            <input type="date" name="date_absence" required>
            <button type="submit" name="submit_absence">Ajouter</button>
        </div>
    </form>
</div>

</body>
</html>

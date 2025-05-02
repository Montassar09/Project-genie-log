<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $sujet = $_POST['sujet'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $message = $_POST['message'] ?? '';

    $to = "montassarbenhassine44@gmail.com";  // Remplace par ton adresse e-mail
    $subject = "Nouveau message de contact de $nom";
    $body = "Nom: $nom\nEmail: $email\nSujet: $sujet\nTéléphone: $telephone\n\nMessage:\n$message";

    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Message envoyé avec succès !');</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'envoi du message.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contactez-Nous</title>
    <link rel="stylesheet" href="pluscontact.css">
</head>
<body>
    <h1>Contactez-Nous</h1>
    <div class="container">
        <div class="contact-form">
            <h2>Formulaire de Contact</h2>
            <form method="POST">
                <div class="row">
                    <input type="text" name="nom" placeholder="Votre nom" required>
                    <input type="email" name="email" placeholder="Adresse Email" required>
                </div>
                <div class="row">
                    <input type="text" name="sujet" placeholder="Sujet" required>
                    <input type="text" name="telephone" placeholder="Téléphone">
                </div>
                <textarea name="message" placeholder="Tapez votre message ici..." required></textarea>
                <div class="buttons">
                    <button type="submit">Envoyer</button>
                    <button type="reset">Effacer</button>
                </div>
            </form>
        </div>
        <div class="contact-info">
            <h2>Informations de Contact</h2>
            <p>L’Institut Supérieur de Gestion de Bizerte (ISGB) est un établissement universitaire relevant de l’Université de Carthage...</p>
            <p><strong>Téléphone :</strong> 72 570 780</p>
            <p><strong>Fax :</strong> 72 570 840</p>
            <p><strong>Email :</strong> isgcb@isgcb.rnu.tn</p>
        </div>
    </div>
    <img src="imgs/dev.png" alt="">
</body>
</html>

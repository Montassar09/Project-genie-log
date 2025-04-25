<?php
$errors = [];
$success = "";

// Traitement à la soumission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['User']);
    $email = trim($_POST['mail']);
    $password = $_POST['pass'];
    $confirm_password = $_POST['cpass'];
    $terms = isset($_POST['chekb2']);

    // Validation
    if (empty($username)) {
        $errors[] = "Le nom est requis.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors[] = "Mot de passe faible : min 8 caractères, 1 majuscule, 1 chiffre.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if (!$terms) {
        $errors[] = "Vous devez accepter les conditions.";
    }

    if (empty($errors)) {
        // Connexion DB
        $conn = new mysqli("localhost", "root", "", "clubs");
        if ($conn->connect_error) {
            die("Connexion échouée : " . $conn->connect_error);
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO jci (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $success = "✅ Inscription réussie !";
        } else {
            $errors[] = "Erreur : " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formuler</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="tuniv.css" rel="stylesheet">
    <script src="formuler.js"></script>
    <link rel="website icon" type="png" href="image/bh3.png">
</head>
<body onLoad="bienvenue()">
    <div class="container">
        <form id="form" method="POST" action="">
            <h1>JCI INSCRIPTION</h1>

            <?php
            if (!empty($errors)) {
                foreach ($errors as $e) {
                    echo "<p style='color:red;'>$e</p>";
                }
            }
            if (!empty($success)) {
                echo "<p style='color:green;'>$success</p>";
            }
            ?>

            <label>Username</label>
            <input type="text" id="User" name="User" placeholder="Entre your name">
            <i class='bx bx-user' id="n"></i>
            <h4 id="u"></h4>

            <label>Email</label>
            <input type="text" id="mail" name="mail" placeholder="Entre your mail">
            <i class='bx bx-envelope' id="n" ></i>
            <h4 id="m"></h4>

            <label>Password</label>
            <input type="password" id="pass" name="pass" placeholder="Entre your Password">
            <i class='bx bx-lock-alt' id="n" ></i>
            <h4 id="p"></h4>

            <label>Confirm Password</label>
            <input type="password" id="cpass" name="cpass" placeholder="Confirme your Password">
            <i class='bx bx-lock-alt' id="n"></i>
            <h4 id="pa"></h4>

            <div id="but">
                <input type="checkbox" id="chekb1" name="chekb1"><span>Save account</span>
                <input type="checkbox" id="chekb2" name="chekb2"><span>Agree with terms & conditions</span>
                <h4 id="bt"></h4>
            </div>

            <button type="submit" id="b1">Sign up</button>
            <button type="reset" id="b2" onclick="resetForm()">Reset</button>
            <button type="button" id="b3"><a href="javascript:history.back()" class="back">Back</a></button>
        </form>

        <div class="links">
            <a href="https://www.instagram.com/montassar__9/"><i class='bx bxl-instagram' id="inst"></i></a> 
            <a href="https://www.facebook.com/"><i class='bx bxl-facebook-circle' id="fcb" ></i></a>
            <a href="https://mail.google.com/mail/u/0/#inbox"><i class='bx bxl-gmail' id="mai" ></i></a>
            <a href="https://web.whatsapp.com/"><i class='bx bxl-whatsapp' id="wht"></i></a>
        </div>
    </div>
</body>
</html>

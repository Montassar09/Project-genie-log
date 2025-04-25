<?php
session_start();
$error = "";

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "logetud");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['User']);
    $email = trim($_POST['mail']);
    $id = trim($_POST['cpass']);

    // Préparation de la requête
    $stmt = $conn->prepare("SELECT * FROM etudiants WHERE username = ? AND email = ? AND etu_id = ?");
    $stmt->bind_param("sss", $username, $email, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérification
    if ($result->num_rows > 0) {
        $_SESSION['user'] = $username;
        header("Location: Menu.html"); // Redirection
        exit();
    } else {
        $error = "❌ Étudiant non trouvé. Vérifiez vos informations.";
    }

    $stmt->close();
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
            <h1>LOGIN ETUDIANT</h1>

            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <label>Username</label>
            <input type="text" id="User" name="User" placeholder="Entre your name"><i class='bx bx-user' id="n"></i>
            <h4 id="u"></h4>

            <label>Email</label>
            <input type="text" id="mail" name="mail" placeholder="Entre your mail"><i class='bx bx-envelope' id="n" ></i>
            <h4 id="m"></h4>

            <label>ID</label>
            <input type="password" id="cpass" name="cpass" placeholder="Entre your id"><i class='bx bx-lock-alt' id="n"></i>
            <h4 id="pa"></h4>

            <div id="but">
                <input type="checkbox" id="chekb1"><span>Save account</span>
                <input type="checkbox" id="chekb2"><span>Agree with terms & conditions</span>
                <h4 id="bt"></h4>
            </div>

            <button type="submit" id="b1" >Sign up</button>
            <button type="reset" id="b2" onclick="resetForm()">Reset</button>
            <button type="button" id="b3"><a href="javascript:history.back()" class="back">Back</a></button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
include 'dba.php'; // Include the database connection

// Vérifier si le formulaire a été soumis
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Préparer la requête pour vérifier si l'admin existe
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si l'admin existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Vérifier le mot de passe
        if ($password == $row['password']) {
            // Si le mot de passe est correct, démarrer la session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];

            // Rediriger vers la page d'administration (Espace Admin)
            header("Location: http://localhost/Project%20genie%20log/All%20files/admin.php");
            exit();
        } else {
            $error_message = "Mot de passe incorrect.";
        }
    } else {
        $error_message = "Nom d'utilisateur incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <style>
        /* Style pour la page de login */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 40px;
            box-shadow: 0px 6px 12px rgba(0,0,0,0.1);
            border-radius: 12px;
            width: 350px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0px 12px 24px rgba(0,0,0,0.15);
        }
        .login-container h2 {
            color: #4a69bd;
            font-size: 24px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 600;
            color: #333;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }
        input:focus {
            border-color: #4a69bd;
            background-color: #e9f1fe;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4a69bd;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        button:hover {
            background-color: #3b5ca8;
            transform: translateY(-2px);
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login Admin</h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="login">Se connecter</button>
    </form>
</div>

</body>
</html>

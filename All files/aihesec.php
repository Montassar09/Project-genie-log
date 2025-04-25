<?php
// Configuration de la base de données
$host = "localhost";
$dbname = "clubs";
$username = "root";
$password = "";

// Établir la connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $username = isset($_POST['User']) ? trim($_POST['User']) : '';
    $email = isset($_POST['mail']) ? trim($_POST['mail']) : '';
    $password = isset($_POST['pass']) ? $_POST['pass'] : '';
    $confirm_password = isset($_POST['cpass']) ? $_POST['cpass'] : '';
    $save_account = isset($_POST['chekb1']) ? 1 : 0;
    $agree_terms = isset($_POST['chekb2']) ? 1 : 0;
    $selected_club = isset($_POST['club']) ? $_POST['club'] : '';

    // Tableau pour stocker les erreurs
    $errors = [];

    // Validation des données
    if (empty($username)) {
        $errors['username'] = "Le nom d'utilisateur est requis";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors['username'] = "Le nom d'utilisateur doit contenir entre 3 et 50 caractères";
    }

    if (empty($email)) {
        $errors['email'] = "L'adresse email est requise";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Veuillez entrer une adresse email valide";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors['email'] = "Cette adresse email est déjà utilisée";
        }
    }

    if (empty($password)) {
        $errors['password'] = "Le mot de passe est requis";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères";
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors['password'] = "Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Les mots de passe ne correspondent pas";
    }

    if (!$agree_terms) {
        $errors['terms'] = "Vous devez accepter les termes et conditions";
    }

    if (empty($selected_club)) {
        $errors['club'] = "Veuillez sélectionner un club";
    }

    // Si aucune erreur, procéder à l'enregistrement
    if (empty($errors)) {
        try {
            // Hasher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertion de l'utilisateur dans la table générale des utilisateurs
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, save_account, agree_terms, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$username, $email, $hashed_password, $save_account, $agree_terms]);
            $user_id = $pdo->lastInsertId();

            // Insertion dans la table du club sélectionné
            $club_table = '';
            switch ($selected_club) {
                case 'aiesec':
                    $club_table = 'aiesec_members';
                    break;
                case 'tunivisions':
                    $club_table = 'tunivisions_members';
                    break;
                case 'ieee':
                    $club_table = 'ieee_members';
                    break;
                case 'jci':
                    $club_table = 'jci_members';
                    break;
                default:
                    throw new Exception("Club non valide");
            }

            $stmt = $pdo->prepare("INSERT INTO {$club_table} (user_id, status, joined_at) VALUES (?, 'pending', NOW())");
            $stmt->execute([$user_id]);

            // Redirection vers une page de succès
            header("Location: success.php?club=" . urlencode($selected_club));
            exit();
        } catch (Exception $e) {
            $error_message = "Une erreur est survenue lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formulaire d'inscription AIESEC</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="tuniv.css" rel="stylesheet">
    <link rel="website icon" type="png" href="image/bh3.png">
    <script src="formuler.js"></script>
</head>
<body onLoad="bienvenue()">
    <div class="container">
        <form id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <h1>AIESEC INSCRIPTION</h1>
            
            <label>Username</label>
            <input type="text" id="User" name="User" placeholder="Entre your name" value="<?php echo isset($_POST['User']) ? htmlspecialchars($_POST['User']) : ''; ?>">
            <i class='bx bx-user' id="n"></i>
            <h4 id="u" class="error"><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></h4>
            
            <label>Email</label>
            <input type="text" id="mail" name="mail" placeholder="Entre your mail" value="<?php echo isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : ''; ?>">
            <i class='bx bx-envelope' id="n"></i>
            <h4 id="m" class="error"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></h4>
            
            <label>Password</label>
            <input type="password" id="pass" name="pass" placeholder="Entre your Password">
            <i class='bx bx-lock-alt' id="n"></i>
            <h4 id="p" class="error"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></h4>
            
            <label>Confirm Password</label>
            <input type="password" id="cpass" name="cpass" placeholder="Confirme your Password">
            <i class='bx bx-lock-alt' id="n"></i>
            <h4 id="pa" class="error"><?php echo isset($errors['confirm_password']) ? $errors['confirm_password'] : ''; ?></h4>
            
            <label>Select Club</label>
            <select id="club" name="club">
                <option value="">-- Sélectionnez un club --</option>
                <option value="aiesec" <?php echo (isset($_POST['club']) && $_POST['club'] == 'aiesec') ? 'selected' : ''; ?>>AIESEC</option>
                <option value="tunivisions" <?php echo (isset($_POST['club']) && $_POST['club'] == 'tunivisions') ? 'selected' : ''; ?>>Tunivisions</option>
                <option value="ieee" <?php echo (isset($_POST['club']) && $_POST['club'] == 'ieee') ? 'selected' : ''; ?>>IEEE</option>
                <option value="jci" <?php echo (isset($_POST['club']) && $_POST['club'] == 'jci') ? 'selected' : ''; ?>>JCI</option>
            </select>
            <h4 id="c" class="error"><?php echo isset($errors['club']) ? $errors['club'] : ''; ?></h4>
            
            <div id="but">
                <input type="checkbox" id="chekb1" name="chekb1" <?php echo isset($_POST['chekb1']) ? 'checked' : ''; ?>>
                <span>Save account</span>
                <input type="checkbox" id="chekb2" name="chekb2" <?php echo isset($_POST['chekb2']) ? 'checked' : ''; ?>>
                <span>Agree with terms & conditions</span>
                <h4 id="bt" class="error"><?php echo isset($errors['terms']) ? $errors['terms'] : ''; ?></h4>
            </div>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <button type="submit" id="b1">Sign up</button>
            <button type="reset" id="b2">Reset</button>
            <button type="button" id="b3"><a href="javascript:history.back()" class="back">Back</a></button>
        </form>
        
        <div class="links">
            <a href="https://www.instagram.com/montassar__9/"><i class='bx bxl-instagram' id="inst"></i></a>
            <a href="https://www.facebook.com/"><i class='bx bxl-facebook-circle' id="fcb"></i></a>
            <a href="https://mail.google.com/mail/u/0/#inbox"><i class='bx bxl-gmail' id="mai"></i></a>
            <a href="https://web.whatsapp.com/"><i class='bx bxl-whatsapp' id="wht"></i></a>
        </div>
    </div>
</body>
</html>
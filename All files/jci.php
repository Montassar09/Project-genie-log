<?php
// Initialize variables to hold any error messages
$success_message = '';
$error_message = '';
$field_errors = [];

// Process the form only when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $dbname = "job_fair";
    $username = "root"; 
    $password = ""; 

    // Validate all fields
    // Email validation
    if (empty($_POST['email'])) {
        $field_errors['email'] = "Email is required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $field_errors['email'] = "Please enter a valid email address";
    }

    // Card number validation
    if (empty($_POST['card_number'])) {
        $field_errors['card_number'] = "Card number is required";
    } elseif (!preg_match('/^[0-9]{16,19}$/', str_replace(' ', '', $_POST['card_number']))) {
        $field_errors['card_number'] = "Please enter a valid card number (16-19 digits)";
    }

    // Expiry date validation (MM/YY format)
    if (empty($_POST['expiry_date'])) {
        $field_errors['expiry_date'] = "Expiry date is required";
    } elseif (!preg_match('/^(0[1-9]|1[0-2])\s?\/\s?([0-9]{2})$/', $_POST['expiry_date'])) {
        $field_errors['expiry_date'] = "Please enter a valid expiry date (MM/YY)";
    } else {
        // Check if card is expired
        list($month, $year) = explode('/', str_replace(' ', '', $_POST['expiry_date']));
        $expiry_date = \DateTime::createFromFormat('my', $month . $year);
        $now = new DateTime();
        if ($expiry_date < $now) {
            $field_errors['expiry_date'] = "The card has expired";
        }
    }

    // CVC validation
    if (empty($_POST['cvc'])) {
        $field_errors['cvc'] = "CVC is required";
    } elseif (!preg_match('/^[0-9]{3,4}$/', $_POST['cvc'])) {
        $field_errors['cvc'] = "Please enter a valid CVC (3-4 digits)";
    }

    // Card holder name validation
    if (empty($_POST['card_holder_name'])) {
        $field_errors['card_holder_name'] = "Card holder name is required";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\'\.]{3,100}$/', $_POST['card_holder_name'])) {
        $field_errors['card_holder_name'] = "Please enter a valid name (3-100 characters)";
    }

    // Address validation
    if (empty($_POST['address_line1'])) {
        $field_errors['address_line1'] = "Address is required";
    } elseif (strlen($_POST['address_line1']) < 5) {
        $field_errors['address_line1'] = "Please enter a valid address (at least 5 characters)";
    }

    // Postal code validation
    if (empty($_POST['postal_code'])) {
        $field_errors['postal_code'] = "Postal code is required";
    } elseif (!preg_match('/^[0-9a-zA-Z\s\-]{4,10}$/', $_POST['postal_code'])) {
        $field_errors['postal_code'] = "Please enter a valid postal code";
    }

    // City validation
    if (empty($_POST['city'])) {
        $field_errors['city'] = "City is required";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\'\.]{2,50}$/', $_POST['city'])) {
        $field_errors['city'] = "Please enter a valid city name";
    }

    // Country validation
    if (empty($_POST['country'])) {
        $field_errors['country'] = "Please select a country";
    }

    // If validation passed, process the form
    if (empty($field_errors)) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Clean and prepare data
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $card_number = preg_replace('/\s+/', '', $_POST['card_number']); // Remove spaces
            $expiry_date = preg_replace('/\s+/', '', $_POST['expiry_date']); // Remove spaces
            $cvc = $_POST['cvc'];
            $card_holder_name = htmlspecialchars($_POST['card_holder_name']);
            $address_line1 = htmlspecialchars($_POST['address_line1']);
            $address_line2 = isset($_POST['address_line2']) ? htmlspecialchars($_POST['address_line2']) : '';
            $postal_code = htmlspecialchars($_POST['postal_code']);
            $city = htmlspecialchars($_POST['city']);
            $country = $_POST['country'];
            $save_info = isset($_POST['save_info']) ? 1 : 0;

            // Insert data into database
            $stmt = $pdo->prepare("INSERT INTO registrations 
                (email, card_number, expiry_date, cvc, card_holder_name, address_line1, address_line2, postal_code, city, country, save_info) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $email, $card_number, $expiry_date, $cvc, $card_holder_name,
                $address_line1, $address_line2, $postal_code, $city, $country, $save_info
            ]);

            $success_message = "Merci ! Votre inscription a été enregistrée avec succès.";
        } catch (PDOException $e) {
            $error_message = "Erreur de connexion ou d'insertion : " . $e->getMessage();
        }
    } else {
        $error_message = "Veuillez corriger les erreurs dans le formulaire.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Fair Registration</title>
    <link rel="stylesheet" href="jci.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <style>
        .message-success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .message-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .field-error {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
        }
        input.is-invalid, select.is-invalid {
            border-color: #dc3545;
        }
        /* Format for card number input */
        input[name="card_number"] {
            letter-spacing: 1px;
        }
        /* For card input focus */
        input:focus {
            outline: none;
            border-color: #4a6fa5;
            box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.2);
        }
    </style>
</head>
<body>
    <?php if($success_message): ?>
        <div class="container">
            <div class="message-success">
                <?php echo $success_message; ?>
                <p><a href="event.html" class="btn btn-success mt-3">Return to Events</a></p>
            </div>
        </div>
    <?php else: ?>
        <?php if($error_message): ?>
            <div class="container">
                <div class="message-error">
                    <?php echo $error_message; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="container">
            <div class="left-section">
                <h2>Join us at Job Fair 2025</h2>
                <div class="price">$20,00 <span class="currency">DT</span></div>
                <div class="summary"><hr>
                    <p><strong>Subscription</strong></p>
                    <p>Job Fair 2025: Bridging Talent and Opportunity <br>

                    Join us at Job Fair 2025, a dynamic event designed to connect students, graduates, and job seekers with leading employers across various industries. Whether you're looking for internships, full-time positions, or simply want to explore career options, this is your opportunity to meet recruiters, expand your network, and take the next step in your professional journey.
                    <br>
                    📍 Location: MAJESTIC BIZERTE <br>
                    📅 Date: 25/05/2025<br>
                    🕘 Time: 10:00 – 16:00 <br> 
                    
                    What to Expect: <br>
                    ✅ On-site interviews and resume reviews <br>
                    ✅ Company booths and career information sessions <br>
                    ✅ Networking opportunities with HR professionals and industry experts <br>
                    ✅ Workshops on job search strategies, CV building, and interview tips <br>
                    
                    Who Should Attend? <br>
                    
                    University students and recent graduates <br>
                    
                    Young professionals exploring new career paths <br>
                    
                    Anyone looking to connect with top employers <br>
                    
                    Don't forget to bring multiple copies of your resume and dress professionally! <br>
                    </p>
                    <hr>
                </div>
            </div>
        
            <div class="right-section">
                <h3>Coordonnées</h3>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                      class="<?php echo isset($field_errors['email']) ? 'is-invalid' : ''; ?>" required>
                    <?php if (isset($field_errors['email'])): ?>
                        <span class="field-error"><?php echo $field_errors['email']; ?></span>
                    <?php endif; ?>
                
                    <h4>Moyen de paiement</h4>
                    <label>Informations de la carte</label>
                    <input type="text" name="card_number" placeholder="1234 1234 1234 1234" maxlength="19" 
                      value="<?php echo isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : ''; ?>"
                      class="<?php echo isset($field_errors['card_number']) ? 'is-invalid' : ''; ?>"
                      oninput="this.value = this.value.replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim()" required>
                    <?php if (isset($field_errors['card_number'])): ?>
                        <span class="field-error"><?php echo $field_errors['card_number']; ?></span>
                    <?php endif; ?>
                    
                    <div class="card-info">
                        <input type="text" name="expiry_date" placeholder="MM / AA" maxlength="5" 
                          value="<?php echo isset($_POST['expiry_date']) ? htmlspecialchars($_POST['expiry_date']) : ''; ?>"
                          class="<?php echo isset($field_errors['expiry_date']) ? 'is-invalid' : ''; ?>"
                          oninput="this.value = this.value.replace(/[^\d\/]/g, '').replace(/^(\d{2})(\d{0,2})/, '$1/$2')" required>
                        <?php if (isset($field_errors['expiry_date'])): ?>
                            <span class="field-error"><?php echo $field_errors['expiry_date']; ?></span>
                        <?php endif; ?>
                        
                        <input type="text" name="cvc" placeholder="CVC" maxlength="4" 
                          value="<?php echo isset($_POST['cvc']) ? htmlspecialchars($_POST['cvc']) : ''; ?>"
                          class="<?php echo isset($field_errors['cvc']) ? 'is-invalid' : ''; ?>"
                          oninput="this.value = this.value.replace(/[^\d]/g, '')" required>
                        <?php if (isset($field_errors['cvc'])): ?>
                            <span class="field-error"><?php echo $field_errors['cvc']; ?></span>
                        <?php endif; ?>
                    </div>
                
                    <label>Nom du titulaire de la carte</label>
                    <input type="text" name="card_holder_name" placeholder="Nom complet" 
                      value="<?php echo isset($_POST['card_holder_name']) ? htmlspecialchars($_POST['card_holder_name']) : ''; ?>"
                      class="<?php echo isset($field_errors['card_holder_name']) ? 'is-invalid' : ''; ?>" required>
                    <?php if (isset($field_errors['card_holder_name'])): ?>
                        <span class="field-error"><?php echo $field_errors['card_holder_name']; ?></span>
                    <?php endif; ?>
                
                    <label>Adresse de facturation</label>
                    <input type="text" name="address_line1" placeholder="Ligne d'adresse n°1" 
                      value="<?php echo isset($_POST['address_line1']) ? htmlspecialchars($_POST['address_line1']) : ''; ?>"
                      class="<?php echo isset($field_errors['address_line1']) ? 'is-invalid' : ''; ?>" required>
                    <?php if (isset($field_errors['address_line1'])): ?>
                        <span class="field-error"><?php echo $field_errors['address_line1']; ?></span>
                    <?php endif; ?>
                    
                    <input type="text" name="address_line2" placeholder="Ligne d'adresse n°2"
                      value="<?php echo isset($_POST['address_line2']) ? htmlspecialchars($_POST['address_line2']) : ''; ?>">
                    
                    <div class="address-info">
                        <input type="text" name="postal_code" placeholder="Code postal" 
                          value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code']) : ''; ?>"
                          class="<?php echo isset($field_errors['postal_code']) ? 'is-invalid' : ''; ?>" required>
                        <?php if (isset($field_errors['postal_code'])): ?>
                            <span class="field-error"><?php echo $field_errors['postal_code']; ?></span>
                        <?php endif; ?>
                        
                        <input type="text" name="city" placeholder="Ville" 
                          value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>"
                          class="<?php echo isset($field_errors['city']) ? 'is-invalid' : ''; ?>" required>
                        <?php if (isset($field_errors['city'])): ?>
                            <span class="field-error"><?php echo $field_errors['city']; ?></span>
                        <?php endif; ?>
                    </div>
                
                    <select name="country" class="<?php echo isset($field_errors['country']) ? 'is-invalid' : ''; ?>" required>
                        <option value="" <?php echo !isset($_POST['country']) ? 'selected' : ''; ?>>Sélectionnez un pays</option>
                        <option value="TN" <?php echo (isset($_POST['country']) && $_POST['country'] == 'TN') ? 'selected' : ''; ?>>Tunisie</option>
                        <option value="FR" <?php echo (isset($_POST['country']) && $_POST['country'] == 'FR') ? 'selected' : ''; ?>>France</option>
                        <option value="US" <?php echo (isset($_POST['country']) && $_POST['country'] == 'US') ? 'selected' : ''; ?>>États-Unis</option>
                        <option value="CA" <?php echo (isset($_POST['country']) && $_POST['country'] == 'CA') ? 'selected' : ''; ?>>Canada</option>
                        <option value="UK" <?php echo (isset($_POST['country']) && $_POST['country'] == 'UK') ? 'selected' : ''; ?>>Royaume-Uni</option>
                        <option value="DE" <?php echo (isset($_POST['country']) && $_POST['country'] == 'DE') ? 'selected' : ''; ?>>Allemagne</option>
                        <option value="IT" <?php echo (isset($_POST['country']) && $_POST['country'] == 'IT') ? 'selected' : ''; ?>>Italie</option>
                    </select>
                    <?php if (isset($field_errors['country'])): ?>
                        <span class="field-error"><?php echo $field_errors['country']; ?></span>
                    <?php endif; ?>
                
                    <label><input type="checkbox" name="save_info" <?php echo (isset($_POST['save_info']) && $_POST['save_info']) ? 'checked' : ''; ?>> 
                      Enregistrer mes informations en toute sécurité</label>
                    
                    <input type="submit" value="Payer">
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Back button for easy navigation -->
    <div class="container mt-4 mb-4">
        <a href="event.html" class="btn btn-secondary">Back to Events</a>
    </div>

    <!-- JavaScript for better user experience with form validation -->
    <script>
        // Format card number as the user types (add spaces every 4 digits)
        document.querySelector('input[name="card_number"]').addEventListener('input', function(e) {
            let value = this.value.replace(/\s+/g, '');
            if (value.length > 0) {
                value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
            }
            this.value = value;
        });

        // Format expiry date as MM/YY
        document.querySelector('input[name="expiry_date"]').addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.value = value;
        });
        
        // Allow only numbers in CVC field
        document.querySelector('input[name="cvc"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>
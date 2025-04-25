<?php
// This part should be at the beginning of register.php

// Process the form only when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $dbname = "job_fair";
    $username = "root"; 
    $password = ""; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check if required fields exist
        $required_fields = ['card_number', 'expiry_date', 'cvc', 'card_holder_name', 
                           'address_line1', 'postal_code', 'city', 'country'];
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $missing_fields[] = $field;
            }
        }
        
        if (!empty($missing_fields)) {
            echo "Error: The following required fields are missing: " . implode(', ', $missing_fields);
            exit;
        }
        
        // Collect form data
        $email = "montassarbenhassine44@gmail.com"; // Hardcoded email
        $card_number = $_POST['card_number'];
        $expiry_date = $_POST['expiry_date'];
        $cvc = $_POST['cvc'];
        $card_holder_name = $_POST['card_holder_name'];
        $address_line1 = $_POST['address_line1'];
        $address_line2 = isset($_POST['address_line2']) ? $_POST['address_line2'] : '';
        $postal_code = $_POST['postal_code'];
        $city = $_POST['city'];
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

        echo "<h2>Merci ! Votre inscription a été enregistrée avec succès.</h2>";
        
    } catch (PDOException $e) {
        echo "Erreur de connexion ou d'insertion : " . $e->getMessage();
    }
} 
    // HTML form to display when page is first loaded
?>


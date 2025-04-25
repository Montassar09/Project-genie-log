<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Use isset to avoid undefined index warnings
    $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : '';
    $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '';
    $cvc = isset($_POST['cvc']) ? $_POST['cvc'] : '';
    $card_holder_name = isset($_POST['card_holder_name']) ? $_POST['card_holder_name'] : '';
    $address_line1 = isset($_POST['address_line1']) ? $_POST['address_line1'] : '';
    $address_line2 = isset($_POST['address_line2']) ? $_POST['address_line2'] : '';
    $postal_code = isset($_POST['postal_code']) ? $_POST['postal_code'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $save_info = isset($_POST['save_info']) ? 1 : 0;

    // Database connection code
    $host = "localhost";
    $dbname = "job_fair";
    $username = "root"; // change if different
    $password = ""; // change if needed

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert the data into the database
        $stmt = $pdo->prepare("INSERT INTO registrations 
            (email, card_number, expiry_date, cvc, card_holder_name, address_line1, address_line2, postal_code, city, country, save_info) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            "montassarbenhassine44@gmail.com", // Hardcoded email
            $card_number, 
            $expiry_date, 
            $cvc, 
            $card_holder_name,
            $address_line1, 
            $address_line2, 
            $postal_code, 
            $city, 
            $country, 
            $save_info
        ]);

        echo "<h2>Merci ! Votre inscription a été enregistrée avec succès.</h2>";

    } catch (PDOException $e) {
        echo "Erreur de connexion ou d'insertion : " . $e->getMessage();
    }
}
?>
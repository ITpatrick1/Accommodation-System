<?php
$host = 'localhost';
$dbname = 'dragonhousedb';
$username = 'root';
$password = '';
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Use $_GET for testing purposes if you're accessing via URL
$sessionId   = isset($_GET["sessionId"]) ? $_GET["sessionId"] : '';
$serviceCode = isset($_GET["serviceCode"]) ? $_GET["serviceCode"] : '';
$phoneNumber = isset($_GET["phoneNumber"]) ? $_GET["phoneNumber"] : '';
$text        = isset($_GET["text"]) ? $_GET["text"] : '';

// Log the received input data
file_put_contents('debug_log.txt', "Received GET Data: " . print_r($_GET, true) . "\n", FILE_APPEND);

// Explode the text input to handle multiple stages in USSD flow
$textArray = explode("*", $text);
$userResponse = trim(end($textArray));

// Log the text array for debugging
file_put_contents('debug_log.txt', "Text Array: " . print_r($textArray, true) . "\n", FILE_APPEND);

// Initialize response
$response = "";

// Check if phone number is registered
$stmt = $pdo->prepare("SELECT GUESTID FROM tblguest WHERE G_PHONE = :phoneNumber");
$stmt->execute([':phoneNumber' => $phoneNumber]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // User not registered, proceed to registration
    if ($text == "") {
        $response = "CON Welcome! Please enter your first name:";
    } elseif (isset($textArray[0]) && !isset($textArray[1])) {
        $response = "CON Enter your last name:";
    } elseif (isset($textArray[1]) && !isset($textArray[2])) {
        $response = "CON Enter your city:";
    } elseif (isset($textArray[2]) && !isset($textArray[3])) {
        $response = "CON Enter your address:";
    } elseif (isset($textArray[3]) && !isset($textArray[4])) {
        $response = "CON Enter your nationality:";
    } elseif (isset($textArray[4]) && !isset($textArray[5])) {
        $response = "CON Enter your email:";
    } elseif (isset($textArray[5]) && !isset($textArray[6])) {
        $response = "CON Create a username:";
    } elseif (isset($textArray[6]) && !isset($textArray[7])) {
        $response = "CON Create a password:";
    } elseif (isset($textArray[7])) {
        // Register user
        $stmt = $pdo->prepare("INSERT INTO tblguest (G_FNAME, G_LNAME, G_CITY, G_ADDRESS, G_PHONE, G_NATIONALITY, G_EMAIL, G_UNAME, G_PASS, LOCATION) 
                               VALUES (:fname, :lname, :city, :address, :phone, :nationality, :email, :uname, :pass, :location)");
        $stmt->execute([
            ':fname' => $textArray[0],
            ':lname' => $textArray[1],
            ':city' => $textArray[2],
            ':address' => $textArray[3],
            ':phone' => $phoneNumber,
            ':nationality' => $textArray[4],
            ':email' => $textArray[5],
            ':uname' => $textArray[6],
            ':pass' => password_hash($textArray[7], PASSWORD_BCRYPT),
            ':location' => $textArray[2] // Assuming city as the location
        ]);

        $response = "END Registration successful! You can now access the menu.";
    }
} else {
    // User already registered, show the main menu
    if ($text == "") {
        $response = "CON Welcome to the Accommodation System \n";
        $response .= "1. View Available Properties \n";
        $response .= "2. Book a Property \n";
        $response .= "3. Check My Bookings \n";
        $response .= "4. Exit";
    } elseif ($text == "1") {
        // View available properties
        $stmt = $pdo->query("SELECT ROOMID, ROOM, PRICE FROM tblroom WHERE STATUS = 'Available'");
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($properties) {
            $response = "CON Available Properties: \n";
            foreach ($properties as $property) {
                $response .= "{$property['ROOMID']}. {$property['ROOM']} - \${$property['PRICE']} \n";
            }
            $response .= "0. Go Back";
        } else {
            $response = "END No properties available at the moment.";
        }
    } elseif ($text == "2") {
        // Prompt for property ID to book
        $response = "CON Enter Property ID to Book: ";
    } elseif (isset($textArray[1]) && is_numeric($textArray[1])) {
        // Property ID entered, prompt for start date
        $propertyId = $textArray[1];
        $response = "CON Enter Start Date (YYYY-MM-DD): ";
    } elseif (isset($textArray[2]) && preg_match('/\d{4}-\d{2}-\d{2}/', $textArray[2])) {
        // Start date entered, prompt for end date
        $response = "CON Enter End Date (YYYY-MM-DD): ";
    } elseif (isset($textArray[3]) && preg_match('/\d{4}-\d{2}-\d{2}/', $textArray[3])) {
        // End date entered, process booking
        $propertyId = $textArray[1];
        $startDate = $textArray[2];
        $endDate = $textArray[3];

        // Check if property is still available
        $stmt = $pdo->prepare("SELECT STATUS FROM tblroom WHERE ROOMID = :propertyId");
        $stmt->execute([':propertyId' => $propertyId]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($property && $property['STATUS'] === 'Available') {
            // Insert booking into the database
            $stmt = $pdo->prepare("INSERT INTO tblreservation (CONFIRMATIONCODE, TRANSDATE, ROOMID, ARRIVAL, DEPARTURE, GUESTID, PRORPOSE, STATUS, BOOKDATE) 
                                   VALUES (:confirmationCode, NOW(), :roomId, :arrival, :departure, :guestId, 'Booking via USSD', 'Pending', NOW())");
            $stmt->execute([
                ':confirmationCode' => uniqid(),
                ':roomId' => $propertyId,
                ':arrival' => $startDate,
                ':departure' => $endDate,
                ':guestId' => $user['GUESTID']
            ]);

            // Update room availability
            $stmt = $pdo->prepare("UPDATE tblroom SET STATUS = 'Not Available' WHERE ROOMID = :propertyId");
            $stmt->execute([':propertyId' => $propertyId]);

            $response = "END Booking successful! Property ID: $propertyId from $startDate to $endDate.";
        } else {
            $response = "END Sorry, this property is no longer available.";
        }
    } elseif ($text == "3") {
        // Check user's bookings
        $stmt = $pdo->prepare("SELECT R.ROOMID, R.ARRIVAL, R.DEPARTURE, P.ROOM 
                               FROM tblreservation R 
                               JOIN tblroom P ON R.ROOMID = P.ROOMID 
                               WHERE R.GUESTID = :guestId");
        $stmt->execute([':guestId' => $user['GUESTID']]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($bookings) {
            $response = "END Your Bookings: \n";
            foreach ($bookings as $booking) {
                $response .= "{$booking['ROOM']} from {$booking['ARRIVAL']} to {$booking['DEPARTURE']} \n";
            }
        } else {
            $response = "END You have no bookings.";
        }
    } elseif ($text == "4") {
        // Exit the USSD session
        $response = "END Thank you for using the Accommodation System!";
    } else {
        // Handle invalid input or go back
        $response = "END Invalid input. Please try again.";
    }
}

// Output the response
header('Content-type: text/plain');
echo $response;
?>

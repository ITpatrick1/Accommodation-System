

<?php
$host = 'localhost';
$dbname = 'dragonhousedb';
$username = 'root';
$password = '';
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Fetch inputs from the user
$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"];

// Explode the text input to handle multiple stages in USSD flow
$textArray = explode("*", $text);
$userResponse = trim(end($textArray));

// Initialize response
$response = "";

// USSD Menu Logic
if ($text == "") {
    // Initial menu
    $response  = "CON Welcome to the Accommodation System \n";
    $response .= "1. View Available Properties \n";
    $response .= "2. Book a Property \n";
    $response .= "3. Check My Bookings \n";
    $response .= "4. Exit";
} elseif ($text == "1") {
    // View available properties
    $stmt = $pdo->query("SELECT id, title, price FROM properties WHERE availability_status = 1");
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($properties) {
        $response = "CON Available Properties: \n";
        foreach ($properties as $property) {
            $response .= "{$property['id']}. {$property['title']} - \${$property['price']} \n";
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
    $stmt = $pdo->prepare("SELECT availability FROM properties WHERE id = :propertyId");
    $stmt->execute([':propertyId' => $propertyId]);
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($property && $property['availability']) {
        // Insert booking into the database
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, property_id, start_date, end_date) VALUES ((SELECT id FROM users WHERE phone_number = :phoneNumber), :propertyId, :startDate, :endDate)");
        $stmt->execute([
            ':phoneNumber' => $phoneNumber,
            ':propertyId' => $propertyId,
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);

        // Update property availability
        $stmt = $pdo->prepare("UPDATE properties SET availability = 0 WHERE id = :propertyId");
        $stmt->execute([':propertyId' => $propertyId]);

        $response = "END Booking successful! Property ID: $propertyId from $startDate to $endDate.";
    } else {
        $response = "END Sorry, this property is no longer available.";
    }
} elseif ($text == "3") {
    // Check user's bookings
    $stmt = $pdo->prepare("SELECT properties.title, bookings.start_date, bookings.end_date FROM bookings JOIN properties ON bookings.property_id = properties.id WHERE bookings.user_id = (SELECT id FROM users WHERE phone_number = :phoneNumber)");
    $stmt->execute([':phoneNumber' => $phoneNumber]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($bookings) {
        $response = "END Your Bookings: \n";
        foreach ($bookings as $booking) {
            $response .= "{$booking['title']} from {$booking['start_date']} to {$booking['end_date']} \n";
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

// Output the response
header('Content-type: text/plain');
echo $response;
?>

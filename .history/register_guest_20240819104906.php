<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "dragonhousedb"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $G_FNAME = $conn->real_escape_string($_POST['G_FNAME']);
    $G_LNAME = $conn->real_escape_string($_POST['G_LNAME']);
    $G_CITY = $conn->real_escape_string($_POST['G_CITY']);
    $G_ADDRESS = $conn->real_escape_string($_POST['G_ADDRESS']);    
    $G_PHONE = $conn->real_escape_string($_POST['G_PHONE']);
    $G_NATIONALITY = $conn->real_escape_string($_POST['G_NATIONALITY']);
    $G_EMAIL = $conn->real_escape_string($_POST['G_EMAIL']);
    $G_UNAME = $conn->real_escape_string($_POST['G_UNAME']);
    $G_PASS = sha1($conn->real_escape_string($_POST['G_PASS'])); // Hash the password

    $sql = "INSERT INTO tblguest (G_FNAME, G_LNAME, G_CITY, G_ADDRESS, G_PHONE, G_NATIONALITY, G_EMAIL, G_UNAME, G_PASS) 
            VALUES ('$G_FNAME', '$G_LNAME', '$G_CITY', '$G_ADDRESS', '$G_PHONE', '$G_NATIONALITY', '$G_EMAIL', '$G_UNAME', '$G_PASS')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Registration successful!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .registration-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px #00000050;
            width: 300px;
        }
        .registration-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="registration-form">
        <h2>Register</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="G_FNAME">First Name:</label>
                <input type="text" id="G_FNAME" name="G_FNAME" required>
            </div>
            <div class="form-group">
                <label for="G_LNAME">Last Name:</label>
                <input type="text" id="G_LNAME" name="G_LNAME" required>
            </div>
            <div class="form-group">
                <label for="G_CITY">City:</label>
                <input type="text" id="G_CITY" name="G_CITY" required>
            </div>
            <div class="form-group">
                <label for="G_ADDRESS">Address:</label>
                <input type="text" id="G_ADDRESS" name="G_ADDRESS" required>
            </div>
            <div class="form-group">
                <label for="DBIRTH">Date of Birth:</label>
                <input type="date" id="DBIRTH" name="DBIRTH" required>
            </div>
            <div class="form-group">
                <label for="G_PHONE">Phone:</label>
                <input type="text" id="G_PHONE" name="G_PHONE" required>
            </div>
            <div class="form-group">
                <label for="G_NATIONALITY">Nationality:</label>
                <input type="text" id="G_NATIONALITY" name="G_NATIONALITY" required>
            </div>
            <div class="form-group">
                <label for="G_COMPANY">Company:</label>
                <input type="text" id="G_COMPANY" name="G_COMPANY" required>
            </div>
            <div class="form-group">
                <label for="G_CADDRESS">Company Address:</label>
                <input type="text" id="G_CADDRESS" name="G_CADDRESS" required>
            </div>
            <div class="form-group">
                <label for="G_EMAIL">Email:</label>
                <input type="email" id="G_EMAIL" name="G_EMAIL" required>
            </div>
            <div class="form-group">
                <label for="G_UNAME">Username:</label>
                <input type="text" id="G_UNAME" name="G_UNAME" required>
            </div>
            <div class="form-group">
                <label for="G_PASS">Password:</label>
                <input type="password" id="G_PASS" name="G_PASS" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register">
            </div>
        </form>
    </div>
</body>
</html>

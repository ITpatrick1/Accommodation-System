<?php
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "dragonhousedb"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $G_FNAME = trim($_POST['G_FNAME']);
    $G_LNAME = trim($_POST['G_LNAME']);
    $G_CITY = trim($_POST['G_CITY']);
    $G_ADDRESS = trim($_POST['G_ADDRESS']);
    $G_PHONE = trim($_POST['G_PHONE']);
    $G_NATIONALITY = trim($_POST['G_NATIONALITY']);
    $G_EMAIL = trim($_POST['G_EMAIL']);
    $G_UNAME = trim($_POST['G_UNAME']);
    $G_PASS = sha1(trim($_POST['G_PASS'])); // Hash the password

    // Validate email format
    if (!filter_var($G_EMAIL, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }

    // Check if any field is empty
    if (empty($G_FNAME) || empty($G_LNAME) || empty($G_CITY) || empty($G_ADDRESS) ||
        empty($G_PHONE) || empty($G_NATIONALITY) || empty($G_EMAIL) || empty($G_UNAME) || empty($G_PASS)) {
        $error = "All fields are required.";
    }

    if (empty($error)) {
        $sql = "INSERT INTO tblguest (G_FNAME, G_LNAME, G_CITY, G_ADDRESS, G_PHONE, G_NATIONALITY, G_EMAIL, G_UNAME, G_PASS) 
                VALUES ('$G_FNAME', '$G_LNAME', '$G_CITY', '$G_ADDRESS', '$G_PHONE', '$G_NATIONALITY', '$G_EMAIL', '$G_UNAME', '$G_PASS')";

        if ($conn->query($sql) === TRUE) {
            $success = "Registration successful!";
            header("refresh:2;url=index.php"); // Redirect after 2 seconds
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .registration-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        .registration-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007BFF;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form-group input:focus {
            border-color: #007BFF;
            outline: none;
        }
        .form-group input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 16px;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #ff0000;
            margin-bottom: 10px;
            text-align: center;
        }
        .success-message {
            color: #28a745;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="registration-form">
        <h2>Guest Registration</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="G_FNAME">First Name:</label>
                <input type="text" id="G_FNAME" name="G_FNAME" value="<?php echo isset($G_FNAME) ? $G_FNAME : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="G_LNAME">Last Name:</label>
                <input type="text" id="G_LNAME" name="G_LNAME" value="<?php echo isset($G_LNAME) ? $G_LNAME : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="G_CITY">City:</label>
                <input type="text" id="G_CITY" name="G_CITY" value="<?php echo isset($G_CITY) ? $G_CITY : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="G_ADDRESS">Address:</label>
                <input type="text" id="G_ADDRESS" name="G_ADDRESS" value="<?php echo isset($G_ADDRESS) ? $G_ADDRESS : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="G_PHONE">Phone:</label>
                <input type="text" id="G_PHONE" name="G_PHONE" value="<?php echo isset($G_PHONE) ? $G_PHONE : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="G_NATIONALITY">Nationality:</label>
                <input type="text" id="G_NATIONALITY" name="G_NATIONALITY" value="<?php echo isset($G_NATIONALITY) ? $G_NATIONALITY : ''; ?>" required>
            </div>           
            <div class="form-group">
                <label for="G_EMAIL">Email:</label>
                <input type="email" id="G_EMAIL" name="G_EMAIL" value="<?php echo isset($G_EMAIL) ? $G_EMAIL : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="G_UNAME">Username:</label>
                <input type="text" id="G_UNAME" name="G_UNAME" value="<?php echo isset($G_UNAME) ? $G_UNAME : ''; ?>" required>
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

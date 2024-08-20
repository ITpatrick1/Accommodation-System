<?php 
require_once("../includes/initialize.php");

// Check if the form to update guest information has been submitted
if (isset($_POST['submit'])) {
    // Create a new Guest object
    $guest = new Guest();
    
    // Set the guest properties from POST data
    $guest->G_FNAME = $_POST['name'];
    $guest->G_LNAME = $_POST['last'];
    $guest->G_CITY = $_POST['city'];
    $guest->G_ADDRESS = $_POST['address'];
    $guest->G_PHONE = $_POST['phone'];
    $guest->G_NATIONALITY = $_POST['nationality'];
    $guest->G_UNAME = $_POST['username'];
    $guest->G_PASS = sha1($_POST['pass']); // Hash the password

    // Update the guest record in the database
    $guest->update($_SESSION['GUESTID']);
    
    // Redirect to the index page
    header("Location: " . WEB_ROOT . "index.php");
    exit();
}

// Check if the form to upload a photo has been submitted
if (isset($_POST['savephoto'])) {
    // Check if an image file has been selected
    if (!isset($_FILES['image']['tmp_name']) || $_FILES['image']['tmp_name'] == '') {
        message("No Image Selected!", "error");
        redirect(WEB_ROOT . "index.php");
    } else {
        // Get the uploaded file details
        $file = $_FILES['image']['tmp_name'];
        $image = addslashes(file_get_contents($file));
        $image_name = addslashes($_FILES['image']['name']);
        $image_size = getimagesize($file);
        
        // Validate if the file is an image
        if ($image_size === false) {
            message("That's not an image!");
            redirect(WEB_ROOT . "index.php");
        } else {
            // Set the upload directory and move the file
            $location = "guest/photos/" . $_FILES["image"]["name"];
            move_uploaded_file($file, $location);
            
            // Create a new Guest object
            $guest = new Guest();
            $guest->LOCATION = $location;
            
            // Update the guest record with the new photo location
            $guest->update($_SESSION['GUESTID']);
            
            // Redirect to the index page
            redirect(WEB_ROOT . "index.php");
        }
    }
}
?>

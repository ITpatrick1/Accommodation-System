<?php
require_once("../includes/initialize.php");
session_start(); // Ensure session handling is initialized

// Check if guest ID is set in the session
if (!isset($_SESSION['GUESTID'])) {
    die("Guest ID is not set. Please log in again.");
}

$guestId = $_SESSION['GUESTID'];

// Handle guest profile update
if (isset($_POST['submit'])) {
    $guest = new Guest();
    $guest->G_FNAME = $_POST['name'];
    $guest->G_LNAME = $_POST['last'];
    $guest->G_CITY = $_POST['city'];
    $guest->G_ADDRESS = $_POST['address'];
    $guest->DBIRTH = date_format(date_create($_POST['dbirth']), 'Y-m-d');
    $guest->G_PHONE = $_POST['phone'];
    $guest->G_NATIONALITY = $_POST['nationality'];
    $guest->G_COMPANY = $_POST['company'];
    $guest->G_CADDRESS = $_POST['caddress'];
    $guest->ZIP = $_POST['zip'];
    $guest->update($guestId);

    echo "<script type='text/javascript'>
        window.location = '" . WEB_ROOT . "index.php';
    </script>";
}

// Handle photo upload
if (isset($_POST['savephoto'])) {
    if (!isset($_FILES['image']['tmp_name'])) {
        message("No Image Selected!", "error");
        redirect(WEB_ROOT . "index.php");
    } else {
        $file = $_FILES['image']['tmp_name'];
        $image = addslashes(file_get_contents($file));
        $image_name = addslashes($_FILES['image']['name']);
        $image_size = getimagesize($file);

        if ($image_size == FALSE) {
            message("That's not an image!");
            redirect(WEB_ROOT . "index.php");
        } else {
            $location = "guest/photos/" . $_FILES["image"]["name"];
            move_uploaded_file($file, "photos/" . $_FILES["image"]["name"]);

            $g = new Guest();
            $g->LOCATION = $location;
            $g->update($guestId);

            redirect(WEB_ROOT . "index.php");
        }
    }
}
?>

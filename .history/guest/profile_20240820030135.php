<?php 
require_once("../includes/initialize.php");
session_start(); // Ensure session is started

// Check if session exists and retrieve guest data
if(!isset($_SESSION['GUESTID'])) {
    die("Guest ID is not set in session. Please log in again.");
}

$guest = new Guest();
$res = $guest->single_guest($_SESSION['GUESTID']);

if(!$res) {
    die("No guest found with the provided ID.");
}
?>

<section class="content-header">
    <h1>My Account <small>Account Details</small></h1>
</section>

<form class="form-horizontal" action="<?php echo WEB_ROOT; ?>guest/update.php" method="post" onsubmit="return personalInfo()" name="personal">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <br/>
                    <div class="box-body no-padding">
                        <div class="table-responsive mailbox-messages">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="name">FIRST NAME:</label>
                                <div class="col-md-8">
                                    <input name="name" type="text" value="<?php echo isset($res->G_FNAME) ? $res->G_FNAME : ''; ?>" class="form-control input-sm" id="name" />
                                </div>
                            </div>
                            <!-- Repeat for other fields: last name, city, address, etc. -->
                            ...
                        </div>
                    </div>
                    <div class="box-footer no-padding">
                        <div class="pull-right"> 
                            <input name="submit" type="submit" value="Save" class="btn btn-primary" onclick="return personalInfo();" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>

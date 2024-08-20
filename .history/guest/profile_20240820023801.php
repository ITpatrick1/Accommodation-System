<?php 
require_once("../includes/initialize.php");
session_start(); // Ensure session is started

if(!isset($_SESSION['GUESTID'])) {
    die("Guest ID is not set in session. Please log in again.");
}
else{


// Check if session exists and retrieve guest data

    $guest = new Guest();
    $res = $guest->single_guest($_SESSION['GUESTID']);
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
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="last">LAST NAME:</label>
                                <div class="col-md-8">
                                    <input name="last" type="text" value="<?php echo isset($res->G_LNAME) ? $res->G_LNAME : ''; ?>" class="form-control input-sm" id="last" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="city">CITY:</label>
                                <div class="col-md-8">
                                    <input name="city" type="text" value="<?php echo isset($res->G_CITY) ? $res->G_CITY : ''; ?>" class="form-control input-sm" id="city" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="address">ADDRESS:</label>
                                <div class="col-md-8">
                                    <input name="address" type="text" value="<?php echo isset($res->G_ADDRESS) ? $res->G_ADDRESS : ''; ?>" class="form-control input-sm" id="address" />
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="phone">PHONE:</label>
                                <div class="col-md-8">
                                    <input name="phone" type="text" value="<?php echo isset($res->G_PHONE) ? $res->G_PHONE : ''; ?>" class="form-control input-sm" id="phone" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="nationality">NATIONALITY:</label>
                                <div class="col-md-8">
                                    <input name="nationality" type="text" value="<?php echo isset($res->G_NATIONALITY) ? $res->G_NATIONALITY : ''; ?>" class="form-control input-sm" id="nationality" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="username">USERNAME:</label>
                                <div class="col-md-8">
                                    <input name="username" type="text" value="<?php echo isset($res->G_UNAME) ? $res->G_UNAME : ''; ?>" class="form-control input-sm" id="username" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="password">PASSWORD:</label>
                                <div class="col-md-8">
                                    <input name="pass" type="password" class="form-control input-sm" id="password" />
                                </div>
                            </div>
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
<script type="text/javascript">
    // Validation logic
    function personalInfo(){
        var fields = ["name", "last", "city", "address", "phone", "username", "password"];
        for(var i = 0; i < fields.length; i++){
            var field = document.forms["personal"][fields[i]].value;
            if(field == ""){
                alert("All fields are required!");
                return false;
            }
        }
        return true;
    }
</script>

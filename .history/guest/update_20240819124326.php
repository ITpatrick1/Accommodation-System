 <?php 
require_once("../includes/initialize.php");
//echo date_format(date_create($_POST['dbirth']), 'Y-m-d');

if(isset($_POST['submit'])){
$guest = New Guest();
$guest->G_FNAME          = $_POST['name'];  undefined property 'G_FNAME'
$guest->G_LNAME          = $_POST['last'];undefined property 'G_LNAME'
$guest->G_CITY           = $_POST['city'];undefined property 'G_CITY'
$guest->G_ADDRESS        = $_POST['address'] ;undefined property 'G_ADDRESS'
$guest->G_PHONE          = $_POST['phone']; undefined property 'G_PHONE'
$guest->G_NATIONALITY    = $_POST['nationality']; undefined property 'G_NATIONALITY'
$guest->G_UNAME          = $_POST['username']; undefined property 'G_UNAME'
$guest->G_PASS           = sha1($_POST['pass']);undefined property 'G_FNAME'
$guest->update($_SESSION['GUESTID']); 

?>
<script type="text/javascript">
	window.location = '<?php echo WEB_ROOT; ?>index.php';
</script>

<?php  } 

if(isset($_POST['savephoto'])){
	if (!isset($_FILES['image']['tmp_name'])) {
			message("No Image Selected!", "error");
			 redirect(WEB_ROOT."index.php");
		}else{
			$file=$_FILES['image']['tmp_name'];
			$image= addslashes(file_get_contents($_FILES['image']['tmp_name']));
			$image_name= addslashes($_FILES['image']['name']);
			$image_size= getimagesize($_FILES['image']['tmp_name']);
			
			if ($image_size==FALSE) {
				message("That's not an image!");
				redirect(WEB_ROOT."index.php");
		   }else{
			
		
				$location= "guest/photos/".$_FILES["image"]["name"];
				
				move_uploaded_file($_FILES["image"]["tmp_name"], "photos/".$_FILES["image"]["name"]);
				
	 				$g = new Guest(); 
			    	
					$g->LOCATION = $location;
					$g->update($_SESSION['GUESTID']); 
					
				 	// message("Room Image Upadated successfully!", "success");
				 	// unset($_SESSION['id']);
				 	 redirect(WEB_ROOT."index.php");
 			}
 		}

}

?>

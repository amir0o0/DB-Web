<?php require_once("../../includes/session.php"); ?>
<?php require_once("../../includes/db_connection.php"); ?>
<?php require_once("../../includes/functions.php"); ?>
<?php require_once("../../includes/validation_functions.php"); ?>
<?php
$username = "";
if(isset($_POST['submit'])) {
	$username = mysql_prep($_POST["username"]);
	$password = mysql_prep($_POST["password"]);
	// Process the form
	// validations
	$required_fields = array("username", "password");
	validate_presences($required_fields);
	if(empty($errors)) {
		// Attempt Login
		$found_member = authenticate($username, $password);
		if($found_member) {
			// Success
			$_SESSION["customer_id"]   = $found_member["customer_id"];
			$_SESSION["customer_name"] = $found_member["customer_name"];
			if(isset($_SESSION["cart"])) {
				$_SESSION["message"] .= "You can book all the performances in this cart.";
				redirect_to("../public_cart.php");
			} else {
				redirect_to("../customer.php");
			}                                   // this is encrypted 'admin' for password | username & password = admin
		} elseif($_POST["username"] == "admin" && $_POST["password"] == "admin") {
			$_SESSION["admin"] = "admin";
			redirect_to("../staff.php");
		} else {
			// Failure
			$_SESSION["errors"] = "Incorrect Username, Password!";
			redirect_to($_SERVER["HTTP_REFERER"]);
		}
	} else {
		$_SESSION["errors"] = "Username and or password can't be blank!";
		redirect_to($_SERVER["HTTP_REFERER"]);
	}
} else {
}
?>
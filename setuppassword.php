<?php
	session_start();
	require_once('inc/top_layout.php'); 
	require_once('inc/functions.php'); 

	//Checks to see if there's a password error message present
	if(isset($_SESSION['pwdMsg'])) {
		//Set the current password error variable equal to its corresponding sessions variable
		$pwdMsg = $_SESSION['pwdMsg'];

		//Unset or remove the value of the session variable
		unset($_SESSION['pwdMsg']);
	}

	//If the create password button is submitted
	if(isset($_POST['pass-submit'])) {
		$result = verifyPassword($_POST['curr-password'], $_SESSION['email']);
		if($result == true) {
			$result2 = confirmPassword($_POST['new-password'], $_POST['conf-password']);
			if($result2 == true) {
				$updtPwdResult = changePassword($_POST['new-password'], $_SESSION['email']);
				if($updtPwdResult == true) {
					$_SESSION['pwdMsg'] = "<div class='alert alert-success'>
											  <strong>Success!</strong> Password set!
											</div>";
					header("Location: setuppassword.php");
				}else {
					$_SESSION['pwdMsg'] = "<div class='alert alert-danger'>
												<strong>Error!</strong> Trouble setting password. Please contact admin!
											</div>";
					header("Location: setuppassword.php");
				}
			}else {
				$_SESSION['pwdMsg'] = "<div class='alert alert-danger'>
												<strong>Error!</strong> Confirmed password doesn't match the new password
											</div>";
				header("Location: setuppassword.php");
			}
		}else {
			$_SESSION['pwdMsg'] = "<div class='alert alert-danger'>
										<strong>Error!</strong> Incorrect current password!
									</div>";
			header("Location: setuppassword.php");
		}
	}
?>
	<?php
		//If there's a current password error message, display it here 
		if(isset($pwdMsg)) { 
			 echo $pwdMsg;
		}
	?>
	<form method="post" id="login-form" action="setuppassword.php">
		<div class="form-group row">
			<label for="raihn-password" class="col-sm-2 col-form-label">Current Password</label>
			<div class="col-sm-10">
			  <input type="password" class="form-control" id="curr-password" name="curr-password" placeholder="Password" required>
			</div>
		</div>
		<div class="form-group row">
			<label for="raihn-password" class="col-sm-2 col-form-label">New Password</label>
			<div class="col-sm-10">
			  <input type="password" class="form-control" id="new-password" name="new-password" placeholder="Password" required>
			</div>
		</div>
		<div class="form-group row">
			<label for="raihn-password" class="col-sm-2 col-form-label">Confirm New Password</label>
			<div class="col-sm-10">
			  <input type="password" class="form-control" id="conf-password" name="conf-password" placeholder="Password" required>
			</div>
		</div>
		<div id="submit-button">
			<button id="pass-submit" name="pass-submit" type="submit" class="btn btn-primary">Create Password</button>
		</div>	
	</form>

<?php require_once('inc/bottom_layout.php'); ?>
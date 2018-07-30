<?php 
	session_start();
	require_once('inc/top_layout.php'); 
	require_once('inc/functions.php');

	//Checks to see if the login error is present
	if(isset($_SESSION['loginError'])) {
		//Set the login value from the session variable and store into a new variable
		$loginError = $_SESSION['loginError'];

		//Unset or remove the value of the session variable
		unset($_SESSION['loginError']);
	}

	//If the sign in button was pressed, verify the user
	if(isset($_POST['login-submit'])) {
		//First verifies the user's email
		$result = verifyEmail($_POST['raihn-email']);
		if($result == true) {
			//If the email is correct, then verify the password
			$result2 = verifyPassword($_POST['raihn-password'], $_POST['raihn-email']);
			if($result2 == true) {
				//Sets a session variable to store the user's email
				$_SESSION['email'] = $_POST['raihn-email'];
				header("Location: ./index.php");
			}else {
				//Create login error that will be saved as a session variable
				$_SESSION['loginError'] = "<div class='alert alert-danger'>
												<strong>Error!</strong> Incorrect email or password!
											</div>";
				
				//Redirect the user back to the login page
				header("Location: login.php");
			}
		}else {
			$_SESSION['loginError'] = "<div class='alert alert-danger'>
												<strong>Error!</strong> Incorrect email or password!
											</div>";
			header("Location: login.php");
		}
	}
?>
	<?php
		//If there's a login error message, display it here 
		if(isset($loginError)) { 
			 echo $loginError;
		}
	?>
	<form method="post" id="login-form" action="login.php">
		<div class="form-group row">
			<label for="raihn-email" class="col-sm-2 col-form-label">Email</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" id="raihn-email" name="raihn-email" placeholder="Enter email" required>
			</div>
		</div>
		<div class="form-group row">
			<label for="raihn-password" class="col-sm-2 col-form-label">Password</label>
			<div class="col-sm-10">
			  <input type="password" class="form-control" id="raihn-password" name="raihn-password" placeholder="Password" required>
			</div>
		</div>
	  	<div class="form-group">
	    	<div class="form-check" id="rmembr-chckbox">
				<input class="form-check-input" type="checkbox" id="gridCheck">
				<label class="form-check-label" for="gridCheck">Remember Me</label>
			</div>
		</div>
		<div id="submit-button">
			<button id="login-submit" name="login-submit" type="submit" class="btn btn-primary">Sign In</button>
		</div>	
	</form>

<?php require_once('inc/bottom_layout.php'); ?>
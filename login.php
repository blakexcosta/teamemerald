<?php 
	session_start();
	require_once('inc/top_layout.php'); 
	require_once('inc/functions.php');

	if(isset($_SESSION['loginError'])) {
		$loginError = $_SESSION['loginError'];
		unset($_SESSION['loginError']);
	}

	if(isset($_POST['login-submit'])) {
		$result = verifyUser($_POST['raihn-email'], $_POST['raihn-password']);
		if($result == true) {
			header("Location: ./index.php");
		}else {
			$_SESSION['loginError'] = "<div class='error-msg'>Incorrect Email or Password!</div>";
			header("Location: login.php");
		}
	}
?>
	<?php 
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
		<div id="login-button">
			<button id="login-submit" name="login-submit" type="submit" class="btn btn-primary">Sign In</button>
		</div>	
	</form>

<?php require_once('inc/bottom_layout.php'); ?>
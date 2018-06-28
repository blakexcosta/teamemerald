<?php include_once 'inc/top_layout.php'; ?>

	<form id="login-form">
		<div class="form-group row">
			<label for="raihn-email" class="col-sm-2 col-form-label">Email</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" id="raihn-email" placeholder="Enter email">
			</div>
		</div>
		<div class="form-group row">
			<label for="raihn-password" class="col-sm-2 col-form-label">Password</label>
			<div class="col-sm-10">
			  <input type="password" class="form-control" id="raihn-password" placeholder="Password">
			</div>
		</div>
	  	<div class="form-group">
	    	<div class="form-check" id="rmembr-chckbox">
				<input class="form-check-input" type="checkbox" id="gridCheck">
				<label class="form-check-label" for="gridCheck">Remember Me</label>
			</div>
		</div>
		<div id="login-button">
			<button type="submit" class="btn btn-primary">Sign In</button>
		</div>	
	</form>
	
	<form action="createEvent.php" method="post">
		Create Event: <input type="text" name="createEvent"><br>
		<input type="submit">
	</form>

<?php include_once 'inc/bottom_layout.php'; ?>

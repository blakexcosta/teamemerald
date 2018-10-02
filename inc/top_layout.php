<!DOCTYPE html>
<html lang="en">
<head>
		<title>RAIN Scheduler</title>

		<!-- Minified Bootstrap v3.7.7 CSS -->
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">

		<!-- Minified Font Awesome CSS -->
		<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">

      <!-- Minified FullCalendar CSS -->
      <link rel="stylesheet" type="text/css" href="./css/fullcalendar.min.css">

		<!-- Main custom CSS -->
		<link rel="stylesheet" type="text/css" href="./css/styles.css">
</head>
<body>
	<div id="title-section">
		<a href="./index.php">
			<div id="logo">
				<div id="logo-img">
					<img src="./img/RAIHNlogo.PNG"  alt="RAIHN logo"/>
				</div>
				<div id="logo-text">
					<div id="logo-text-pt1">
						<h1>RAIHN</h1>
					</div>
					<div id="logo-text-pt2">
						<span id="scheduler-font">Scheduler App</span>
					</div>
				</div>
			</div>
		</a>
	</div>
	<nav class="navbar navbar-default custom-bg">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	  	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    	<ul class="nav navbar-nav">
		      	<li class="nav-item">
		        	<a class="nav-link" href="./index.php">Home</span></a>
		      	</li>
		      	<?php /* if logged in */ if(isset($_SESSION['email'])): ?>
		      		<?php /* if logged in */ if(isset($_SESSION['role']) && ($_SESSION['role'] == "Bus Driver" || $_SESSION['role'] == "Admin")): ?>
				      	<li class="nav-item">
				        	<a class="nav-link" href="./busdriverroster.php">Bus Driver Roster</a>
				      	</li>
                        <li class="nav-item">
                            <a class="nav-link" href="./testBlackoutsDRIVERS.php">Test Blackout Drivers</a>
                        </li>
			      	<?php endif; ?>
			      	<?php /* if logged in */ if(isset($_SESSION['role']) && ($_SESSION['role'] == "Congregation" || $_SESSION['role'] == "Admin")): ?>
				      	<li class="nav-item">
				        	<a class="nav-link" href="./congregationroster.php">Host Congregation Roster</a>
				      	</li>
				      	<li class="nav-item">
				        	<a class="nav-link" href="./congregationcoordinators.php">Congregation Coordinators</a>
				      	</li>
				      	<li class="nav-item">
				        	<a class="nav-link" href="./inputblackouts.php">Input Blackouts</a>
				      	</li>
                        <li class="nav-item">
                            <a class="nav-link" href="./testBlackoutsPage.php">Test Blackout Congregation</a>
                        </li>
			      	<?php endif; ?>
			      	<?php /* if logged in */ if(isset($_SESSION['role']) && $_SESSION['role'] == "Admin"): ?>
			      		<li class="nav-item">
				        	<a class="nav-link" href="./insertDateData.php">Insert Date Data</a>
				      	</li>
			      	<?php endif; ?>
			      	<li class="nav-item">
			        	<a class="nav-link" href="./setuppassword.php">Change Password</a>
			      	</li>
			      	<li class="nav-item">
			        	<a class="nav-link" href="./logout.php">Logout</a>
			      	</li>
		      	<?php else: ?>
		      	<li class="nav-item">
		        	<a class="nav-link" href="./login.php">Login</a>
		      	</li>
		      	<?php endif; ?>
			</ul>
	  	</div>
	</nav>

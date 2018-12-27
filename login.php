<?php 
	session_start();
	if(isset($_SESSION['login'])){ 
    	header("Location: profile.php");
	}
	$pass = "ITP303_2018";
	$db = "sophiesm_intercolor";
	$connection = new mysqli("303.itpwebdev.com", "sophiesm_user", $pass, $db);
	if (!$connection) {
    	die("Connection failed: " . mysqli_connect_error());
	} 
	$error = "";

	if (isset($_POST['signup'])) {
		//check if username already exists
		$username = "'".$_POST['username']."'";
		$pw = "'".hash("md5", $_POST['password'])."'";
		$check = "SELECT * FROM users WHERE username=".$username.";";
		$results = $connection->query($check);
		if ($results->num_rows > 0) {
			$error .= "Username ".$username." already taken";
		} else {
			$insert = "INSERT INTO users VALUES (NULL, ".$username.", ".$pw.", '#FFA719', NULL, NULL, 0);";
			$result = $connection->query($insert);
			if ($result) {
				$results2 = $connection->query($check);
				$user = $results2->fetch_assoc();
				$id = $user['id'];
				$_SESSION['login'] = true;
				$_SESSION['user_id'] = $id;
				header("Location: profile.php");
			} else {
				$error .= "There was an error creating your account!";
				$error .= mysqli_error($connection);
			}
		}
	} else if (isset($_POST['login'])) {
		//check if user exists, check if pw matches
		$username = "'".$_POST['username']."'";
		$pw = hash("md5", $_POST['password']);
		$check = "SELECT * FROM users WHERE username=".$username.";";
		$results = $connection->query($check);
		if ($results->num_rows == 0) {
			$error .= "User with username ".$username." does not exist!";
		} else {
			$result = $results->fetch_assoc();
			if ($result['password'] != $pw) {
				$error .= "Incorrect password!";
			} else {
				$results2 = $connection->query($check);
				$user = $results2->fetch_assoc();
				$id = $user['id'];
				$_SESSION['login'] = true;
				$_SESSION['user_id'] = $id;
				header("Location: profile.php");
			}
		}
	} 

	mysqli_close($connection);

?>

<html>
	<head>
		<title>Intercolor | Log In</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i" rel="stylesheet">
		<link href="button.css" rel="stylesheet">
		<link href="navbar.css" rel="stylesheet">
		<link href="style.css" rel="stylesheet">
		<link href="login.css" rel="stylesheet">
		<link rel="shortcut icon" href="favicon.png" type="image/x-icon">
		<link rel="icon" href="favicon.png" type="image/x-icon">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="login.js"></script>
	</head>

	<body>
		<img id="logo" src="logo.png">
		<div class="nav">
	      <ul>
	        <li><a href="index.html">home</a></li>
	        <li><a href="play.php">play</a></li>
	        <li><a href="palette.php">palette creator</a></li>
	        <li><a href="profile.php">profile</a></li>
	      </ul>
	    </div>

	    <div id="outer">
	    	<div id="inner">
	    	<?php
	    		if ($error != "") {
	    			echo "<p> Error: ".$error."</p>";
	    		}
	    	 ?>
	    	<h3>log in</h3>
	    	<form id="login" action="login.php" method="POST">
	    		<div class="row">
	    			<p>username: </p>
	    			<input id="user_li" type="text" name="username" required>
	    		</div>
	    		<div class="row">
	    		<p>password: </p>
	    			<input id="pw_li" type="password" name="password" required>
	    		</div>
	    		<input type="hidden" name="login" value="true">
	    		<button id="login_button" class="primary">log in</button>
	    	</form>
	    	<p>- or -</p>
	    	<h3>sign up</h3>
	    	<form id="signup" action="login.php" method="POST">
	    		<div class="row">
	    		<p>username: </p>
	    		<input id="user_su" type="text" name="username" required>
	    		</div>
	    		<div class="row">
	    		<p>password: </p>
	    		<input id="pw_su" type="password" name="password" required>
	    		</div>
	    		<div class="row">
	    		<p>confirm password: </p>
	    		<input id="confirm_su" type="password" name="password" required>
	    		</div>
	    		<input type="hidden" name="signup" value="true">
	    		<button id="signup_button" class="primary">sign up</button>
	    	</form>
	    </div>
	</div>
	</body>

</html>
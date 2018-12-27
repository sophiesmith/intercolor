<?php 
	session_start();
	if (isset($_POST['logout'])) {
		session_destroy();
		header("Location: login.php");
	}
	if(!isset($_SESSION['login'])){ 
    	header("Location: login.php");
	}
	$pass = "ITP303_2018";
	$db = "sophiesm_intercolor";
	$connection = new mysqli("303.itpwebdev.com", "sophiesm_user", $pass, $db);
	if (!$connection) {
    	die("Connection failed: " . mysqli_connect_error());
	} 
	$sql = "SELECT * FROM users WHERE id=".$_SESSION['user_id'].";";
	$results = $connection->query($sql);
	$user = $results->fetch_assoc();

	$paletteQuery = "SELECT * FROM palettes WHERE creator_id=".$_SESSION['user_id'].";";
	$palettes = $connection->query($paletteQuery);

	mysqli_close($connection);
?>

<html>
	<head>
		<title>Intercolor | Profile</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i" rel="stylesheet">
		<link href="button.css" rel="stylesheet">
		<link href="navbar.css" rel="stylesheet">
		<link href="style.css" rel="stylesheet">
		<link rel="shortcut icon" href="favicon.png" type="image/x-icon">
		<link rel="icon" href="favicon.png" type="image/x-icon">
		<style>
			.button {
				font-size: 16px;
				width: 3vw;
			}

			p {
				display: inline-block;
				margin-right: 10px;
			}

			#inner {
				flex-direction: column;
			}
		</style>
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
	    	echo "<h3>".$user['username']."'s profile</h3>";
	    	echo "<h4>best score: ".$user['best_score']."</h4>";
	    	echo "<h4>average score: ".$user['avg_score']."</h4>";
	    	echo "<h4>saved palettes: </h4>";
	    	while ($palette = $palettes->fetch_assoc()) {
	    		echo "<div class='palette'>
	    		<p>".$palette['name'].": </p> 
	    		<a class='button primary' href='palette.php?id=".$palette['id']."'>Edit</a>
	    		</div>";
	    	}
	    	?>
	    	<form method="POST" action="profile.php">
	    		<input type="hidden" name="logout" value="true">
	    		<button class="primary danger">log out</button>
	    	</form>
	    </div>
	    </div>
	</body>

</html>
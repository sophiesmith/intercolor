<?php 
	session_start();
	$pass = "ITP303_2018";
	$db = "sophiesm_intercolor";
	$connection = new mysqli("303.itpwebdev.com", "sophiesm_user", $pass, $db);
	if (!$connection) {
    	die("Connection failed: " . mysqli_connect_error());
	} 
	$error = "";
	$sql = "SELECT * FROM games ORDER BY RAND() LIMIT 1;";
	$results = $connection->query($sql);
	$result = $results->fetch_assoc();

	$scoreQuery = "SELECT scores.*, users.username FROM scores 
	INNER JOIN users ON scores.user_id=users.id 
	WHERE game_id=".$result['id']." ORDER BY num_moves LIMIT 10;";
	$scores = $connection->query($scoreQuery);

	if (isset($_POST['score'])) {
		if (!isset($_SESSION['login'])) {
			$error .= "You must be logged in to submit scores!";
		} else {
			$userQuery = "SELECT * FROM users WHERE id=".$_SESSION['user_id'].";";
			$users = $connection->query($userQuery);
			$user = $users->fetch_assoc();
			$score = $_POST['score'];
			$bestScore = $user['best_score'];
			$numGames = $user['games_played'] + 1;	
			if ($score < $bestScore || !isset($bestScore)) {
				$bestScore = $score;
			}
			$avgScore = $user['avg_score'] + ($score - $user['avg_score']) / $numGames;
			$update = "UPDATE users SET best_score=".$bestScore.", avg_score=".$avgScore.", games_played=".$numGames." WHERE id=".$_SESSION['user_id'].";";
			$updateResult = $connection->query($update);
			if (!$updateResult) {
				$error .= "Error updating user scores";
				$error .= mysqli_error($connection);
			}

			$save = "INSERT INTO scores VALUES (NULL, ".$_SESSION['user_id'].", ".$_POST['game'].", ".$score.");";
			$saveResult = $connection->query($save);
			if ($saveResult) {
				$error .= "Your score was submitted!";
			} else {
				$error .= "There was an error submitting your score.";
				$error .= mysqli_error($connection);
			}
		}
	}

	mysqli_close($connection);
?>

<html>
	<head>
		<title>Intercolor | Play</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i" rel="stylesheet">
		<link href="button.css" rel="stylesheet">
		<link href="navbar.css" rel="stylesheet">
		<link href="style.css" rel="stylesheet">
		<link href="play.css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script src="jquery.ui.touch-punch.min.js"></script>
  		<script src="play.js"></script>
  		<link rel="shortcut icon" href="favicon.png" type="image/x-icon">
		<link rel="icon" href="favicon.png" type="image/x-icon">

	<!--	<script type="text/javascript">
			$(function() {
			    $(".color").draggable();
			}); 
		</script> -->
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
	    	<?php
	    		if ($error != "") {
	    			echo "<p>".$error."</p>";
	    		}
	    	 ?>
			<h3 id="result">sort these colors</h3>
			<div id="scores">
				<h4>top 10 high scores</h4>
				<ol>
				<?php 
					if ($scores->num_rows == 0) {
						echo "<p>Be the first to submit your score!</p>";
					}
					while ($score = $scores->fetch_assoc()) {
						echo "<li>". $score['username'].": ".$score['num_moves']."</li>";
					}
					
				?>
				</ol>
				<form id="save_score" method="POST" action="play.php">
					<input id="moves" type="hidden" name="score" value="">
					<?php
					echo "<input type='hidden' name='game' value='".$result['id']."'>";
					?>
					<button class="primary success">submit my score</button>
				</form>
				<button id="restart" class="primary danger">don't submit and restart</button>
			</div>
			<div id="game">
				<?php
					echo "<div id='c1' style='background-color:". $result['color1'].";' class='color'></div>";
					echo "<div id='c2' style='background-color:". $result['color2'].";' class='color movable'></div>";
					echo "<div id='c3' style='background-color:". $result['color3'].";' class='color movable'></div>";
					echo "<div id='c4' style='background-color:". $result['color4'].";' class='color movable'></div>";
					echo "<div id='c5' style='background-color:". $result['color5'].";' class='color movable'></div>";
					echo "<div id='c6' style='background-color:". $result['color6'].";' class='color movable'></div>";
					echo "<div id='c7' style='background-color:". $result['color7'].";' class='color movable'></div>";
					echo "<div id='c8' style='background-color:". $result['color8'].";' class='color movable'></div>";
					echo "<div id='c9' style='background-color:". $result['color9'].";' class='color movable'></div>";
					echo "<div id='c10' style='background-color:". $result['color10'].";' class='color'></div>";
				
				?>
			</div>
		</div>
		<div id="buttons">
				<button id="reshuffle" class="primary danger">reshuffle</button>
				
				<button id="submit" class="primary success">submit</button>
			</div>
	</body>

</html>
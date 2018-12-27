<?php 
	session_start();
	
	$pass = "ITP303_2018";
	$db = "sophiesm_intercolor";
	$connection = new mysqli("303.itpwebdev.com", "sophiesm_user", $pass, $db);
	if (!$connection) {
    	die("Connection failed: " . mysqli_connect_error());
	} 
	$error = "";
	$msg = "";
	$color1 = "";
	$color2 = "";
	$color3 = "";
	$color4 = "";
	$color5 = "";
	$color6 = "";
	$name = "";
	$illegalAccess;
	//updating existing palette
	$id = $_GET['id'];
	if (isset($id)) {
		$getPalette = "SELECT * FROM palettes WHERE id=".$id.";";
		$palettes = $connection->query($getPalette);
		if ($palettes->num_rows > 0) {
			$palette = $palettes->fetch_assoc();
			//if logged in user is not the palette's creator, deny access
			if ($palette['creator_id'] != $_SESSION['user_id']) {
				$error .= "You don't have permission to view this palette!";
				$illegalAccess = true;
			} else {
				$color1 = $palette['color1'];
				$color2 = $palette['color2'];
				$color3 = $palette['color3'];
				$color4 = $palette['color4'];
				$color5 = $palette['color5'];
				$color6 = $palette['color6'];
				$name = $palette['name'];
			}
		} else {
			$error .= "Palette not found!";
		}
	}
	//if user deletes palette
	if (isset($_POST['delete_id'])) {
		$delete = "DELETE FROM palettes WHERE id=".$_POST['delete_id'].";";
			$deleted = $connection->query($delete);
			if (!$deleted) {
				$error .= "Could not delete this palette";
				$error .= mysqli_error($connection);
			} else {
				$msg .= "Palette was successfully deleted.";
			}
	}
	//if user tries to save palette
	if (isset($_POST['name'])) {
		if(!isset($_SESSION['login'])){ 
		    $error .= "You must be logged in to save palettes!";
		} else {
			//update, don't insert
			if (isset($_POST['curr_id']) && !empty($_POST['curr_id'])) {
				$sql = "UPDATE palettes SET name='".$_POST['name']."', color1='".$_POST['color1']."', color2='".$_POST['color2']."', color3='".$_POST['color3']."', color4='".$_POST['color4']."', color5='".$_POST['color5']."', color6='".$_POST['color6']."' WHERE id=".$_POST['curr_id'].";";
			} else {
				$sql = "INSERT INTO palettes VALUES (NULL, ".$_SESSION['user_id'].", '".$_POST['name']."', '".$_POST['color1']."', '".$_POST['color2']."', '".$_POST['color3']."', '".$_POST['color4']."', '".$_POST['color5']."', '".$_POST['color6']."');";
			}
			$results = $connection->query($sql);
			if ($results) {
				$msg .= "Palette successfully saved to your profile!";
			} else {
				$error .= "There was an error creating your palette.";
				$error .= mysqli_error($connection);
			}
		}
	}

?>

<html>
	<head>
		<title>Intercolor | Palette Creator</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i" rel="stylesheet">
		<link href="button.css" rel="stylesheet">
		<link href="navbar.css" rel="stylesheet">
		<link href="style.css" rel="stylesheet">
		<link href="palette.css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="farbtastic.js"></script>
		<script src="palette.js"></script>
		<link rel="stylesheet" href="farbtastic.css" type="text/css" />
		<link rel="shortcut icon" href="favicon.png" type="image/x-icon">
		<link rel="icon" href="favicon.png" type="image/x-icon">
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
	    		if ($msg != "") {
	    			echo "<p>".$msg."</p>";
	    		}
	    	 ?>
	    	<div id="outerpicker">
	    		<input type="text" id="color" name="color" value="#123456" />
				<div id="colorpicker"></div>
				<button id="save_color" class="primary">save color</button>		
			</div>
			<form id="palette_form" method="POST" action="palette.php">
				<div id="palette">
					<?php
					echo "<div class='palette_color'>
						<button type='button' class='primary danger remove'>X</button>
						<input id='c1' type='hidden' name='color1' value='".$color1."'>
					</div>
		    		<div class='palette_color'>
		    			<button type='button' class='primary danger remove'>X</button>
		    			<input id='c2' type='hidden' name='color2' value='".$color2."'>
		    		</div>
		    		<div class='palette_color'>
		    			<button type='button' class='primary danger remove'>X</button>
		    			<input id='c3' type='hidden' name='color3' value='".$color3."'>
		    		</div>
		    		<div class='palette_color'>
		    			<button type='button' class='primary danger remove'>X</button>
		    			<input id='c4' type='hidden' name='color4' value='".$color4."'>
		    		</div>
		    		<div class='palette_color'>
		    			<button type='button' class='primary danger remove'>X</button>
		    			<input id='c5' type='hidden' name='color5' value='".$color5."'>
		    		</div>
		    		<div class='palette_color'>
		    			<button type='button' class='primary danger remove'>X</button>
		    			<input id='c6' type='hidden' name='color6' value='".$color6."'>
		    		</div>";
		    		?>
	    		</div>
	    		<div id="name">
	    			<?php
	    			if (!isset($illegalAccess)) {
		    			echo "<p>palette name: </p>";
		    			
		    			echo "<input id='palette_name' type='text' name='name' value='".$name."'>";
		    			echo "<input type='hidden' name='curr_id' value=".$id.">";
		    			
		    			echo "<button class='primary success'>save palette</button>";
	    			}
	    			?>
	    		</div>
    		</form>
    		<form method="POST" action="palette.php">
    			<?php
    				if (isset($id) && !isset($illegalAccess)) { 
    					echo "<input type='hidden' name='delete_id' value=".$id.">";
    					echo "<button onclick=\"return confirm('Are you sure you want to delete this palette?')\" class='primary danger'>delete palette</button>";
    				}
	    			?>
    		</form>
    	</div>
	    </div>
		
	</body>

	<script type="text/javascript">

	  $(document).ready(function() {

	    $('#colorpicker').farbtastic('#color');

	  });

	  $(document).ready(function() {
	 	$('.palette_color').each(function(i, obj) {
        var color = $(this).find("input").val();
       	$(this).css('background', color);
       	if (color != "") {
       		$(this).addClass("full");
       	}
    });
})

	</script>
</html>
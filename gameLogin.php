<?php
session_start();
//include dark ambience
include("auto_audio.php");
?>

<html>
<head>
	<title>Login...</title>
</head>
<body bgcolor="#000000">
<div align="center">
<font color="white">
<h2><font color="orange">The Haunting of Merch Hill</font></h2>
<p>Welcome. Please login.</p>

<?php
if (!ISSET($_POST['submit'])) {
	$do_query=false;
	$error=false;
}
else {
	$do_query=false;
	$error=false;
	//store entered username into session so it stays in the form
	$_SESSION['username']=$_POST['username'];
	//error if username and password do not match
	if ($_POST['username']=='' or $_POST['password']=='') {
		$error=true;
		$error_message="<font color='red'><strong>Please enter all information.</strong></font>";
	}
	//username and password match
	if ($_POST['username']!='' and $_POST['password']!='') {
		$do_query=true;
	}
}

//item fetch error
if (ISSET($_SESSION['loginerror'])) {
	echo $_SESSION['loginerror'];
}

?>

<!--login form-->
<br /><br />
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	Username<br />
	<input type="text" name="username" placeholder="Username" value="<?php if(ISSET($_SESSION['username'])) {
	echo $_SESSION['username']; }?>"><br>
	Password<br />
	<input type="password" name="password" placeholder="Password"><br>
	<input type="submit" name="submit" value="Login"><br>
</form>

<p>The Haunting of Merch Hill is a text-based horror game. Use the information the game has given you to explore the terrifying Merch Hill.</p>
<?php

//if setting user_id session is outside of if statement embedded in while loop, value of session is the last user's id in the user table of MySQL. WHY?!?!


if ($do_query==true){
	//connect to db, get user_data info
	require("dbConnect.php");
	$query="SELECT * FROM user_data";
	$result=mysqli_query($dbc,$query) or die("Query error");
	while ($row=mysqli_fetch_array($result)) {
		$db_username=$row['username'];
		$db_password=$row['password'];
		$id=$row['id'];
		//stop at row with the same username and pwd as entered in form
		if ($_POST['username']==$db_username and $_POST['password']==$db_password) {
			$_SESSION['id']=$id;
			//get and store user's game data in session

			//reset mysqli_fetch_array, get user's game_data, take them to game
			$query = "SELECT * FROM game_data";
			$result = mysqli_query($dbc,$query) or die("yo I had a query error");
			while ($game_row = mysqli_fetch_array($result)) {
				$user_id = $game_row['user_id'];
				
				if ($_SESSION['id'] == $user_id) {
					$_SESSION['location'] = $game_row['location'];
					$_SESSION['sanity'] = $game_row['sanity'];
					$_SESSION['equipment'] = $game_row['equipment'];
					$_SESSION['skill'] = $game_row['skill'];
					$_SESSION['cur_obj'] = $game_row['current_objective'];
					$_SESSION['comp_obj'] = $game_row['completed_objectives'];
					$_SESSION['one_events'] = $game_row['one_time_events'];
					$_SESSION['companion'] = $game_row['companion'];
					
					//take user to game after this process, close connection
					mysqli_close($dbc);
					header("Location:skill_choose.php");
				}
			}
		}
		
	}
	//if it gets this far, something went wrong
	$error=true;
	$error_message="<font color='red'><strong>Incorrect username or password.</strong></font>";
}

//error message
if ($error==true) {
	echo $error_message;
}
?>
<br /><img src="http://i7.lbp.me/img/ft/037c6e8213a4fe870112c9387bb8229b82518468.jpg">
<?php
//go to login
include("nav.php");
?>

<!--opens new tab with the audio-->
<p>Want to have a creepier experience? <br />
Press "Click me" to open up some creepy audio in a new tab.<br />
<a href="ambience.php" target="_blank">Click me</a></p>

</body>
</html>
<html>
<head>
	<title>Sign up</title>
</head>

<body bgcolor="#000000">
<div align="center">
<font color="white">

<?php
session_start();

//play dark ambience
include("auto_audio.php");
?>

<p><h2><font color="orange">The Haunting of Merch Hill</font></h2>
</p>

<?php
//these values are like this when submit isn't pressed
if(!ISSET($_POST['submit'])) {
	$_POST['username']='';
	$_POST['password']='';
	$_POST['password_confirm']='';
	$_POST['email']='';
	$error=false;
	$do_query=false;
	$username_taken=false;
	$email_taken=false;
}
//when submit is pressed
else {
	$error=false;
	$do_query=false;
	$username_taken=false;
	$email_taken=false;
	//errors in submission
	if ($_POST['username']=='' or $_POST['password']=='' or $_POST['password_confirm']=='' or $_POST['email']=='') {
		$error=true;
		$error_message='<font color="red"><strong>Please fill in all fields.</strong></font>';
	}
	if (($_POST['username']!='' and $_POST['password']!='' and $_POST['email']!='') and ($_POST['password'] != $_POST['password_confirm'])) {
		$error=true;
		$error_message='<font color="red"><strong>Passwords do not match.</strong></font>';
	}
	//successful submission
	if (($_POST['username']!='' and $_POST['password']!='' and $_POST['email']!='') and ($_POST['password'] == $_POST['password_confirm'])) {
		$do_query=true;
	}
	//these strings are now equal to the corresponding form values
	$username=$_POST['username'];
	$password=$_POST['password'];
	$email=$_POST['email'];
}

//store what the user enters into the session so those values will stay in the box
$_SESSION['username'] = $_POST['username'];
$_SESSION['email_hold'] = $_POST['email'];

?>
<!--the form-->
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	Username<br />
	<input type="text" name="username" placeholder="Username" value="<?php echo $_SESSION['username']; ?>"><br>
	Password / Confirm Password<br />
	<input type="password" name="password" placeholder="Password"><br>
	<input type="password" name="password_confirm" placeholder="Confirm password"><br>
	<p>Do not enter a password you use for other sites, such as Facebook. I will be able to see your password. Instead, type in something which is easy to remember.</p><br />
	Email (doesn't matter what you put here... just put something)<br />
	<input type="text" name="email" placeholder="Email" value="<?php echo $_SESSION['email_hold']; ?>"><br>
	<input type="submit" name="submit" value="Sign up"><br>
</form>

<br />
<img src="http://i0.lbp.me/img/ft/9e0d70902ad0147037e4d39de217f6f758b304b5.jpg"><br />
<?php

//show error message if error is true
if ($error==true) {
	echo $error_message;
}
//query to get info from database
if ($do_query==true) {
	require("dbConnect.php");
	
	$query="SELECT * FROM user_data";
	$result=mysqli_query($dbc,$query) or die("Query error");
	while ($row=mysqli_fetch_array($result)) {
		$db_username=$row['username'];
		$db_email=$row['email'];
		//check if the username and/or email already exist in db
		if ($_POST['username']==$db_username) {
			$username_taken=true;
		}
		if ($_POST['email']==$db_email) {
			$email_taken=true;
		}
	}
	//email and username don't already exist; add a new row to both tables in db
	if ($username_taken==false and $email_taken==false) {
		$query="INSERT INTO `game`.`user_data` (`id`, `username`, `password`, `email`, `d_o_r`, `img_url`) VALUES (NULL, '$username', '$password', '$email', NOW(), 'http://farm4.static.flickr.com/3840/15074294822_dbfe522487_m.jpg')";
		mysqli_query($dbc,$query) or die("Query error2");
		//get new id
		$user_id = mysqli_insert_id($dbc);
		$query="INSERT INTO `game`.`game_data` (`game_id`, `user_id`, `location`, `equipment`, `sanity`, `skill`, `current_objective`, `completed_objectives`, `one_time_events`, `companion`) VALUES (NULL, '$user_id', 'Dunwich Forest Entrance', 'flashlight,radio', '5', '', 'Explore to find Andy', '', '', '')";
		mysqli_query($dbc,$query) or die("Query error2fhfhfghdgdg");
		mysqli_close($dbc);
		header("Location:gameLogin.php");
	}
	//email and username already exist
	else {
		if ($username_taken==true) {
			echo "<font color='red'><strong>That username is unavailable.</font></strong>";
		}
		if ($email_taken==true) {
			echo "<br><font color='red'><strong>That email is already registered.</font></strong>";

		}
	}
}

//nav to get to login
include("login_nav.php");
?>
</div>
</font>
</body>
</html>
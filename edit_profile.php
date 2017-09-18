<?php
session_start();
//check if session_id is set
require ("check_id.php");
include("head_profile.php");
?>
<html>
<head>
	<title><?php echo "Edit " . $_SESSION['username']; ?></title>
<body bgcolor="#000000">
<font color="white">

<h2><font color='orange'>Edit profile...</font></h2>

<?php
if (ISSET($_POST['submit']) or !ISSET($_POST['submit'])) {
	$go=false;
	$username_taken=false;
}

//connect to db, get user's data
require("dbConnect.php");
$query="SELECT * FROM user_data";
$result=mysqli_query($dbc,$query) or die("Query error....");
while ($row=mysqli_fetch_array($result)) {
	$db_userid=$row['id'];
}
?>

<!--update info form-->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<p>
		<input type="text" name="new_imgurl" placeholder="New Avatar URL">
	</p>
	<p>
		<input type="text" name="new_username" placeholder="New username">
	</p>
	<br><br>
		<input type="password" name="password" placeholder="Password">
		<input type="password" name="password_confirm" placeholder="Confirm password">
	<br>Note: passwords must match in order to update password
	
	
	<p>
		<input type="submit" value="Update" name="submit">
		<input type="submit" value="Cancel" name="submit2">
	</p>
</form>

<?php

//these are empty if submit has not been pressed
if (!ISSET($_POST['submit'])) {
	$_POST['edit_bio']="";
	$_POST['new_imgurl']="";
	$_POST['new_username']="";
	$_POST['password']="";
	$_POST['password_confirm']="";
}
else {
	//connect to db, make sure new username does not exist
	require("dbConnect.php");
	$query="SELECT * FROM user_data";
	$result=mysqli_query($dbc,$query) or die("Query error");
	while ($row=mysqli_fetch_array($result)) {
		$db_username=$row['username'];
		if ($_POST['new_username']==$db_username) {
			$username_taken=true;
		}
	}
	//if new username does not exist, set these strings to corresponding post values
	if ($username_taken==false) {
		$new_imgurl=$_POST['new_imgurl'];
		$user_id=$_SESSION['id'];
		$new_username=$_POST['new_username'];
		$new_password=$_POST['password'];
		$go=true;
		
		//update information which had post values entered
		if ($new_imgurl!="") {
			$query="UPDATE user_data SET img_url='$new_imgurl' WHERE id='$user_id'";
			mysqli_query($dbc,$query) or die("Query error");
			echo "<br>Avatar has been updated! Refresh to make it appear.";
		}
		if ($new_username!="") {
			$query="UPDATE user_data SET username='$new_username' WHERE id='$user_id'";
			mysqli_query($dbc,$query) or die("Query error");
			$_SESSION['username']=$new_username;
			echo "<br>New username! Refresh to make it appear.";
		}
		if ($_POST['password']!="") {
			if ($_POST['password']==$_POST['password_confirm']) {
				$query="UPDATE user_data SET password='$new_password' WHERE id='$user_id'";
				mysqli_query($dbc,$query) or die("Query error");
			}
		}
		if ($new_username=="" and $new_imgurl=="" and $_POST['password']=="" and $_POST['password_confirm']=="") {
			echo "Nothing was entered to update.";
			$go=false;
		}
	}
	//error
	else {
		echo "<font color='red'><strong>That username is unavailable.</strong></font>";
		$go=false;
	}
}

//back to game
if (ISSET($_POST['submit2'])) {
	header("Location: main.php");
}
if ($go==true) {
	header("Location:user_profile.php");
}
?>
<br /><img src="http://t3.gstatic.com/images?q=tbn:ANd9GcToihkaadHy4woVQ4w5fThN8PtHoIgu8S2LdyTY5fnDhdbGcFq1" align="center">
<?php
mysqli_close($dbc);
?>
</body>
</html>
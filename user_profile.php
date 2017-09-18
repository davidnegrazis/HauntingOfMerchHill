<?php
session_start();
//check if session id is set
require ("check_id.php");
//include user info tab at top of page
include("head_profile.php");
?>
<html>
<head>
	<title><?php echo $_SESSION['username'] . "'s profile."; ?></title>
</head>
<body bgcolor="#000000" link="red">
<font color="white">
<h1><font color="orange"><?php echo $_SESSION['username']; ?>'s profile</font></h1><br />
<div align="right">
<a href="edit_profile.php">Edit profile</a><br />
</div>

<!--show user's avatar-->
<img src="<?php echo $db_imgurl; ?>" width="250" height="250">

</div>
<br><br><br>
<?php

//take user back to game when they press go back button
if (ISSET($_POST['submit'])) {
	header("Location: main.php");
}
//get user's personal data
require("dbConnect.php");
$query="SELECT * FROM user_data";
$result=mysqli_query($dbc,$query) or die("Query error");
while ($row=mysqli_fetch_array($result)) {
	$db_userid=$row['id'];
	if ($db_userid==$_SESSION['id']) {
		$db_imgurl=$row['img_url'];
	}
}

?>

<!--show user's game data-->
<div align="left">
<fieldset>
<p align="left"><font color="red" size="5"><strong>Current location: <?php echo $_SESSION['location']; ?></strong></font>
<p align="left" style="float:left"><font color="red" size="5"><strong>Sanity: </strong></font></p><br />
<?php
for ($c=0 ; $c < $_SESSION['sanity'] ; $c++) {
	echo "<img src='http://i.imgur.com/tLG5f5L.png' style='float:left'>";
}
?>
<br><br>
<p align="left"><font color="red" size="5"><strong>Current items: </strong></font>
<?php
for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
	if (count($_SESSION['equipment']) - $v != 1) {
		echo $_SESSION['equipment'][$v] . ", ";
	}
	else {
		echo $_SESSION['equipment'][$v];
	}
}
?>
</p>
<p align="left"><font color="red" size="5"><strong>Skill: <?php echo $_SESSION['skill']; ?></strong></font>
<img src="http://i.imgur.com/0ANuJel.gif" width="250" height="250" align="right">
<br><br>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="submit" name="submit" value="Back to game">
</form>
</font>
</body>
</html>
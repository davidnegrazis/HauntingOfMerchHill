<?php
?>
<html>
<body link="orange" vlink="orange">
<div style="float:right">

<?php
//connect to db
require("dbConnect.php");
//get user information
$query="SELECT * FROM user_data";
$result=mysqli_query($dbc,$query) or die("Query error");
while ($row=mysqli_fetch_array($result)) {
	$db_userid=$row['id'];
	if ($_SESSION['id']==$db_userid) {
		$db_imgurl=$row['img_url'];
	}
}
?>
<!--show info-->
<img src="<?php echo $db_imgurl; ?>" height="30" width="30" align="center"><br>
<?php
echo "<font color='red'>Signed in as: " . $_SESSION['username'] . "</font><br>";
echo "<a href='user_profile.php'>Your <strong>profile</strong></a> | <a href='logout.php'>Logout</a>";
?>
</div>
</body>
</html>
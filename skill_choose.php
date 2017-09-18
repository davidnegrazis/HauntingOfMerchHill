<html>
<head>
<title>Choose skill</title>
</head>
<body bgcolor="#000000" align="center" text="white">

<?php
//check if session id is set
session_start();
require ("check_id.php");

//take user to 
if (ISSET($_POST['submit'])) {
	if (ISSET($_POST['skill'])) {
		$_SESSION['skill'] = $_POST['skill'];
	}
	else {
		echo "Choose a skill.<br />";
	}
}
if ($_SESSION['skill'] != "") {
	header("Location: main.php");
}
?>

<!--skill list-->
<h2><font color="orange">Choose skill</font></h2>

<p>In the game, you will occasionally benefit from a special skill which you get to choose here.</p>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<fieldset>
<legend><h3><font color="red">PERCEPTION</font></h3></legend>
<p>Perception increases your ability to notice details. This skill is useful for uncovering clues which otherwise would not be revealed.</p>
<p><font color="red">Choose Perception skill:</font></p>
<input type="radio" name="skill" value="perception">
</fieldset>

<br />

<fieldset>
<legend><h3><font color="blue">STRENGTH</font></h3></legend>
<p>Strength improves your mental resistance to the environment along with your brawn. Sanity does not decrease as easily, and you can use your might to solve certain situations with force.</p>
<p><font color="blue">Choose Strength skill:</font></p>
<input type="radio" name="skill" value="strength">
</fieldset>

<fieldset>
<legend><h3><font color="orange">COLLECTOR</font></h3></legend>
<p>Collector increases your aptitude for finding useful items. With this skill, you will be able to spot and take beneficial items which otherwise wouldn't be found.</p>
<p><font color="orange">Choose Collector skill:</font></p>
<input type="radio" name="skill" value="collector">
</fieldset>

<input type="submit" name="submit" value="Start game">

</form>

</body>
</html>
<?php
if (!ISSET($_SESSION['id'])) {
	header("Location: gameLogin.php");
}
?>
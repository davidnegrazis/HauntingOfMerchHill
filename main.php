<?php
//start session
session_start();
?>
<html>
<head>
	<title>Begin your journey</title>
</head>
<body bgcolor="000000">
<font color="white">

<?php

//include header and sound
include("head_profile.php");
include('scenes.php');
include('msg.php');

//$_SESSION['inProgress'] ensures that the following logic doesn't run again even if the user's profile is visited; only once for every game session set
if (!ISSET($_SESSION['inProgress'])) {
	//check if session_id is set
	require ("check_id.php");
	//check skills
	if ($_SESSION['skill'] == "") {
			header("Location: skill_choose.php");
	}
	//explode items
	$_SESSION['equipment'] = explode(",",$_SESSION['equipment']);
	//explode other arrays
	$_SESSION['comp_obj'] = explode(",",$_SESSION['comp_obj']);
	$_SESSION['one_events'] = explode(",",$_SESSION['one_events']);
	
	$_SESSION['inProgress'] = true;
	$_SESSION['refresh'] = 0;
	
	$_SESSION['station'] = "gong_station.mp3";
	
	$_SESSION['convo'] = false;
	
	$_SESSION['convo_part'] = 1;
	
	$_SESSION['popup'] = false;
	
}

if (!ISSET($_POST['submit']) and $_SESSION['convo'] != true) {
	$_POST['command'] = "help";
	$msg = "";
	$img = "";
	$no_command = true;
	$finish = false;
	$_SESSION['popup'] = false;
}
else {
	$msg = $msg_unknown . "<br />Your command: <font color='green'>" . $_POST['command'] . "</font>";
	$img = "";
	$no_command = true;
	$finish = false;
}

//every x number of refreshes causes sanity loss
if (ISSET($_POST['submit']) and $_SESSION['convo'] == false) {
	$_SESSION['refresh'] = $_SESSION['refresh'] + 1;
	//strength increases refresh number for loss of sanity
	if ($_SESSION['skill'] == "strength") {
		$limit = 50;
	}
	else {
		$limit = 30;
	}
	if ($_SESSION['refresh'] >= $limit and $msg != $msg_nocommand) {
		$_SESSION['sanity'] = $_SESSION['sanity'] - 1;
		$_SESSION['refresh'] = 0;
		$msg = $msg_losesanitytime;
	}
}

//Dunwich Forest Entrance (first area)
if ($_SESSION['location'] == "Dunwich Forest Entrance") {
	$desc = $desc_dunwichentrance;
	if ($_POST['command'] == "go west") {
		$_SESSION['location'] = "North End of Dunwich Path";
		$msg = "";
	}
	if ($_POST['command'] == "go east") {
		$_SESSION['location'] = "Dunwich Cedar Tree";
		$msg = "";
	}
	if ($_POST['command'] == "go south" or $_POST['command'] == "go north") {
		$msg = $msg_notnav;
	}
	if ($_POST['command'] == "go back") {
		$msg = $msg_notapp;
	}
}

//Dunwich Cedar Tree
if ($_SESSION['location'] == "Dunwich Cedar Tree") {
		if ($_SESSION['skill'] == "perception") {
			$desc_cont = $descont_cedarpercept;
		}
		else {
			$desc_cont = "";
		}
	$desc = $desc_cedartree . $desc_cont;
	//open bag
	if ($_POST['command'] == "open bag" or $_POST['command'] == "check bag") {
		$_SESSION['location'] = "Cedar Bag - Looking inside the backpack";
		$check_error = true;
		$msg = "";
	}
	//go back to entrance
	if ($_POST['command'] == "go west" or $_POST['command'] == "go back") {
		$_SESSION['location'] = "Dunwich Forest Entrance";
		$desc = $desc_dunwichentrance;
		$msg = "";
	}
}

//Inside bag at cedar tree
if ($_SESSION['location'] == "Cedar Bag - Looking inside the backpack") {
	//display that there is a map if player has collector skill
	
	if ($_SESSION['skill'] == "collector") {
		$desc_cont = $descont_cedarmap;
	}
	else {
		$desc_cont = "";
	}
	//check if player has map or note in inventory
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "forest map") {
			$desc_cont = "";
		}
		if ($_SESSION['equipment'][$v] == "cedar tree note") {
			$desc = $desc_cedarempty;
			$has_note = true;
		}
	}
	//take map
	if ($_POST['command'] == "take map" and $desc_cont != "") {
		array_push($_SESSION['equipment'],"forest map");
		$desc_cont = "";
		$msg = $msg_takemap;
	}
	//description
	if (ISSET($has_note)) {
		$desc = $desc_cedarempty . $desc_cont;
	}
	else {
		//description
		$desc = $desc_cedarfull . $desc_cont;
		//take note
		if ($_POST['command'] == "take note" and !ISSET($has_note)) {
			array_push($_SESSION['equipment'],"cedar tree note");
			$desc = $desc_cedarempty . $desc_cont;
			$msg = $msg_takenote;
		}
	}
	//open bag
	if ($_POST['command'] == "check bag" or $_POST['command'] == "open bag" and empty($check_error)) {
		$msg = "You are already checking the bag.";
	}
	//if they try to take the bag
	if ($_POST['command'] == "take bag") {
		$msg = "You can't take the bag.";
	}
	//if they try to close bag
	if ($_POST['command'] == "close bag") {
		$_SESSION['location'] = "Dunwich Cedar Tree";
		$msg = "";
		if ($_SESSION['skill'] == "perception") {
			$desc_cont = $descont_cedarpercept;
		}
		else {
			$desc_cont = "";
		}
		$desc = $desc_cedartree . $desc_cont;
	}
	//"go back"
	if ($_POST['command'] == "go back") {
		$_SESSION['location'] = "Dunwich Forest Entrance";
		$desc = $desc_dunwichentrance;
		$msg = "";
	}
}
	
//North End of Dunwich Path
if ($_SESSION['location'] == "North End of Dunwich Path") {
	$desc = $desc_northend;
	//go south
	if ($_POST['command'] == "go south") {
		$_SESSION['location'] = "South End of Dunwich Path";
		$msg = "";
	}
	//go back
	if ($_POST['command'] == "go east") {
		$_SESSION['location'] = "Dunwich Forest Entrance";
		$desc = $desc_dunwichentrance;
	}
	if ($_POST['command'] == "go back") {
		$msg = $msg_notapp;
	}
}

//South End of Dunwich Path
if ($_SESSION['location'] == "South End of Dunwich Path") {
	$desc_cont = $descont_southendcd;
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-1") {
			$desc_cont = "";
		}
	}
	if ($_POST['command'] == "take cd" and $desc_cont !="") {
		array_push($_SESSION['equipment'],"recording-1");
		$desc_cont = "";
		$msg = $msg_takecd;
	}
	$desc = $desc_southend . $desc_cont;
	if ($_POST['command'] == "go east") {
		$_SESSION['location'] = "The Shed - Door";
		$msg = "";
	}
	if ($_POST['command'] == "go southwest") {
		$_SESSION['location'] = "Backside of the House";
		$msg = "";
	}
	if ($_POST['command'] == "go north") {
		$_SESSION['location'] = "North End of Dunwich Path";
		$desc = $desc_northend;
		$msg = "";
	}
	if ($_POST['command'] == "go back") {
		$msg = $msg_notapp;
	}
}

//Outside of shed
if ($_SESSION['location'] == "The Shed - Door") {
	//check if user has strength
	if ($_SESSION['skill'] == "strength") {
		$desc_cont = $descont_shedstrength;
	}
	elseif ($_SESSION['skill'] == "collector") {
		$desc_cont = $descont_collopendoor;
	}
	else {
		$desc_cont = "";
	}
	//description
	$desc = $desc_sheddoor . $desc_cont;
	
	//if they have strength
	if ($_SESSION['skill'] == "strength") {
		if ($_POST['command'] == "go in shed" or $_POST['command'] == "open door" or $_POST['command'] == "use door" or $_POST['command'] == "enter shed") {
			$_SESSION['location'] = "Inside the Shed";
			$msg = $msg_shedstrength;
			$_POST['command'] = "";
			$no_command = false;
		}
	}
	//they don't have strength, have collector
	elseif ($_SESSION['skill'] == "collector") {
		if ($_POST['command'] == "open door" or $_POST['command'] == "use door" or $_POST['command'] == "enter shed") {
			$go = false;
			//check if they have bobby pin
			for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
				if ($_SESSION['equipment'][$v] == "screwdriver and bobby pin") {
					$go = true;
				}
			}
			if ($go == true) {
				$_SESSION['location'] = "Inside the Shed";
				$_POST['command'] = "";
				$no_command = false;
				$msg = $msg_usescrewbob;
			}
			else {
				$msg = $msg_shednostrength;
			}
		}
	}
	//perception is their skill
	else {
		if ($_POST['command'] == "open door") {
			$msg = $msg_shednostrength;
		}
	}
	//leave
	if ($_POST['command'] == "go west" or $_POST['command'] == "go back") {
		$_SESSION['location'] = "South End of Dunwich Path";
		$desc_cont = $descont_southendcd;
		$msg = "";
		for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
			if ($_SESSION['equipment'][$v] == "recording-1") {
				$desc_cont = "";
			}
		}
		$desc = $desc_southend . $desc_cont;
	}	
}

//inside the shed
if ($_SESSION['location'] == "Inside the Shed") {
	$desc_cont = $descont_shedcd;
	//check if cd is in inventory
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-2") {
			$desc_cont = "";
		}
	}
	//take cd
	if ($_POST['command'] == "take cd" and $desc_cont != "") {
		array_push($_SESSION['equipment'],"recording-2");
		$desc_cont = "";
		$msg = $msg_takecd;
	}
	//check if they have teddy or if they've used the teddy
	$desc_cont2 = $descont_shedteddy;
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "shed teddy bear") {
			$desc_cont2 = "";
		}
	}
	for ($v=0 ; $v < count($_SESSION['one_events']) ; $v++) {
		if ($_SESSION['one_events'][$v] == "used_shedteddy") {
			$desc_cont2 = "";
		}
	}
	//take teddy
	if ($_POST['command'] == "take teddy bear" and $desc_cont2 != "") {
		array_push($_SESSION['equipment'],"shed teddy bear");
		$desc_cont2 = "";
		$msg = $msg_taketeddy;
	}
	//description
	$desc = $desc_shedinside . $desc_cont . $desc_cont2;
	//leave
	if ($_POST['command'] == "go back" or $_POST['command'] == "leave shed" or $_POST['command'] == "exit shed" or $_POST['command'] == "use door" or $_POST['command'] == "open door") {
		$_SESSION['location'] = "The Shed - Door";
		$msg = "";
		//check if user has strength
		if ($_SESSION['skill'] == "strength") {
			$desc_cont = $descont_shedstrength;
		}
		elseif ($_SESSION['skill'] == "collector") {
			$desc_cont = $descont_collopendoor;
		}
		else {
			$desc_cont = "";
		}
		$desc = $desc_sheddoor . $desc_cont;
	}
}

//backside of house
if ($_SESSION['location'] == "Backside of the House") {
	$desc = $desc_housebackside;
	//go around house to porch
	if ($_POST['command'] == "go around house" or $_POST['command'] == "go around the house") {
		$_POST['command'] = "";
		$no_command = false;
		$_SESSION['location'] = "Frontside of the House";
		$msg = "";
	}
	//go back
	if ($_POST['command'] == "go northeast" or $_POST['command'] == "go back") {
		$_SESSION['location'] = "South End of Dunwich Path";
		$msg = "";
		$desc_cont = $descont_southendcd;
		for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
			if ($_SESSION['equipment'][$v] == "recording-1") {
				$desc_cont = "";
			}
		}
		$desc = $desc_southend . $desc_cont;
	}
}

//Frontside of the House
if ($_SESSION['location'] == "Frontside of the House") {
	$desc = $desc_housefrontside;
	//go to house
	if ($_POST['command'] == "go north" or $_POST['command'] == "go to house") {
		$_SESSION['location'] = "The Porch of the House";
		$msg = "";
	}
	//go south
	if ($_POST['command'] == "go south") {
		$_SESSION['location'] = "Deeper Forest";
		$_POST['command'] = "";
		$no_command = "";
		$msg = "";
	}
	if ($_POST['command'] == "go around house" or $_POST['command'] == "go back") {
		$_SESSION['location'] = "Backside of the House";
		$desc = $desc_housebackside;
		$msg = "";
	}
}

//deeper forest
if ($_SESSION['location'] == "Deeper Forest") {
	//extra description
	if ($_SESSION['skill'] == "perception") {
		$desc_cont = $descont_deeperpercept;
	}
	else {
		$desc_cont = "";
	}
	$desc = $desc_deeperforest . $desc_cont;
	//go west
	if ($_POST['command'] == "go west") {
		$_SESSION['location'] = "Cabin Area";
		$msg = "";
	}
	if ($_POST['command'] == "go east") {
		$_SESSION['location'] = "The Noose";
		$msg = "";
	}
	//go south
	if ($_POST['command'] == "go south" or $_POST['command'] == "continue south") {
		$_SESSION['location'] = "Dark Forest";
		$msg = "";
	}
	//go back
	if ($_POST['command'] == "go north" or $_POST['command'] == "go back") {
		$_SESSION['location'] = "Frontside of the House";
		$desc = $desc_housefrontside;
		$msg = "";
	}
}

//dark forest
if ($_SESSION['location'] == "Dark Forest") {
	$desc_cont = $descont_darkteddy;
	//check if they have teddy or if they've used the teddy
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "dark teddy bear") {
			$desc_cont = "";
		}
	}
	for ($v=0 ; $v < count($_SESSION['one_events']) ; $v++) {
		if ($_SESSION['one_events'][$v] == "used_darkteddy") {
			$desc_cont = "";
		}
	}
	//take teddy
	if ($_POST['command'] == "take teddy bear" and $desc_cont != "") {
		array_push($_SESSION['equipment'],"dark teddy bear");
		$desc_cont = "";
		$msg = $msg_taketeddy;
	}
	$desc = $desc_darkforest . $desc_cont;
	//go south
	if ($_POST['command'] == "go south" or $_POST['command'] == "continue south") {
		if ($_SESSION['companion'] == "andy") {
			$_SESSION['convo'] = true;
			$_SESSION['convo_type'] = "jacob";
			$msg = "";
		}
		else {
			$msg = $msg_needandy;
		}
	}
	//go back
	if ($_POST['command'] == "go back" or $_POST['command'] == "go north") {
		$_SESSION['location'] = "Deeper Forest";
		$msg = "";
		if ($_SESSION['skill'] == "perception") {
			$desc_cont = $descont_deeperpercept;
		}
		else {
			$desc_cont = "";
		}
		$desc = $desc_deeperforest . $desc_cont;
	}
}

//the noose
if ($_SESSION['location'] == "The Noose") {
	$desc_cont = $descont_noosenote;
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "My note to society") {
			$desc_cont = "";
		}
	}
	//take note
	if ($_POST['command'] == "take note" and $desc_cont != "") {
		array_push($_SESSION['equipment'],"My note to society");
		$msg = $msg_takenote;
		$desc_cont = "";
	}
	$desc = $desc_thenoose . $desc_cont;
	//go back
	if ($_POST['command'] == "go back" or $_POST['command'] == "go west") {
		$_SESSION['location'] = "Deeper Forest";
		$msg = "";
		if ($_SESSION['skill'] == "perception") {
			$desc_cont = $descont_deeperpercept;
		}
		else {
			$desc_cont = "";
		}
		$desc = $desc_deeperforest . $desc_cont;
	}
}

//cabin area
if ($_SESSION['location'] == "Cabin Area") {
	$desc_cont = $descont_cabinareacd;
	//check if cd is in inventory
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-3") {
			$desc_cont = "";
		}
	}
	//take cd
	if ($_POST['command'] == "take cd" and $desc_cont != "") {
		array_push($_SESSION['equipment'],"recording-3");
		$desc_cont = "";
		$msg = $msg_takecd;
	}
	$desc = $desc_cabinarea . $desc_cont;
	//go south
	if ($_POST['command'] == "go south") {
		$_SESSION['location'] = "Front of South Cabin";
		$msg = "";
	}
	//go east
	if ($_POST['command'] == "go east") {
		$_SESSION['location'] = "Front of East Cabin";
		$msg = "";
	}
	//go back
	if ($_POST['command'] == "go back") {
		$_SESSION['location'] = "Deeper Forest";
		$msg = "";
		if ($_SESSION['skill'] == "perception") {
			$desc_cont = $descont_deeperpercept;
		}
		else {
			$desc_cont = "";
		}
		$desc = $desc_deeperforest . $desc_cont;
	}
}

//south cabin front
if ($_SESSION['location'] == "Front of South Cabin") {
	if ($_SESSION['skill'] == "perception") {
		$desc_cont = $descont_southcabinpercept;
	}
	elseif ($_SESSION['skill'] == "strength") {
		$desc_cont = $descont_southcabinstrength;
	}
	else {
		$desc_cont = "";
	}
	$desc = $desc_southcabinfront . $desc_cont;
	//go around
	if ($_POST['command'] == "go around" or $_POST['command'] == "go around cabin" or $_POST['command'] == "go around the cabin") {
		$_SESSION['location'] = "Side of South Cabin";
		$_POST['command'] = "";
		$no_command = false;
		$msg = "";
	}
	//go back
	if ($_POST['command'] == "go back" or $_POST['command'] == "go north") {
		$_SESSION['location'] = "Cabin Area";
		$desc = $desc_cabinarea;
		$msg = "";
	}
	//open door
	if ($_POST['command'] == "open door" or $_POST['command'] == "enter cabin" or $_POST['command'] == "use door") {
		if ($_SESSION['skill'] == "strength") {
			$_SESSION['location'] = "Inside the South Cabin";
			$msg = "";
		}
		else {
			$msg = $msg_doortight;
		}
	}
}

//side of south cabin
if ($_SESSION['location'] == "Side of South Cabin") {
	$desc = $desc_southcabinside;
	//go up/use ladder
	if ($_POST['command'] == "go up ladder" or $_POST['command'] == "climb ladder" or $_POST['command'] == "go up" or $_POST['command'] == "use ladder" or $_POST['command'] == "go to roof") {
		$_SESSION['location'] = "South Cabin Roof";
		$_POST['command'] = "";
		$no_command = false;
		$msg = "";
	}
	//go back
	if ($_POST['command'] == "go around cabin" or $_POST['command'] == "go back" or $_POST['command'] == "go around the cabin") {
		if ($_SESSION['skill'] == "perception") {
			$desc_cont = $descont_southcabinpercept;
		}
		elseif ($_SESSION['skill'] == "strength") {
			$desc_cont = $descont_southcabinstrength;
		}
		else {
			$desc_cont = "";
		}
		$_SESSION['location'] = "Front of South Cabin";
		$desc = $desc_southcabinfront . $desc_cont;
		$_POST['command'] = "";
		$no_command = false;
		$msg = "";
	}
}

//south cabin roof
if ($_SESSION['location'] == "South Cabin Roof") {
	$desc = $desc_southcabinroof;
	//enter cabin
	if ($_POST['command'] == "enter cabin" or $_POST['command'] == "go down" or $_POST['command'] == "drop down") {
		$_SESSION['location'] = "Inside the South Cabin";
		$msg = "";
	}
	//go back
	if ($_POST['command'] == "climb ladder" or $_POST['command'] == "go back" or $_POST['command'] == "use ladder") {
		$_SESSION['location'] = "Side of South Cabin";
		$desc = $desc_southcabinside;
		$msg = "";
	}
}

//inside south cabin
if ($_SESSION['location'] == "Inside the South Cabin") {
	$desc_cont = $descont_southcabinnote;
	$desc_cont2 = $descont_southcabinteddy;
	//check if note is in inventory
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "Note to my BooBoo") {
			$desc_cont = "";
		}
	}
	//take note
	if (($_POST['command'] == "take note" or $_POST['command'] == "take dirty note") and $desc_cont != "") {
		array_push($_SESSION['equipment'],"Note to my BooBoo");
		$desc_cont = "";
		$msg = $msg_takenote;
	}
	//check if they have teddy or if they've used the teddy
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "south cabin teddy bear") {
			$desc_cont2 = "";
		}
	}
	for ($v=0 ; $v < count($_SESSION['one_events']) ; $v++) {
		if ($_SESSION['one_events'][$v] == "used_southcabinteddy") {
			$desc_cont2 = "";
		}
	}
	//take teddy
	if ($_POST['command'] == "take teddy bear" and $desc_cont2 != "") {
		array_push($_SESSION['equipment'],"south cabin teddy bear");
		$desc_cont2 = "";
		$msg = $msg_taketeddy;
	}
	//screwdriver and bobbypin
	if ($_SESSION['skill'] == "collector") {
		$desc_cont3 = $descont_southcabinscrewbobby;
	}
	else {
		$desc_cont3 = "";
	}
	//check for screwdriver and bobby pin in inventory
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "screwdriver and bobby pin") {
			$desc_cont3 = "";
		}
	}
	if (($_POST['command'] == "take screwdriver" or $_POST['command'] == "take bobby pin" or $_POST['command'] == "take screwdriver and bobby pin" or $_POST['command'] == "take bobby pin and screwdriver") and $desc_cont3 != "") {
		array_push($_SESSION['equipment'],"screwdriver and bobby pin");
		$desc_cont3 = "";
		$msg = $msg_takescrewbob;
	}
	$desc = $desc_southcabininside . $desc_cont . $desc_cont2 . $desc_cont3;
	
	//leave
	if ($_POST['command'] == "go back" or $_POST['command'] == "climb bunkbeds" or $_POST['command'] == "leave cabin" or $_POST['command'] == "climb beds" or $_POST['command'] == "climb bed" or $_POST['command'] == "climb bunkbeds" or $_POST['command'] == "climb bunkbed" or $_POST['command'] == "climb bunk beds" or $_POST['command'] == "climb bunk bed") {
		$_SESSION['location'] = "South Cabin Roof";
		$desc = $desc_southcabinroof;
		$_POST['command'] = "";
		$no_command = false;
		$msg = "";
	}
	
	//open door
	if ($_POST['command'] == "open door" or $_POST['command'] == "use door") {
		if ($_SESSION['skill'] == "strength") {
			$msg = $msg_doortight . "<font color='blue'> Weird. It feels as if something is holding the door shut. You're going to have to use the ladder when you're on the roof.</font>";
		}
		else {
			$msg = $msg_doortight;
		}
	}
}

//east cabin front
if ($_SESSION['location'] == "Front of East Cabin") {
	$desc = $desc_eastcabinfront;
	//enter cabin
	if ($_POST['command'] == "open door" or $_POST['command'] == "use door" or $_POST['command'] == "enter cabin" or $_POST['command'] == "enter door") {
		$_SESSION['location'] = "Inside of East Cabin";
		$_POST['command'] = "";
		$no_command = false;
		$msg = "";
	}
	//go back
	if ($_POST['command'] == "go back" or $_POST['command'] == "go west") {
		$_SESSION['location'] = "Cabin Area";
		$desc_cont = $descont_cabinareacd;
		$msg = "";
		//check if cd is in inventory
		for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
			if ($_SESSION['equipment'][$v] == "recording-3") {
				$desc_cont = "";
			}
		}
		$desc = $desc_cabinarea . $desc_cont;
	}
}

//inside east cabin
if ($_SESSION['location'] == "Inside of East Cabin") {
	//lose 1 sanity
	$lose_sanity = true;
	for ($v=0 ; $v < count($_SESSION['one_events']) ; $v++) {
		if ($_SESSION['one_events'][$v] == "eastcabinsanityloss") {
			$lose_sanity = false;
		}
	}
	if ($lose_sanity == true) {
		array_push($_SESSION['one_events'],"eastcabinsanityloss");
		$_SESSION['sanity'] = $_SESSION['sanity'] - 1;
		$msg = $msg_losesanity;
	}
	$desc_cont = $descont_eastcabinnote;
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "Me and Mum") {
			$desc_cont = "";
		}
	}
	//take picture
	if ($_POST['command'] == "take picture" and $desc_cont != "") {
		array_push($_SESSION['equipment'],"Me and Mum");
		$msg = $msg_takepicture;
		$desc_cont = "";
	}
	//search body
	if ($_POST['command'] == "search body" or $_POST['command'] == "search pocket" or $_POST['command'] == "search pockets" or $_POST['command'] == "search person" or $_POST['command'] == "check pockets" or $_POST['command'] == "check pocket") {
		$has_key = false;
		for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
			if ($_SESSION['equipment'][$v] == "house key") {
				$has_key = true;
			}
		}
		if ($has_key == false) {
			array_push($_SESSION['equipment'],"house key");
			$msg = $msg_findkey;
		}
		else {
			$msg = $msg_empty;
		}
	}
	$desc = $desc_eastcabininside . $desc_cont;
	//go back
	if ($_POST['command'] == "open door" or $_POST['command'] == "use door" or $_POST['command'] == "exit cabin" or $_POST['command'] == "leave cabin" or $_POST['command'] == "go back") {
		$_SESSION['location'] = "Front of East Cabin";
		$desc = $desc_eastcabinfront;
		$msg = "";
	}
}



//porch of house
if ($_SESSION['location'] == "The Porch of the House") {
	echo '<embed src="whispers.mp3" autostart="true" loop="true" hidden="true">';
	//check for collector skill
	if ($_SESSION['skill'] == "collector") {
		$desc_cont = $descont_collopendoor;
	}
	else {
		$desc_cont = "";
	}
	$desc_cont2 = $descont_porchcd;
	$has_key = false;
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "house key") {
			$has_key = true;
		}
		if ($_SESSION['equipment'][$v] == "screwdriver and bobby pin") {
			$has_screwbob = true;
		}
		if ($_SESSION['equipment'][$v] == "recording-4") {
			$desc_cont2 = "";
		}
	}
	//take cd
	if ($_POST['command'] == "take cd" and $desc_cont2 != "") {
		array_push($_SESSION['equipment'],"recording-4");
		$desc_cont2 = "";
		$msg = $msg_takecd;
	}
	$desc = $desc_houseporch . $desc_cont . $desc_cont2;
	//enter house
	if ($_POST['command'] == "enter house" or $_POST['command'] == "use door" or $_POST['command'] == "enter door" or $_POST['command'] == "open door") {
		if ($has_key == true or ISSET($has_screwbob)) {
			$_SESSION['location'] = "Mudroom";
			$msg = $msg_opendoor;
		}
		else {
			$msg = $msg_nokey;
		}
	}
	//go back
	if ($_POST['command'] == "go south" or $_POST['command'] == "go back") {
		$_SESSION['location'] = "Frontside of the House";
		$desc = $desc_housefrontside;
		$msg = "";
	}
}



//mudroom
if ($_SESSION['location'] == "Mudroom") {
	//go to kitchen
	if ($_POST['command'] == "go to kitchen" or $_POST['command'] == "go kitchen" or $_POST['command'] == "go ahead" or $_POST['command'] == "go forward") {
		$_SESSION['location'] = "The Kitchen";
		$_POST['command'] = "";
		$no_command = "";
		$msg = "";
	}
	//do they have perception?
	if ($_SESSION['skill'] == "perception") {
		$desc_cont = $descont_mudroompercept;
	}
	else {
		$desc_cont = "";
	}
	$desc = $desc_mudroom . $desc_cont;
	//leave
	if ($_POST['command'] == "go back" or $_POST['command'] == "leave house" or $_POST['command'] == "exit house" or $_POST['command'] == "use door") {
		$_SESSION['location'] = "The Porch of the House";
		$msg = "";
		if ($_SESSION['skill'] == "collector") {
			$desc_cont = $descont_collopendoor;
		}
		else {
			$desc_cont = "";
		}
		$desc_cont2 = $descont_porchcd;
		for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
			if ($_SESSION['equipment'][$v] == "recording-4") {
				$desc_cont2 = "";
			}
		}
		$desc = $desc_houseporch . $desc_cont . $desc_cont2;
	}
}

//kitchen
if ($_SESSION['location'] == "The Kitchen") {
	$desc = $desc_kitchen;
	//lose 1 sanity
	$lose_sanity = true;
	for ($v=0 ; $v < count($_SESSION['one_events']) ; $v++) {
		if ($_SESSION['one_events'][$v] == "kitchensanityloss") {
			$lose_sanity = false;
		}
	}
	if ($lose_sanity == true) {
		array_push($_SESSION['one_events'],"kitchensanityloss");
		$_SESSION['sanity'] = $_SESSION['sanity'] - 1;
		$msg = $msg_losesanity;
	}
	//go to living room
	if ($_POST['command'] == "go to living room" or $_POST['command'] == "go left") {
		$_SESSION['location'] = "The Living Room";
		$msg = "";
	}
	//go to staircase
	if ($_POST['command'] == "go to staircase" or $_POST['command'] == "go ahead" or $_POST['command'] == "go forward") {
		$_SESSION['location'] = "Bottom of Staircase";
		$msg = "";
		$_POST['command'] = "";
		$no_command = false;
	}
	//go back
	if ($_POST['command'] == "go back" or $_POST['command'] == "go to mudroom") {
		$_SESSION['location'] = "Mudroom";
		$msg = "";
		if ($_SESSION['skill'] == "perception") {
			$desc_cont = $descont_mudroompercept;
		}
		else {
			$desc_cont = "";
		}
		$desc = $desc_mudroom . $desc_cont;
	}
}

//living room
if ($_SESSION['location'] == "The Living Room") {
	$desc = $desc_livingroom;
	//go to door
	if ($_POST['command'] == "go forward" or $_POST['command'] == "go ahead" or $_POST['command'] == "go to door") {
		$_SESSION['location'] = "Basement Door";
		$_POST['command'] = "";
		$no_command = false;
		$msg = "";
	}
	//go back
	if ($_POST['command'] == "go back" or $_POST['command'] == "go to kitchen") {
		$_SESSION['location'] = "The Kitchen";
		$desc = $desc_kitchen;
		$msg = "";
	}
}

//basement door
if ($_SESSION['location'] == "Basement Door") {
	//check for collector skill
	if ($_SESSION['skill'] == "collector") {
		$desc_cont = $descont_collopendoor;
	}
	else {
		$desc_cont = "";
	}
	$has_key = false;
	$has_screwbob = false;
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "basement key") {
			$has_key = true;
		}
		if ($_SESSION['equipment'][$v] == "screwdriver and bobby pin") {
			$has_screwbob = true;
		}
	}
	//open door
	if ($_POST['command'] == "open door" or $_POST['command'] == "unlock door" or $_POST['command'] == "enter door" or $_POST['command'] == "go ahead" or $_POST['command'] == "go forward" or $_POST['command'] == "go to basement" or $_POST['command'] == "use door") {
		if ($has_key == true or $has_screwbob == true) {
			$_SESSION['location'] = "The Basement";
			$msg = $msg_opendoor;
			$_POST['command'] = "";
			$no_command = false;
		}
		else {
			$msg = $msg_nokey;
		}
	}
	$desc = $desc_basementdoor . $desc_cont;
	//go back
	if ($_POST['command'] == "go back"or $_POST['command'] == "go to living room" or $_POST['command'] == "go to the living room") {
		$_SESSION['location'] = "The Living Room";
		$desc = $desc_livingroom;
		$msg = "";
	}
}

//bottom of staircase
if ($_SESSION['location'] == "Bottom of Staircase") {
	$desc_cont = $descont_staircd;
	//check if cd is in inventory
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-5") {
			$desc_cont = "";
		}
	}
	//take cd
	if ($_POST['command'] == "take cd" and $desc_cont != "") {
		array_push($_SESSION['equipment'],"recording-5");
		$desc_cont = "";
		$msg = $msg_takecd;
	}
	$desc = $desc_staircase . $desc_cont;
	if ($_POST['command'] == "go up the stairs" or $_POST['command'] == "use stairs" or $_POST['command'] == "use staircase" or $_POST['command'] == "go up stairs" or $_POST['command'] == "go up staircase" or $_POST['command'] == "go ahead" or $_POST['command'] == "go forward" or $_POST['command'] == "go up") {
		$_SESSION['location'] = "Bedroom";
		$msg = "";
	}
	if ($_POST['command'] == "go back" or $_POST['command'] == "go to kitchen") {
		$_SESSION['location'] = "The Kitchen";
		$desc = $desc_kitchen;
		$msg = "";
	}
}

//bedroom
if ($_SESSION['location'] == "Bedroom") {
	$desc_cont = $descont_bedroomkey;
	//check if key is in inventory
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "basement key") {
			$desc_cont = "";
		}
	}
	//take key
	if ($_POST['command'] == "take key" and $desc_cont != "") {
		array_push($_SESSION['equipment'],"basement key");
		$desc_cont = "";
		$msg = $msg_takekey;
	}
	$desc = $desc_bedroom . $desc_cont;
	//go back
	if ($_POST['command'] == "go back" or $_POST['command'] == "go downstairs" or $_POST['command'] == "go down" or $_POST['command'] == "use stairs" or $_POST['command'] == "use staircase" or $_POST['command'] == "go to bottom of staircase") {
		$_SESSION['location'] = "Bottom of Staircase";
		$desc_cont = $descont_staircd;
		//check if cd is in inventory
		for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
			if ($_SESSION['equipment'][$v] == "recording-5") {
				$desc_cont = "";
			}
		}
		//take key
		if ($_POST['command'] == "take cd" and $desc_cont != "") {
			array_push($_SESSION['equipment'],"recording-5");
			$desc_cont = "";
			$msg = $msg_takecd;
		}
		$desc = $desc_staircase . $desc_cont;
		$msg = "";
	}
}

//basement
if ($_SESSION['location'] == "The Basement") {
	$desc_cont = $descont_basementcd;
	//check if cd is in inventory
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-6") {
			$desc_cont = "";
		}
	}
	//take cd
	if ($_POST['command'] == "take cd" and $desc_cont != "") {
		array_push($_SESSION['equipment'],"recording-6");
		$desc_cont = "";
		$msg = $msg_takecd;
	}
	//take flashlight
	if ($_POST['command'] == "take flashlight" or $_POST['command'] == "take burnt out flashlight") {
		$msg = $msg_canttake;
	}
	$go = true;
	for ($v=0 ; $v < count($_SESSION['one_events']) ; $v++) {
		if ($_SESSION['one_events'][$v] == "seen_andy") {
			$go = false;
		}
	}
	//go ahead
	if ($_POST['command'] == "go ahead" or $_POST['command'] == "go forward" or $_POST['command'] == "go to figure") {
		if ($go == true) {
			$_SESSION['location'] = "The Basement - Andy";
			$msg = "";
		}
		else {
			$msg = "You can't go back there; you already found Andy.";
		}
	}
	$desc = $desc_basement . $desc_cont;
	//go back
	if ($_POST['command'] == "go back" or $_POST['command'] == "go to door" or $_POST['command'] == "leave basement") {
		$_SESSION['location'] = "Basement Door";
		$msg = "";
		//check for collector skill
		if ($_SESSION['skill'] == "collector") {
			$desc_cont = $descont_collopendoor;
		}
		else {
			$desc_cont = "";
		}
		$desc = $desc_basementdoor . $desc_cont;
	}
}

//andy
if ($_SESSION['location'] == "The Basement - Andy") {
	$desc = $desc_andy;
	//break trance
	if ($_POST['command'] == "shake andy" or $_POST['command'] == "hit andy" or $_POST['command'] == "slap andy" or $_POST['command'] == "punch andy" or $_POST['command'] == "kick andy" or $_POST['command'] == "shake Andy" or $_POST['command'] == "hit Andy" or $_POST['command'] == "slap Andy" or $_POST['command'] == "punch Andy" or $_POST['command'] == "kick Andy") {
		$_SESSION['convo'] = true;
		$_SESSION['convo_type'] = "andy";
	}
	//"wake andy"
	if ($_POST['command'] == "wake andy" or $_POST['command'] == "wake Andy") {
		$msg = $msg_howtowake;
	}
	//try to go back
	if ($_POST['command'] == "go back" or $_POST['command'] == "leave basement" or $_POST['command'] == "go to basement" or $_POST['command'] == "go to basement door") {
		$msg = $msg_baseneedandy;
	}
}

//win
if ($_SESSION['location'] == "Win") {
	$finish = true;
}



//conversation
if ($_SESSION['convo'] == true) {
	$end_convo = false;

	//convo1
	if ($_SESSION['convo_type'] == "andy") {
		$char_name = "Andy";

		//first part
		if ($_SESSION['convo_part'] == 1) {
			$_SESSION['other_speech'] = "Is... is that you?";
			
			$sub = "one";
			
			$option1 = "Andy! Oh, am I ever so happy to see you.";
			$option2 = "What happened to you?";
			$option3 = "Why the hell would you come here like this? Look what happened to you.";
			
			if (ISSET($_POST['one'])) {
				$_SESSION['convo_part'] = 2;
				if ($_POST['command'] == $option1) {
					$_SESSION['other_speech'] = "I'm even more happy to see you. I wish I never came here... I was just exploring to find Steven.";
				}
				elseif ($_POST['command'] == $option2) {
					$_SESSION['other_speech'] = "I came here to explore. Explore the legend, and explore this place. Somebody named Steven went missing here apparently.";
				}
				elseif ($_POST['command'] == $option3) {
					$_SESSION['other_speech'] = "The hypocrisy is that you came here alone, too; I was doing the same thing you just did, which was looking for Steven.";
				}
			}
		}

		//second part
		if ($_SESSION['convo_part'] == 2) {

			$sub = "two";
			
			$option1 = "Who's Steven?";
			$option2 = "What did you see?";
			$option3 = "We should probably get going. We can chat more once we're out of this place.";
			
			if (ISSET($_POST['two'])) {
				$_SESSION['convo_part'] = 3;
				if ($_POST['command'] == $option1) {
					$_SESSION['other_speech'] = "Steven was a guy I knew from computer science class. I'm not sure why he came here. Maybe it was for the same reason <em>we</em> came here: to find somebody, or something. To find out the legend. But wherever he went, he's gone. I couldn't find him.";
				}
				elseif ($_POST['command'] == $option2) {
					$_SESSION['other_speech'] = "Weird things. Weird things. I saw people with bags on their heads, and I found bodies. I found suicide notes. This place is haunted for sure. The one thing that didn't happen, though, is I couldn't find Steven. I guess the legend is true.";
				}
				elseif ($_POST['command'] == $option3) {
					$end_convo = true;
				}
			}
		}
		
		//third part
		if ($_SESSION['convo_part'] == 3) {
			
			$sub = "three";
			
			$option1 = "Did you discover what the legend is, or if it's true?";
			$option2 = "";
			for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
				if ($_SESSION['equipment'][$v] == "cedar tree note") {
					$option2 = "I have this note from a bag at the north entrance under a cedar tree. It might be his. Also, did you find what the legend was all about?";
				}
			}
			$option3 = "We should probably get going. We can chat more once we're out of this place.";
			
			if (ISSET($_POST['three'])) {
				$_SESSION['convo_part'] = 4;
				if ($_POST['command'] == $option1) {
					$_SESSION['other_speech'] = "The story of this place, Merch Hill, is that it's sort of like a state of limbo for souls, and it's apparently also a purgatory or something. I'm pretty sure that's true; there are a lot of creepy, haunting things going on here. The other part is people who come here slowly lose neuroreceptors in their brains, causing loss of function and eventually blackouts. Blackouts lead to dying, and dying here would mean the unthinkable. That's definitely true. I think that if you didn't wake me just now, I would have died.";
				}
				elseif ($_POST['command'] == $option2) {
					$_SESSION['other_speech'] = "This looks like his writing. Thank you for showing me this. Although it upsets me, it provides closure. What happened to him most likely was he slowly lost the neuroreceptors in his brain due to being here for so long, and that lead to insanity and eventually death. Dying here would mean becoming a part of this limbo-state of souls which is hosted here, at Merch Hill. I hope Steven isn't suffering.";
				}
				elseif ($_POST['command'] == $option3) {
					$end_convo = true;
				}
			}
		}
		
		//part four
		if ($_SESSION['convo_part'] == 4) {
			
			$sub = "four";
			
			$option1 = "I've also felt the sanity effects. Hopefully there's no permanent damage.";
			$option2 = "How did you get stuck in here anyway? I had to unlock the doors to get in.";
			$option3 = "We should probably get going. We can chat more once we're out of this place.";
			
			if (ISSET($_POST['four'])) {
				$_SESSION['convo_part'] = 5;
				if ($_POST['command'] == $option1) {
					$_SESSION['other_speech'] = "They aren't permanent. Only when we're in the prescence of the spirits do we get the effects.";
				}
				elseif ($_POST['command'] == $option2) {
					$_SESSION['other_speech'] = "When I shut the doors, they locked on me. I should have learned the first time the house door locked that closing the door behind me is a bad idea. When I came in the house, everything started crumbling, and I thought I should hide in the basement. Bad idea, right? My flashlight died, and I recorded one last message before waiting it out. Seriously, if it weren't for you, I would be dead.";
				}
				elseif ($_POST['command'] == $option3) {
					$end_convo = true;
				}
			}
		}
		
		//part five
		if ($_SESSION['convo_part'] == 5) {
			
			$sub = "five";
			
			$option1 = "I see. Anyway, we should probably get out of here now.";
			$option2 = "";
			$option3 = "We should probably get going. We can chat more once we're out of this place.";
			
			if (ISSET($_POST['five'])) {
				$end_convo = true;
			}
		}

		
		if ($end_convo == true) {
			$_SESSION['location'] = "The Basement";
			$desc_cont = $descont_basementcd;
			//check if cd is in inventory
			for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
				if ($_SESSION['equipment'][$v] == "recording-6") {
					$desc_cont = "";
				}
			}
			$desc = $desc_basement . $desc_cont;
			$_SESSION['convo'] = false;
			array_push($_SESSION['one_events'],"seen_andy");
			$_SESSION['cur_obj'] = "Take Andy to the south end of the forest and escape";
			$_SESSION['companion'] = "andy";
			$msg = "Andy is now your companion.";
			$_SESSION['convo_part'] = 1;
		}
	}
	
	//jacob
	if ($_SESSION['convo_type'] == "jacob") {
		$char_name = "Jacob";
		//part 1
		if ($_SESSION['convo_part'] == 1) {
		
			$sub = "newone";
			
			$_SESSION['other_speech'] = "Hey, don't leave yet! It's me, Jacob! Check out my slick new getup!";
			
			if ($_SESSION['skill'] == "perception") {
				$option1 = "(PERCEPTION) That's a sweet getup. I can clearly tell you put some quality time into it.";
				$option2 = "(PERCEPTION) Your hair is looking like it's on fire.";
				$option3 = "(PERCEPTION) Yo, I also see you got some slick new kicks.";
			}
			if ($_SESSION['skill'] == "strength") {
				$option1 = "(STRENGTH) Sweet, man. If I must say, that's quite the BOLD outfit.";
				$option2 = "(STRENGTH) That's slick. By the way, I can tell you've been stackin' racks at the gym.";
				$option3 = "(STRENGTH) John Cena approves.";
			}
			if ($_SESSION['skill'] == "collector") {
				$option1 = "(COLLECTOR) Holy jeez, are those vintage Jordan Airs? That's supreme, brah.";
				$option2 = "(COLLECTOR) I don't think I've been able to get myself such a nice outfit. By the way, look at Andy's threads; they're so lame.";
				$option3 = "(COLLECTOR) Nice. I've collected lots of nice threads in my time. You look pretty slick.";
			}
			
			if (ISSET($_POST['newone'])) {
				$_SESSION['convo_part'] = 2;
			}
		}
		
		if ($_SESSION['convo_part'] == 2) {
			$_SESSION['other_speech'] = "Thanks, man! You're totally right!";
			
			$sub = "newtwo";
			
			$option1 = "Later! >:E";
			$option2 = "It was nice seeing you, brah. I gotta roll now.";
			$option3 = "Pizza! Huh?";
			
			if (ISSET($_POST['newtwo'])) {
				$_SESSION['convo'] = false;
				$_SESSION['location'] = "Win";
				$finish = true;
			}
		}
	}
}







//no command error
if ($_POST['command'] == "" and ISSET($_POST['submit']) and $no_command == true) {
	$msg = $msg_nocommand;
}

//msg is nothing when in convo
if ($_SESSION['convo'] == true) {
	$msg = "";
}

//read cedar tree note
if ($_POST['command'] == "read cedar tree note") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "cedar tree note") {
			$msg = $msg_cedarnote;
		}
	}
}

//read note to my booboo
if ($_POST['command'] == "read Note to my BooBoo") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "Note to my BooBoo") {
			$msg = $msg_boonote;
		}
	}
}

//read note to society
if ($_POST['command'] == "read My note to society") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "My note to society") {
			$msg = $msg_societynote;
		}
	}
}

//look at forest map
if ($_POST['command'] == "check forest map" or $_POST['command'] == "use forest map" or $_POST['command'] == "look at forest map") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "forest map") {
			$img = "http://i.imgur.com/ObprJ0U.png";
		}
	}
	$msg = "";
}

//look at me and mum pic
if ($_POST['command'] == "check Me and Mum" or $_POST['command'] == "use Me and Mum" or $_POST['command'] == "look at Me and Mum") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "Me and Mum") {
			$img = "http://i.imgur.com/sjdITrh.jpg";
		}
	}
	$msg = "";
}
	
//play first recording
if ($_POST['command'] == "listen to recording-1" or $_POST['command'] == "use recording-1" or $_POST['command'] == "play recording-1") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-1") {
			$msg = "Playing recording-1";
			echo '<embed src="voice1.mp3" autostart="true" loop="true" hidden="true">';
		}
	}
}

//play second recording
if ($_POST['command'] == "listen to recording-2" or $_POST['command'] == "use recording-2" or $_POST['command'] == "play recording-2") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-2") {
			$msg = "Playing recording-2";
			echo '<embed src="voice2.mp3" autostart="true" loop="true" hidden="true">';
		}
	}
}

//play third recording
if ($_POST['command'] == "listen to recording-3" or $_POST['command'] == "use recording-3" or $_POST['command'] == "play recording-3") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-3") {
			$msg = "Playing recording-3";
			echo '<embed src="voice3.mp3" autostart="true" loop="true" hidden="true">';
		}
	}
}

//play fourth recording
if ($_POST['command'] == "listen to recording-4" or $_POST['command'] == "use recording-4" or $_POST['command'] == "play recording-4") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-4") {
			$msg = "Playing recording-4";
			echo '<embed src="voice4.mp3" autostart="true" loop="true" hidden="true">';
		}
	}
}

//play fifth recording
if ($_POST['command'] == "listen to recording-5" or $_POST['command'] == "use recording-5" or $_POST['command'] == "play recording-5") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-5") {
			$msg = "Playing recording-5";
			echo '<embed src="voice5.mp3" autostart="true" loop="true" hidden="true">';
		}
	}
}

//play sixth recording
if ($_POST['command'] == "listen to recording-6" or $_POST['command'] == "use recording-6" or $_POST['command'] == "play recording-6") {
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "recording-6") {
			$msg = "Playing recording-6";
			echo '<embed src="voice6.mp3" autostart="true" loop="true" hidden="true">';
		}
	}
}

//use south cabin teddy
if ($_POST['command'] == "use south cabin teddy bear") {
	$used_teddy = true;
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "south cabin teddy bear") {
			// for ($x=0 ; $x < count($_SESSION['one_events']) ; $x++) {
				// if ($_SESSION['one_events'][$x] == "used_southcabinteddy") {
					// $used_teddy = true;
				// }
			// }
			$x = $v;
			$used_teddy = false;
		}
	}
	if ($used_teddy == false) {
		if ($_SESSION['sanity'] != 5) {
			$_SESSION['sanity'] = $_SESSION['sanity'] + 1;
			$msg = $msg_gainsanityteddy;
			array_push($_SESSION['one_events'],"used_southcabinteddy");
			unset($_SESSION['equipment'][$x]);
			$_SESSION['equipment'] = array_values($_SESSION['equipment']);
			$_SESSION['refresh'] = 0;
		}
		else {
			$msg = $msg_fullsanity;
		}
	}
}

//use shed teddy
if ($_POST['command'] == "use shed teddy bear") {
	$used_teddy = true;
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "shed teddy bear") {
			$x = $v;
			$used_teddy = false;
		}
	}
	if ($used_teddy == false) {
		if ($_SESSION['sanity'] != 5) {
			$_SESSION['sanity'] = $_SESSION['sanity'] + 1;
			$msg = $msg_gainsanityteddy;
			array_push($_SESSION['one_events'],"used_shedteddy");
			unset($_SESSION['equipment'][$x]);
			$_SESSION['equipment'] = array_values($_SESSION['equipment']);
			$_SESSION['refresh'] = 0;
		}
		else {
			$msg = $msg_fullsanity;
		}
	}
}

//use dark teddy
if ($_POST['command'] == "use dark teddy bear") {
	$used_teddy = true;
	for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
		if ($_SESSION['equipment'][$v] == "dark teddy bear") {
			$x = $v;
			$used_teddy = false;
		}
	}
	if ($used_teddy == false) {
		if ($_SESSION['sanity'] != 5) {
			$_SESSION['sanity'] = $_SESSION['sanity'] + 1;
			$msg = $msg_gainsanityteddy;
			array_push($_SESSION['one_events'],"used_darkteddy");
			unset($_SESSION['equipment'][$x]);
			$_SESSION['equipment'] = array_values($_SESSION['equipment']);
			$_SESSION['refresh'] = 0;
		}
		else {
			$msg = $msg_fullsanity;
		}
	}
}

//HELP message
if ($_POST['command'] == "help" or $_POST['command'] == "bubba" or $_POST['command'] == "?") {
	$msg = $msg_help;
}

//"read note" and stuff
if ($_POST['command'] == "read note" or $_POST['command'] == "use cd" or $_POST['command'] == "listen to cd" or $_POST['command'] == "play cd" or $_POST['command'] == "use teddy bear" or $_POST['command'] == "use map" or $_POST['command'] == "look at map" or $_POST['command'] == "use picture" or $_POST['command'] == "look at picture") {
	$msg = "Remember to reference the EXACT NAME OF THE ITEM, including CAPITALS. Example: read Note Name. The type of item you referenced must be taken before you can use them.";
}

//save game
if (ISSET($_POST['save'])) {
	require("dbConnect.php");
	$id = $_SESSION['id'];
	$put_location = $_SESSION['location'];
	$put_sanity = $_SESSION['sanity'];
	$put_skill = $_SESSION['skill'];
	$put_equipment = implode(",",$_SESSION['equipment']);
	$put_curobj = $_SESSION['cur_obj'];
	$put_compobj = implode(",",$_SESSION['comp_obj']);
	$put_oneevents = implode(",",$_SESSION['one_events']);
	$put_companion = $_SESSION['companion'];

	$query="UPDATE `game`.`game_data` SET `location` = '$put_location', `equipment` = '$put_equipment', `sanity` = '$put_sanity', `skill` = '$put_skill', `current_objective` = '$put_curobj', `completed_objectives` = '$put_compobj', `one_time_events` = '$put_oneevents', `companion` = '$put_companion' WHERE `game_data`.`user_id` = '$id'";
	mysqli_query($dbc,$query) or DIE("I died doing the query, bruh");
	$msg = "Game saved successfully!";
	
}

//activate radio
if ($_POST['command'] == "activate radio" or $_POST['command'] == "use radio" or $_POST['command'] == "turn on radio") {
	if ($_SESSION['location'] == "The Porch of the House") {
		$_SESSION['station'] = "gong_station.mp3";
	}
	elseif ($_SESSION['location'] == "Mudroom") {
		$_SESSION['station'] = "static.mp3";
	}
	?>
	<embed src="<?php echo $_SESSION['station']; ?>" autostart="true" loop="" hidden="true">
	<?php
	if ($_SESSION['station'] == "gong_station.mp3") {
		$msg = "Picked up signal IS8-91";
	}
	elseif ($_SESSION['station'] == "static.mp3") {
		$msg = "Picked up signal 666";
	}
}
else {
	$_SESSION['radio'] = false;
}

//radio sounds
if ($_SESSION['radio'] == true) {
	//echo '<embed src="static.mp3" autostart="true" loop="" hidden="true">';
	echo '<embed src="gong_station.mp3" autostart="true" loop="" hidden="true">';
	$msg = "Picked up signal IS8-91";
}

//sanity screams
if ($_SESSION['sanity'] < 3) {
	$array = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);
	shuffle($array);
	$rand = $array[0];
	if ($array[0] == 10) {
		echo '<embed src="scream.mp3" autostart="true" loop="" hidden="true">';
	}
}

//sanity names
if ($_SESSION['sanity'] == 5) {
	$sanity = "Mentally stable";
}
elseif ($_SESSION['sanity'] == 4) {
	$sanity = "Nervous";
}
elseif ($_SESSION['sanity'] == 3) {
	$sanity = "Afraid";
}
elseif ($_SESSION['sanity'] == 2) {
	$sanity = "Paranoid";
}
elseif ($_SESSION['sanity'] == 1) {
	$sanity = "Insane";
}
elseif ($_SESSION['sanity'] < 1) {
	$_SESSION['gameover'] = true;
}

//less than 3 sanity popup
if ($_SESSION['sanity'] < 3) {
	$go = true;
	for ($v=0 ; $v < count($_SESSION['one_events']) ; $v++ ) {
		if ($_SESSION['one_events'][$v] == "popup") {
			$go = false;
		}
	}
	if ($go == true) {
		array_push($_SESSION['one_events'],"popup");
		$_SESSION['popup'] = true;
	}
}

//show giu if game hasn't ended
if (!ISSET($_SESSION['gameover']) and $finish == false and $_SESSION['popup'] == false) {

?>

<!--GUI-->
<h1 align="left"><font color="orange">The Haunting of Merch Hill</font></h1><br />
<br><br>


<p align="center"><font size="3">Current location:</font><br />
<font size="6"><?php echo $_SESSION['location']; ?></font></p>

<fieldset>
<p align="left" style="float:left"><font color="red" size="5"><strong>Sanity: </strong></font></p><br />
<?php
for ($c=0 ; $c < $_SESSION['sanity'] ; $c++) {
	echo "<img src='http://i.imgur.com/tLG5f5L.png' style='float:left'>";
}
?>
<div style="float:left"><font color="red" size="5"><?php echo "(" . $_SESSION['sanity'] . "; " . $sanity . ")"; ?></font></div>
<br><br>
<p align="left"><font color="red" size="5"><strong>Inventory: </strong></font>
<?php
for ($v=0 ; $v < count($_SESSION['equipment']) ; $v++) {
	if (count($_SESSION['equipment']) - $v != 1) {
		echo $_SESSION['equipment'][$v] . " | ";
	}
	else {
		echo $_SESSION['equipment'][$v];
	}
}
?>
</p>

<p align="left"><font color="red" size="5"><strong>Current objective: </strong></font>
<?php echo $_SESSION['cur_obj']; ?>
<br>

<?php
if ($_SESSION['convo'] == false) {
?>

	<p><font size="4"><?php echo $desc; ?></font></p>

	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="text" name="command" placeholder="Command" autofocus>
		<input type="submit" name="submit" value="Go">
		<input type="submit" name="save" value="Save game"><br />
		<?php
		//show image
		if ($img != "") {
		?>
			<img src="<?php echo $img; ?>" align="right" height="200" width="200">
		<?php
		}
		?>
		<p>Type "?" or "help" for help message. If you don't know what to do or where to go, trying typing <em>go back</em></p>
	</form>
	</fieldset>
	<?php
	echo $msg;

}
//CONVERSATION
else {

echo "<br /> <br />";

echo "<font color='green'><strong>" . $char_name . " says: " . $_SESSION['other_speech'] . "</strong></font><br /><br />";
	?>
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<p><em>Choose your reply from this box...</em></p>
		<select name="command">
			<?php if ($option1 != "") { ?>
				<option value="<?php echo $option1; ?>"><?php echo $option1; ?></option>
			<?php } if ($option2 != "") { ?>
				<option value="<?php echo $option2; ?>"><?php echo $option2; ?></option>
			<?php } if ($option3 != "") { ?>
				<option value="<?php echo $option3; ?>"><?php echo $option3; ?></option>
			<?php } ?>
		</select>
		<input type="submit" name="<?php echo $sub; ?>" value="Go">
	</form>
	</fieldset>
	<?php		
}
?>

<img src="http://i45.tinypic.com/33njl0j.gif" style="float:right">
<?php
//extra eye with less than full sanity
if ($_SESSION['sanity'] <= 4) {
?>
	<img src="http://i45.tinypic.com/33njl0j.gif" style="float:left">
<?php
}
?>
<?php
}
elseif ($_SESSION['popup'] == true) {
	?>
	<embed src="popup.mp3" autostart="true" loop="true" hidden="true">
	<img src="https://d1ij7zv8zivhs3.cloudfront.net/assets/1216688/lightbox/94236d4772abbaeff8e754b7fe28f0ba.jpg?1277206322">
	<img src="http://i.imgur.com/GJ85Xqi.png">
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="submit" name="back" value="Back to game"><p>(press this button twice to return)</p>
	</form>
	<?php
	if (ISSET($_POST['back'])) {
		$_SESSION['popup'] = false;
	}
}
elseif (ISSET($_SESSION['gameover'])) {
	echo "<font color='red'><strong>You have gone completely insane. Game over.</strong></font>";
	echo "<img src='https://d1ij7zv8zivhs3.cloudfront.net/assets/1216688/lightbox/94236d4772abbaeff8e754b7fe28f0ba.jpg?1277206322'>";
	echo '<embed src="scream.mp3" autostart="true" loop="true" hidden="true">';
}
elseif ($finish == true) {
	echo "You win. Thanks for playing. In memory of my cat, Max, who died on January 9th, 2015. I will never forget you. <br />
		Created by: David Negrazis";
	?>
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="submit" name="save" value="Save game"><br />
	</form>
	<?php
}
mysqli_close($dbc);
?>
</font>
</body>
</html>
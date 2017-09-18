<?php
//errors
$msg_notapp = "That command is not applicable here.";
$msg_notnav = "It is too difficult to navigate in that direction.";
$msg_canttake = "You can't take that.";
$msg_nocommand = "No command given.";
$msg_checkingbag = "You are already checking the bag.";
$msg_doortight = "The door is tightly shut. You cannot open it.";
$msg_fullsanity = "You already have full sanity.";
if ($_SESSION['skill'] == "collector") {
	$msg_nokey = "You can't open the door. A key or <font color='orange'>screwdriver and bobbypin</font> is needed to open it.";
}
else {
	$msg_nokey = "You can't open the door. A key is required to open it.";
}
$msg_empty = "It is empty.";
$msg_needandy = "You can't leave yet; Andy hasn't been found!";
$msg = $msg_unknown = "That command is not valid. It may be an unknown command, or the command is not applicable in this area.";
$msg_howtowake = "Think of an action you can do to wake Andy.";
$msg_baseneedandy = "You need to wake Andy first.";

//skillmsg
$msg_shedstrength = "<font color='blue'><strong>You use your strength to enter the shed.</strong></font>";
$msg_shednostrength = "You are too weak to open the door with your bare hands.";
$msg_usescrewbob = "<br /><font color='orange'>You picked the lock.</font>";
$msg_takescrewbob = "<font color='orange'>Screwdriver and bobby pin taken.</font>";
//using items
$msg_cedarnote = "The note says: I'm lost. I'm dead. Please... learn from my mistakes.";
$msg_boonote = "The note says: Hi, Cindy. I hope you're having fun at camp. Daddy is feeling a little better, but he is still in the hospital. The doctors say he will probably be okay, so you shouldn't be worried. How has camp been so far? <br />Love Mom XOXO";
$msg_societynote = "<font size='5'><strong>My note to society</strong></font><br>My name is Cindy. I am eighteen. I came here to this abandoned camp because of all of you.<br />
You all ruined me. Everybody judged me. I hate living now. I hate it. Nothing is good about living for me. All I have are problems, death to deal with and more crap. Nothing is worth living for anymore. Nothing is worth suffering through another day. I came here to free myself. Free myself from all of you.<br />
I miss you so much, dad. I hope you are sleeping easy. Mom, the same to you. You were the only ones that actually loved me.<br />
Goodbye.";

//notices
$msg_takecd = "CD taken.";
$msg_takenote = "Note taken.";
$msg_takemap = "Map taken.";
$msg_taketeddy = "Teddy bear taken.";
$msg_takepicture = "Picture taken.";
$msg_takekey = "You took the key.";
$msg_findkey = "You found a key in the pockets.";
$msg_losesanity = "You have lost sanity.";
$msg_gainsanity = "You have gained sanity.";
$msg_gainsanityteddy = "You have gained sanity by cuddling with the teddy bear. The teddy bear has been removed from your inventory.";
$msg_opendoor = "You unlocked the door.";
$msg_losesanitytime = "You have lost sanity due to being here for so long.";


//help
$msg_help = '<font color="red" size="4">READ THIS ENTIRE HELP MESSAGE</font><br />
	First of all, you type in commands to control your character. Commands are very straight forward, often including two words: a verb and the target of the verb. Commands are also only lowercase unless you are referencing something which includes capital letters. Example commands: read Note Name, go southwest, go to kitchen, use radio.<br />
	To move, you first type "go" and then indicate the target location (i.e. north, southwest, etc.). Some areas allow typing "go back" to take you to the area before that location. In some cases, the target location may have to be "up", "down", or other similar targets. <br />
	Objects can be interacted with. Common verbs for this type of command include: "take, open, activate, listen to, use, read, leave, unlock". <br />
	Doors are opened by typing "open door".<br />
	You have a radio which can be used to pick up signals or play audio items, like a CD. Typing "activate radio" will play the strongest radio signal for your location. When you have an audio item in your inventory, typing something like "listen to" and then the name of the audio item will play the audio item. <br />
	Sanity slowly decreases as you play the game. There are teddy bears which you can find and use to regenerate some of your sanity.
	';
?>
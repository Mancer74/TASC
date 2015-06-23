<html>
<head>
<link rel="stylesheet" type="text/css" href="tascStyle.css">
</head>
<body>

<?php
include 'dbconnect.php';
mysql_connect($host, $username, $password);
@mysql_select_db($database) or die("Unable to select database");

//get the user ID from the URL and find the correct poll
$userid = $_GET["id"];
$query = "SELECT `PollID`, `Title`, `Description` FROM `polls` WHERE `UserID` = '". $userid ."'";
	$result = mysql_query($query);
	$thisPoll = mysql_result($result,0,"PollID");
	$title = mysql_result($result,0,"Title");
	$description = mysql_result($result,0,"Description");
	
echo '<h1 id="smallerMargin">The Title of this poll is: </h1><h4>'. $title .'</h4><br>';
echo '<label id="smallLabel">Description: </label><br><label id="smallDescription">'. $description .'</label><br><br><br>';

//figure out how many designs there are for this particular poll
$query = "SELECT `DesignID` FROM `designs` WHERE `PollID` = ". $thisPoll;
	$result = mysql_query($query);
	$numDesigns = mysql_numrows($result);
?>

This is a place holder for a how to <br><br>

<form action="designform.php" method="post">

<input type="hidden" name="thisPoll" value="<?php echo $thisPoll; ?>">
<input type="hidden" name="numDesigns" value="<?php echo $numDesigns; ?>">
<input type="submit" id="doneButton" value="Next">

</form>
<img id="psulogo" src="pictures/PennState001.gif">
<img id="britelablogo" src="pictures/britelablogo.gif">

</body>
</html>
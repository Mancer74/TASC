<html>
<head>
<link rel="stylesheet" type="text/css" href="tascStyle.css">
</head>
<body>

<?php
include 'dbconnect.php';
mysql_connect($host, $username, $password);
@mysql_select_db($database) or die("Unable to select database");

$numDesigns = $_POST['numDesigns'];
$thisPoll = $_POST['thisPoll'];
$user = $_POST['user'];

//get the current score for each category for this poll from the database
$query = "SELECT `InnovationTOT`, `FeasibilityTOT` FROM `designs` WHERE `PollID` = ". $thisPoll;
	$AVGresult = mysql_query($query);

//get the scores for each word from the database
$query = "SELECT `InnovationS`, `FeasabilityS` FROM `words`";
	$scoresResult = mysql_query($query);

//get the number of users already participating in this poll
$query = "SELECT `NumPart` FROM `polls` WHERE `PollID` = ". $thisPoll;
	$participantsResult = mysql_query($query);
	$NumPart = mysql_result($participantsResult,0,"NumPart");

//loop through designs
for ($i = 0; $i < $numDesigns; $i++)
{
	//create a new user
	$query = "INSERT INTO `users` (`PollID`, `DesignID`, `UserName`) VALUES (". $thisPoll .",". $i .",'". $user ."')";
		mysql_query($query);
	$InnovToAdd = 0;
	$FeasToAdd = 0;
	$newInnovAVG = 0;
	$newFeasAVG = 0;
	
	//set the current scores for this design
	$curInnovAVG = mysql_result($AVGresult,$i,"InnovationTOT");
	$curFeasAVG = mysql_result($AVGresult,$i,"FeasibilityTOT");
	
	//loop through each word (checkbox)
	for ($j = 0; $j < 36; $j++)
	{
		//if the checkbox was selected add it's scores to the temporary ToAdd values
		if (isset($_POST['design'. $i .'word'. $j]))
		{
			$InnovToAdd += mysql_result($scoresResult,$j,"InnovationS");
			$FeasToAdd += mysql_result($scoresResult,$j,"FeasabilityS");
			$thisWord = $_POST['design'. $i .'word'. $j]. ' ';
			
			//add this word to the word list in the design entry
			$query = "UPDATE `designs` SET WordList = CONCAT(WordList, '". $thisWord ."')
					  WHERE `PollID` = ". $thisPoll ." AND `DesignID` = ". $i;
				mysql_query($query);
				
			//add this word to the word list in the user entry
			$query = "UPDATE `users` SET Words = CONCAT(Words, '".$thisWord ."')
					  WHERE `PollID` = ". $thisPoll ." AND `DesignID` = ". $i;
				mysql_query($query);
		}
	}
	
	//calculate a new average for both scores based on the number of current participants and the ToAdd values
	$newInnovAVG = ($curInnovAVG * ($NumPart / ($NumPart + 1))) + ($InnovToAdd * (1 / ($NumPart + 1)));
	$newFeasAVG = ($curFeasAVG * ($NumPart / ($NumPart + 1))) + ($FeasToAdd * (1 / ($NumPart +1)));
	
	//store new averages in the database for this design
	$query = "UPDATE `designs` SET `InnovationTOT` = ". $newInnovAVG ." 
			  WHERE `PollID` = ". $thisPoll ." AND `DesignID` = ". $i;
		mysql_query($query);
	$query = "UPDATE `designs` SET `FeasibilityTOT` = ". $newFeasAVG ." 
		      WHERE `PollID` = ". $thisPoll ." AND `DesignID` = ". $i;
		mysql_query($query);
}

//add 1 to the number of participants
$query = "UPDATE `polls` SET NumPart = NumPart + 1 WHERE `PollID` = ". $thisPoll;
	mysql_query($query);
	
mysql_close();
?>
<h1>Thank you, <?php echo $user; ?>, for your submission!</h1>

<img id="psulogo" src="pictures/PennState001.gif">
<img id="britelablogo" src="pictures/britelablogo.gif">

</body>
</html>
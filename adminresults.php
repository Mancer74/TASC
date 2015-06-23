<html>
<head>
<link rel="stylesheet" type="text/css" href="tascStyle.css">
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>!-->
</head>
<body>

<?php
include 'dbconnect.php';
mysql_connect($host, $username, $password);
@mysql_select_db($database) or die("Unable to select database");
?>
		
<?php
//get the admin ID from the URL
$adminID = $_GET['id'];
$resultSet = array();

//find the poll and it's relavent data
$query = "SELECT `PollID`, `Title`, `NumPart`, `Closed` FROM `polls` WHERE `AdminID` = '". $adminID ."'";
	$result = mysql_query($query);
	$thisPoll = mysql_result($result,0,"PollID");
	$title = mysql_result($result,0,"Title");
	$numPart = mysql_result($result,0,"NumPart");
	$isClosed = mysql_result($result,0,"Closed");

//get the number of designs for the poll
$query = "SELECT `DesignID` FROM `designs` WHERE `PollID` = ". $thisPoll;
	$result = mysql_query($query);
	$numDesigns = mysql_numrows($result);
?>


	
<?php	
//if the poll is not closed, give them and option to close and exit
if($isClosed == false)
{
	$query = "SELECT `UserName` FROM `users` WHERE `PollID` = ". $thisPoll ." AND DesignID = ". 0;
		$userResult = mysql_query($query);
	echo '<h1 id="smallerMargin">These people have participated in the Poll: </h1>';
	echo "<h4>";
	for ($i = 0; $i < $numPart; $i++)
	{
		$thisName = mysql_result($userResult,$i,"UserName");
		if($i > 0)
			echo ", ";
		echo $thisName;
	}
	echo "</h4><br>";
	echo '<h2>You cannot see results from this poll until it is closed </h2>
	<form method="POST" action="adminsplash.php">
	<input type="hidden" name="thisPoll" value='. $thisPoll .'>
	<input type="hidden" name="adminID" value="'. $adminID .'">
	<input type="submit" name="submitted" value="Close Poll">
	</form>';
	exit();
}

$query = "SELECT `DesignName`, `DesignID`, `InnovationTOT`, `FeasibilityTOT` FROM `designs` WHERE `PollID` = ". $thisPoll;
	$designResult = mysql_query($query);
	
$query = "SELECT `WordList` FROM `designs` WHERE `PollID` = ". $thisPoll;
	$wordListResult = mysql_query($query);

$query = "SELECT `StrRep` FROM `words`";
	$wordResult = mysql_query($query);
	
echo '<h1>Results for Poll "'. $title .'":</h1>';
echo "<br>";

for($i = 0; $i < $numDesigns; $i++)
{
	$innovationScore = mysql_result($designResult,$i,"InnovationTOT");
	$feasibilityScore = mysql_result($designResult,$i,"FeasibilityTOT");
	$resultTOT = $innovationScore + $feasibilityScore;
	$arrayToAdd = array();
	$arrayToAdd['result'] = $resultTOT;
	$arrayToAdd['design'] = $i;
	array_push($resultSet, $arrayToAdd);
}
usort($resultSet, 'sortByResult');
function sortByResult($a, $b)
{
	return $b['result'] - $a['result'];
}

$superCloudSet = array();
$idCounter = 1;
for($k = 0; $k < $numDesigns; $k++)
{
	$i = $resultSet[$k]['design'];
	$designID = mysql_result($designResult,$i,"DesignID");
	$thisName = mysql_result($designResult,$i,"DesignName");
	$thisWordList = mysql_result($wordListResult,$i,"WordList");
	$words = explode(" ", $thisWordList);
	$wordSet = array();
	$cloudSet = array();
	for($j = 0; $j < 36; $j++)
	{
		$thisWord = mysql_result($wordResult,$j,"StrRep");
		$wordSet[$thisWord] = 0;
	}
	
	for($j = 0; $j < count($words) - 1; $j++)
	{
		$thisKey = $words[$j];
		$wordSet[$thisKey] += 1;
	}
	
	$counter = 0;
	foreach($wordSet as $x => $x_value)
	{
		if($x_value > 0)
		{
			$cloudSet[$counter] = array($x, $x_value);
			$counter++;
		}
	}
	$superCloudSet[$k] = $cloudSet;
	
	echo '<div class="resultRow">';
	echo '<div id="resultBlock"><span>';
	if($k + 1 == 1)
		echo '<label class="resultPlace">1st</label>';
	else if($k + 1 == 2)
		echo '<label class="resultPlace">2nd</label>';
	else if($k + 1 == 3)
		echo '<label class="resultPlace">3rd</label>';
	else
		echo '<label class="resultPlace">'. $k + 1 . 'th</label>';
	echo '<label id="resultDesign">Design: </label><label>'. $thisName .'</label><br>';
	echo '<label id="resultResult">Result: </label><label>'. $resultSet[$k]['result'] .'</label>';
	echo '</span></div>';
	echo '<img src="designpictures/'. $thisPoll .'/'. $designID .'.png" style="width:500px;height:300px" alt="Image for Design '. $designID .'">';
	
	echo '
	<div class="tabGroup">
		<input type="radio" name="tabGroup'. $k .'" id="rad'. $idCounter .'" class="tab'. $idCounter .'" checked="checked"/>
		<label for="rad'. $idCounter .'">Word Cloud</label>
 
		<input type="radio" name="tabGroup'. $k .'" id="rad'. ($idCounter + 1) .'" class="tab'. ($idCounter + 1) .'"/>
		<label for="rad'. ($idCounter + 1) .'">Frequency Table</label>
		<br/>
		
		<div class="tab'. $idCounter .'">';
		echo '<canvas id="cloudcanvas'. $k .'" width="400" height="300"></canvas>';
		echo'</div>
		
		<div class="tab'. ($idCounter + 1) .'">';
		echo '<table class="freqTable" style="width: 150px">';
		echo '<tr>
		<th>Word</th>
		<th>Frequency</th></tr>';
		foreach($wordSet as $x => $x_value)
		{
			if($x_value > 0)
			{
				echo '<tr>
				<td>'. $x .'</td>
				<td>'. $x_value .'</td></tr>';
			}
		}
		echo '</table>';
		echo '</div>
		
	</div>
	';
	
	
	echo '<div id="tagCloud">';
	echo '</div><br><br><br>';
	echo "</div>";
	$idCounter += 2;
}
?>

<script type="text/javascript" src="wordcloud2/src/wordcloud2.js"></script>
<script type="text/javascript">
	var numDesigns = <?php echo $numDesigns ?>;
	var superlist = new Array(numDesigns);
	var superlist = <?php echo json_encode($superCloudSet) ?>;
	var list = new Array();
	for(var i = 0; i < numDesigns; i++)
	{
		list = superlist[i];
		WordCloud(document.getElementById('cloudcanvas' + i), { list: list, weightFactor: 20} );
	}
	
</script>


</body>
</html>
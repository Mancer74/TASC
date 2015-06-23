<html>
<head>
<link rel="stylesheet" type="text/css" href="tascStyle.css">
</head>
<body>
<?php
include 'dbconnect.php';
mysql_connect($host, $username, $password);
@mysql_select_db($database) or die("Unable to select database");
?>

<h1>Please choose between 3 and 5 of the options for each of the designs below:</h1><br>

<p id="checkboxError"></p>

<form name="designForm" action="handleinput.php" onsubmit="return checkCheckBoxes()" method="post">

<?php
//get the poll and number of designs
$thisPoll = $_POST['thisPoll'];
$numDesigns = $_POST['numDesigns'];

$query = "SELECT `DesignName`, `DesignID` FROM `designs` WHERE `PollID` = ". $thisPoll;
	$designResult = mysql_query($query);

//get the words from the database
$query = "SELECT `StrRep` FROM `words`";
	$wordResult = mysql_query($query);

//loop through the designs
for($i = 0; $i < $numDesigns; $i++)
{
	$designID = mysql_result($designResult,$i,"DesignID");
	$thisName = mysql_result($designResult,$i,"DesignName");
	//echo "Design Name: ". $thisName ."<br>";
	//display the picture
	echo '<img src="designpictures/'. $thisPoll .'/'. $designID .'.png" style="width:800px;height:500px" alt="Image for Design '. $designID .'"><br><br>';
	
	//loop through all 36 words for each design to create 36 checkboxes
	for ($j = 0; $j < 36; $j++)
	{
		//create a divider if it's the first checkbox
		if($j == 0)
		{
			if($i == ($numDesigns - 1))
				echo '<div class="checkboxDivider" id="lastDivider">';
			else
				echo '<div class="checkboxDivider">';
		}
		$thisWord = mysql_result($wordResult,$j,"StrRep");
		echo '<input type="checkbox" name="design'. $i .'word'. $j .'" value = "'. $thisWord .'">'. $thisWord . '<br>';
		
		//close the divider every 6 words
		if((($j-5) % 6) == 0)
		{
			echo '</div>';
			if($j != 35)
			{
				if($i == ($numDesigns - 1))
					echo '<div class="checkboxDivider" id="lastDivider">';
				else
					echo '<div class="checkboxDivider">';
			}
		}
		
	}
	echo "<br>";
}
?>

<label>Enter your name: </label>
<input type="text" name="user" required><br><br><br><br>
<input type="hidden" name="thisPoll" value="<?php echo $thisPoll ?>">
<input type="hidden" name="numDesigns" value="<?php echo $numDesigns ?>">
<input type="submit">

</form>

<img id="psulogo" src="pictures/PennState001.gif">
<img id="britelablogo" src="pictures/britelablogo.gif">

<!-- checkbox validation !-->
<script type="text/javascript" language="JavaScript">
function checkCheckBoxes(theForm) 
{
	var count = 0;
	var numberDesigns = <?php Print($numDesigns); ?>;
	//loop through the designs
	for(i = 0; i < numberDesigns; i++)
	{
		//count how many checkboxes are checked for each design
		count = 0;
		for(j = 0; j < 36; j++)
		{
			var x = document.forms["designForm"]["design" + i + "word" + j].checked;
			if(x == true)
				count++;
		}
		
		//if the count is not between 3 and 5 do not submit (return false)
		if(count < 3 || count > 5)
		{
			document.getElementById("checkboxError").innerHTML = "You must check between 3 and 5 options for each design";
			document.body.scrollTop = document.documentElement.scrollTop = 0;
			return false;
		}
		
	}
	return true;
}
//-->
</script>

</body>
</html>


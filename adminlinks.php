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

<?php
function generateRandomString($length = 6) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
<br>

<?php
$newLink = generateRandomString();			//generate a random string for admin ID
$userLink = generateRandomString();			//generate a random string for user ID
$userName = $_POST['userName'];
$adminEmail = $_POST['email'];
$newTitle = $_POST['title'];
$newDescription = $_POST['description'];
$todaysDate = date("Y-m-d H:i:s");
$titles = array();
$descriptions = array();
$fileNameArray = array();

//get the file names
if (isset($_POST['fileNameArray']))
	$fileNameArray = $_POST['fileNameArray'];
	
//query for creation of new poll
$query = "INSERT INTO `polls` (`AdminID`, `UserID`, `DateOfLA`, `Title`, `Description`, `AdminEmail`, `AdminName`) 
		  VALUES ('". $newLink ."','". $userLink ."','". $todaysDate ."','". $newTitle ."','". $newDescription ."','". $adminEmail ."','". $userName ."')";
	mysql_query($query);
	
//query to get the poll id of the poll just created
$query = "SELECT `PollID` FROM `polls` WHERE `AdminID` = '". $newLink ."'";
	$result = mysql_query($query);
	$thisPoll = mysql_result($result,0,"PollID");

//final destination for the uploaded pictures
$target_path = 'designpictures/'. $thisPoll .'/';
if (!file_exists('designpictures/'. $thisPoll)) 
	mkdir('designpictures/'. $thisPoll, 0777, true);

$numDesigns = $_POST['numDesigns'];

//loop through designs
$i = 0;
while($i < $numDesigns)
{
	$thisDescription;
	
	//get the description for this design
	if(isset($_POST['description'. $i]))
		$thisDescription = $_POST['description'. $i];
	else
		$thisDescription = "";
	
	$tempLocation = "tempUpload/". $fileNameArray[$i];
	$imageFileType = pathinfo($tempLocation,PATHINFO_EXTENSION);
	$newFileLocation = $target_path. $i .".". $imageFileType;
	
	//move the file
	if(rename($tempLocation, $newFileLocation))
		echo "The file ".  basename($fileNameArray[$i]). " has been uploaded<br>";
			
	else
		echo "There was an error uploading the file, please try again!<br>";
	
	//get the title for this design and create a new entry in the database
	$thisTitle = $_POST['title'. $i];
	$query = "INSERT INTO `designs` (`PollID`, `DesignID`, `DesignName`, `DesignDescription`) VALUES (". $thisPoll .", ". $i .", '". $thisTitle ."', '". $thisDescription ."')";
		mysql_query($query);
	$i++;
}

mysql_close();
?>
<br>
Admin Link: <a href="adminresults.php?id=<?php echo $newLink; ?>">adminresults.php?id=<?php echo $newLink; ?></a><br><br>
User Link: <a href="userpage.php?id=<?php echo $userLink; ?>">userpage.php?id=<?php echo $userLink; ?></a><br><br>

<form action="donepage.php" method="post">
	<input type="hidden" name="adminEmail" value="<?php echo $adminEmail; ?>">
	<input type="hidden" name="newLink" value="<?php echo $newLink; ?>">
	<input type="hidden" name="userLink" value="<?php echo $userLink; ?>">
    <input type="submit" value="Email me these links!"/> 
</form>

<img id="psulogo" src="pictures/PennState001.gif">
<img id="britelablogo" src="pictures/britelablogo.gif">

</body>
</html>

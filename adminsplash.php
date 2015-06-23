
<html>
<head>
<link rel="stylesheet" type="text/css" href="tascStyle.css">
</head>
<body>

<h1>This poll has been closed! </h1><br>
<label id="smallLabel">Head back to your admin link to see results: </label><br>

<?php
include 'dbconnect.php';
mysql_connect($host, $username, $password);
@mysql_select_db($database) or die("Unable to select database");
?>

<?php
$thisPoll = $_POST['thisPoll'];
$adminID = $_POST['adminID'];
if (isset($_POST['submitted']))
{
	$query = "UPDATE `polls` SET Closed=1 WHERE `PollID` = ". $thisPoll;
		mysql_query($query);
}

?>

<a href="adminresults.php?id=<?php echo $adminID; ?>">adminresults.php?id=<?php echo $adminID; ?></a>

</body>
</html>
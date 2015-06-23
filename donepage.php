<html>
<body>

<?php
$newLink = $_POST['newLink'];
$userLink = $_POST['userLink'];
$adminEmail = $_POST['adminEmail'];
$emailSubject = "Here is your TASC email";
$emailHeader = "From: TASCdatabase@psu.edu\n";
$message = "Your admin Link: TASC/adminresults.php?id=". $newLink ."/n
			Send this link to your users: TASC/userpage.php?id=". $userLink;
mail($adminEmail, $emailSubject, $message, $emailHeader);
echo 'Email Sent. Thank you for creating a poll on the TASC Website!';
?>

<img id="psulogo" src="pictures/PennState001.gif">
<img id="britelablogo" src="pictures/britelablogo.gif">

</body>
</html>
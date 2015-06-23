<html>
<head>
<link rel="stylesheet" type="text/css" href="tascStyle.css">
</head>
<body>
<h3>Please provide us with a little bit of information about yourself</h3>

<!-- Create form for user name and email !-->
<!-- Uses required (HTML5) for form validation !-->

<div class="formContainer">
<form action="adminpage2.php" method="post">

<span class="error" id="reqfield">* required field.</span><br>
<label>Your Name:</label>
<input type="text" name="userName" required>
<span class="error">* </span><br>
<label>Your Email Address:</label>
<input type="email" name="email" required>
<span class="error">* </span><br>

<br><br>
<input id="nextbutton" type="submit" name="Next" value="Next">

<img id="psulogo" src="pictures/PennState001.gif">
<img id="britelablogo" src="pictures/britelablogo.gif">

</form>
</div>
</body>
</html>
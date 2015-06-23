<html>
<head>
<link rel="stylesheet" type="text/css" href="tascStyle.css">
</head>

<body>
<?php			
$fileNameArray = array();	//array for remembering temporarily uploaded files
$titles = array();			//array for remembering user set names for files
$descriptions = array();	//array for remembering user set descriptions for files
?>


<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

<!-- Create the top menu bar !-->
<div class="topBar">
<label>Project Title:</label>

<!-- if the user has already entered a title, auto fill the field !-->
<?php if (isset($_POST['title']))
	echo '<input type="text" name="title" value="'. $_POST['title'] .'" required>';
else
	echo '<input type="text" name="title" required>'; ?>
	
<input type="file" id="chooseFileButton" name="upload[]" multiple="multiple"><br>
<label for="desc">Project Description:</label>

<!-- if the user has already entered a description, auto fill the field !-->
<?php if(isset($_POST['description']))
	echo '<textarea id="desc" name="description" rows="2" cols="35">'. $_POST['description'] .'</textarea>';
else
	echo '<textarea id="desc" name="description" rows="2" cols="35"></textarea>'; ?>

<input type="submit" id="uploadButton" value="Upload Pictures"><br>
</div>

<?php

//set numDesigns to the number of designs the user has uploaded so far
if(isset($_POST['numDesigns']))
	$numDesigns = $_POST['numDesigns'];
else
	$numDesigns = 0;

//if the user has uploaded files, remember their names and descriptions
if(isset($_POST['fileNameArray']))
	$fileNameArray = $_POST['fileNameArray'];
if(isset($_POST['descriptions']))
	$descriptions = $_POST['descriptions'];

//loop through the designs
for($i=0; $i<$numDesigns; $i++)
{
	//if the user submitted via a delete button, remove this file from the name array, 
    //refactor the array indicies, and decrement the total number of designs
	if (isset($_POST['delete'. $i]))
	{
		unset($fileNameArray[$i]);
		$fileNameArray = array_values($fileNameArray);
		$numDesigns--;
	}
	
	//remember the user title and description (if it exists) for this file
	if(isset($_POST['title'. $i]))
		$titles[$i] = $_POST['title'. $i];
	if(isset($_POST['description'. $i]))
		$descriptions[$i] = $_POST['description'. $i];
}

//loop through all currently uploading files
for($i=0; $i<count($_FILES['upload']['name']); $i++)
{
	$uploadOk = 1;
	
    //Get the temp file path and extension
	$tmpFilePath = $_FILES['upload']['tmp_name'][$i];
	$imageFileType = pathinfo($_FILES['upload']['name'][$i], PATHINFO_EXTENSION);
	
	//Check file size
	if (filesize($_FILES['upload']['tmp_name'][$i]) > 100000000)
	{
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	
	//Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) 
	{
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	
	//Make sure we have a filepath
	if ($tmpFilePath != "" && $uploadOk == 1)
	{
		//Setup our new file path
		$newFilePath = "C:/xampp/htdocs/TASC/tempUpload/" . $_FILES['upload']['name'][$i];

		//Upload the file into the temp dir
		if(move_uploaded_file($tmpFilePath, $newFilePath)) 
		{
			//remember the file name by adding it to fileNameArray and increment the number of designs
			array_push($fileNameArray, $_FILES['upload']['name'][$i]);
			$numDesigns++;
		}
	}
	else
		echo "Your file was not uploaded";
}

//loop through designs in order to create boxes for each design
for($i=0; $i<$numDesigns; $i++)
{
	$path = "tempUpload/". $fileNameArray[$i];		//temporary file path
	$ext = pathinfo($path, PATHINFO_EXTENSION);		//file extension of temporary file
	$thisTitle = basename($path, ".". $ext);		//the name of the temporary file without its extension
	
	//create the box
	echo '<div class="uploadContainer">';
	
	//if the user had previously entered a title, use it. Otherwise, use thisTitle
	if ($i < count($titles))
		echo 'Title: <input type="text" name="title'. $i .'" value="'. $titles[$i] .'"><br>';
	else
		echo 'Title: <input type="text" name="title'. $i .'" value="'. $thisTitle .'"><br>';

	echo '<img src="tempUpload/'. $fileNameArray[$i] .'" alt="Temp Image" style="width:300px;height:200px"><br>';
	echo "<label> Design Description (Optional): </label>";
	
	//if the user had previously entered a description, use it.
	if ($i < count($descriptions))
		echo '<textarea id="description" name="description'. $i .'"   rows="7" cols="35">'. $descriptions[$i] .'</textarea><br>';
	else
		echo '<textarea id="description" name="description'. $i .'" rows="7" cols="35"></textarea><br>';
	echo '<input type="submit" name="delete'. $i .'" value="delete"<br>';
	echo '</div>';
}

//pass along the fileNameArray
foreach($fileNameArray as $value)
	echo '<input type="hidden" name="fileNameArray[]" value="'. $value .'">';

?>

<!-- pass along required and already entered information !-->
<input type="hidden" name="userName" value="<?php echo $_POST['userName']; ?>">
<input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
<input type="hidden" name="numDesigns" value="<?php echo $numDesigns; ?>">
<br><br><br>

<?php

//if the user has not uploaded anything do not allow them to continue to next page
if ($numDesigns == 0)
	echo '<h1>You must upload at least one design picture to continue</h1>';
else
	echo '<input type="submit" id="doneButton" value="Done" formaction="adminlinks.php"><br>';
?>
</form>

<img id="psulogo" src="pictures/PennState001.gif">
<img id="britelablogo" src="pictures/britelablogo.gif">

</body>
</html>
<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

// Set the uplaod directory
if ($_POST['location'] == 'uploads')
	$uploadDir = dirname(__FILE__).'/../../uploads/';
else
	$uploadDir = dirname(__FILE__).'/../../views/img/iconsets/';

// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'txt'); // Allowed file extensions

if (!empty($_FILES)) 
{
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	#$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) 
	{
		// Save the file
		move_uploaded_file($tempFile, $targetFile);
		echo $_FILES['Filedata']['name'];
	} 
	else 
	{
		// The file type wasn't allowed
		echo 'error2';
	}
}
?>
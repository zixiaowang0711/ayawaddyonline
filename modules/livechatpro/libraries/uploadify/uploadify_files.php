<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
*/

$target_folder = dirname(__FILE__).'/../../uploads';

if (!empty($_FILES)) 
{
	$temp_file = $_FILES['Filedata']['tmp_name'];
	$target_path = $_SERVER['DOCUMENT_ROOT'].$target_folder;
	$target_file = rtrim($target_path, '/').'/'.$_FILES['Filedata']['name'];
	
	/* Validate the file type */
	$file_types = array('jpg', 'jpeg', 'gif', 'png', 'ico', 'svg', 'psd', 'txt', 'pdf', 'htm', 'html', 'tpl', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', 'zip', 'rar', 'mp3', 'm4a', 'ogg', 'wav', 'mp4', 'mov', 'wmv', 'avi', 'mpg', 'ogv', '3gp', '3g2');
	/* File extensions */
	$file_parts = pathinfo($_FILES['Filedata']['name']);
	
	/* random file name */
	$uniqid = uniqid();
	$target_file = sprintf('%s/%s.%s', $target_path, $uniqid, $file_parts['extension']);
	
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$target_folder.'/'.$_POST['Filename'])) echo 'error1';
	elseif (in_array($file_parts['extension'], $file_types)) 
	{
		move_uploaded_file($temp_file, $target_file);
		echo $uniqid.'.'.$file_parts['extension'];
	} 
	else echo 'error2';
}
?>
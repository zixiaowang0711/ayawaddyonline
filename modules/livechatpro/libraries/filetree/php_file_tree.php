<?php
/*
	
	== PHP FILE TREE ==
	
		Let's call it...oh, say...version 1?
	
	== AUTHOR ==
	
		Cory S.N. LaViska
		http://abeautifulsite.net/
		
	== DOCUMENTATION ==
	
		For documentation and updates, visit http://abeautifulsite.net/notebook.php?article=21
		
*/


function php_file_tree($directory, $return_link, $extensions = array(), $excluded_dirs = array()) {

	$_SESSION['array_file_tree'] = array();
	// Generates a valid XHTML list of all directories, sub-directories, and files in $directory
	// Remove trailing slash
	if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
	@$code .= php_file_tree_dir($directory, $return_link, $extensions, true, $excluded_dirs);
	return $code;
}

function php_file_tree_dir($directory, $return_link, $extensions = array(), $first_call = true, $excluded_dirs = array()) {
	// Recursive function called by php_file_tree() to list directories/files
	
	// Get and sort directories/files
	if( function_exists("scandir") ) $file = scandir($directory); else $file = php4_scandir($directory);
	natcasesort($file);
	// Make directories first
	$files = $dirs = array();
	foreach($file as $this_file) {

		#echo "$directory/$this_file"."<br>";
		#echo "<pre>"; print_r($excluded_dirs);
		if (!in_array("$directory/$this_file", $excluded_dirs)) {
				#echo "$directory/$this_file"."<br>";
			
			if( is_dir("$directory/$this_file" ) ) {
				
				$dirs[] = $this_file;
				
			} else {
				$files[] = $this_file;
			}
		} 

	}

	$file = array_merge($dirs, $files);
	

	// Filter unwanted extensions
	if( !empty($extensions) ) {
		foreach( array_keys($file) as $key ) {
			if( !is_dir("$directory/$file[$key]") ) {
				$ext = substr($file[$key], strrpos($file[$key], ".") + 1); 
				if( !in_array($ext, $extensions) ) unset($file[$key]);
			}
		}
	}

	
	if( count($file) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
		$php_file_tree = "<ul";
		if( $first_call ) { $php_file_tree .= " class=\"php-file-tree\""; $first_call = false; }
		$php_file_tree .= ">";
		foreach( $file as $this_file ) {
			if( $this_file != "." && $this_file != ".." ) {
				if( is_dir("$directory/$this_file") ) {
					// Directory
					#print_r($file_tree)."<br>";
					$file_tree = php_file_tree_dir("$directory/$this_file", $return_link ,$extensions, false, $excluded_dirs);
					#$array_file_tree[] = $file_tree;

					#$php_file_tree .= "<li class=\"pft-directory\"><a href=\"#\">" . htmlspecialchars($this_file) . "</a>";
					#$php_file_tree .= php_file_tree_dir("$directory/$this_file", $return_link ,$extensions, false, $excluded_dirs);
					#$php_file_tree .= "</li>";
				} else {
					// File
					
					$file_tree = "$directory/$this_file";
					$_SESSION['array_file_tree'][] = $file_tree;

					// Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
					#$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1); 
					#$link = str_replace("[link]", "$directory/" . urlencode($this_file), $return_link);
					#$php_file_tree .= "<li class=\"pft-file " . strtolower($ext) . "\"><a href=\"$link\">" . htmlspecialchars($this_file) . "</a></li>";
				}
			}
		}
		$php_file_tree .= "</ul>";
	}

	#echo "<pre>"; print_r($_SESSION['array_file_tree']);
	#Session::put('array_file_tree', $_SESSION['array_file_tree']);
	


	return $file_tree;
	#return $php_file_tree;
}

// For PHP4 compatibility
function php4_scandir($dir) {
	$dh  = opendir($dir);
	while( false !== ($filename = readdir($dh)) ) {
	    $files[] = $filename;
	}
	sort($files);
	return($files);
}

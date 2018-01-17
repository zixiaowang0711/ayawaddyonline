<?php
session_start();

$from_iso = 'en';
$to_iso = 'sv';


$from_file_name = 'lang_'.$from_iso.'.php';
$check_file_name = 'old/'.$to_iso.'.php';
$to_file_name = 'lang_'.$to_iso.'.php';

include(dirname(__FILE__).'/'.$from_file_name);
include(dirname(__FILE__).'/'.$check_file_name);

function keyMatch($str, $array)
{
	$found = false;
	foreach($array as $k => $v)
	{
		//echo $k.' = '.$str."\n";
		if (stristr($k, $str) !== false)
		{
			$found = true;
			$return = $v;
		}
		//else
			//$_SESSION['not_found'][] = $str;
	}
	if ($found == true)
		return $return;
	else
	{
		return false;
	}

}

$language_file_contents = '<?php'."\n";
foreach($_LANG as $key => $value)
{
	$exp = explode('{', $key);
	$exp2 = explode('}', $exp[2]);
	$md5 = $exp2[0];

	$translation = keyMatch($md5, $_MODULE);

	if ($translation != false)
	{
		$language_file_contents .=  '$_LANG[\''.$key.'\'] = '."'".$translation."';"."\n";
	}
	
}

#echo $language_file_contents;
$file_write = dirname(__FILE__).'/'.$to_file_name;
$handle = fopen($file_write, 'w') or die("can't open file");
fwrite($handle, $language_file_contents);
fclose($handle);

//echo "<pre>"; print_r($_SESSION['not_found']); echo "</pre>";



?>
<?php

$codeType = 'code128';

$version = phpversion();

if($version > 6)
{
	$barcode_file = $codeType.'.php';
	include 'PHP7/code/'.$barcode_file;
}
else
{
	include 'barcode_v5.php';
}

?>

<?php

ignore_user_abort(true);
set_time_limit(0); // disable the time limit for this script
$file_path = $_GET['file'];
if(file_exists($file_path)){
	#get file infos
	$file_type = "application/pdf";//mime_content_type($file_path);#get file type
	$file_size = filesize($file_path);
	$file_basename = basename($file_path);
	#set file infos
	header("Content-Description: File Transfer");
    header("Content-Type: {$file_type}");
    header("Content-Disposition: attachment; filename={$file_basename}");
    header("Expires: 0");
    header("Cache-Control: must-revalidate");
    header("Pragma: public");
    header("Content-Length: {$file_size}");
    readfile($file_path);
	exit;
}else{
	echo "Dosya bulunamadi!";
}
?>
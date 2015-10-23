<?php
session_start();

include_once (__DIR__.'/service/OfferService.php');
include_once (__DIR__.'/service/CancelService.php');
include_once (__DIR__.'/Util/session.php');

if(!Session::exists(Session::USER)){
	echo "Giriş Yapılmadı!";
	return;
}

ignore_user_abort(true);
set_time_limit(0); // disable the time limit for this script
$id = $_GET['id'];
$type = $_GET['type'];

$file_path = "";
if($type == 'policy'){
	$offerService = new OfferService();
	$policy = $offerService->getCompletedPolicy($id);
	$file_path = $policy['POLICY_PATH'];
}else if($type == 'makbuz'){
	$offerService = new OfferService();
	$policy = $offerService->getCompletedPolicy($id);
	$file_path = $policy['MAKBUZ_PATH'];
}else if($type == 'cancel'){
	$cancelService = new CancelService();
	$cancel = $cancelService->getCancelRequest($id);
	$file_path = $cancel['SOZLESME'];
}

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
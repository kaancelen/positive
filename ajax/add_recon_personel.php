<?php
include_once (__DIR__.'/../Util/init.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}else{
		$user = Session::get(Session::USER);
		if(!($user[User::ROLE] == User::PERSONEL || $user[User::ROLE] == User::ADMIN)){
			$logger->write(ALogger::INFO, __FILE__, "Request not from personel");
			echo json_encode(false);
			return;
		}
	}
	
	$takip_no = Util::cleanInput($_POST['TAKIP_NO']);
	$net = Util::cleanInput($_POST['NET']);
	$net = str_replace(".", "", $net);
	$net = str_replace(",", ".", $net);
	$net = floatval($net);
	
	$recon_update_params = array(
			'KAYNAK' => Util::cleanInput($_POST['KAYNAK']),
			'URETIM_KANALI' => Util::cleanInput($_POST['URETIM_KANALI']),
			'MUSTERI_TIPI' => Util::cleanInput($_POST['MUSTERI_TIPI']),
			'YENI_TECDIT' => Util::cleanInput($_POST['YENI_TECDIT']),
			'ZEYIL_NO' => Util::cleanInput($_POST['ZEYIL_NO']),
			'PARA_BIRIMI' => Util::cleanInput($_POST['PARA_BIRIMI']),
			'NET' => $net
	);
	
	$reconService = new ReconService();
	$result = $reconService->updateRecon($takip_no, $recon_update_params);
	
	if($result){
		Session::flash(Session::FLASH, "Mutabakat bilgileri güncellendi.");
	}
	
	echo json_encode($result);
}

?>
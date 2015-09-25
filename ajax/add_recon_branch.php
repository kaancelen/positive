<?php
include_once (__DIR__.'/../Util/init.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}else{
		$user = Session::get(Session::USER);
		if(!($user[User::ROLE] == User::BRANCH || $user[User::ROLE] == User::ADMIN)){
			$logger->write(ALogger::INFO, __FILE__, "Request not from personel");
			echo json_encode(false);
			return;
		}
	}
	
	$takip_no = Util::cleanInput($_POST['TAKIP_NO']);
	
	$recon_update_params = array(
			'MUSTERI_ADI' => Util::cleanInput($_POST['MUSTERI_ADI']),
			'BASLANGIC_TARIHI' => Util::cleanInput($_POST['BASLANGIC_TARIHI']),
			'BITIS_TARIHI' => Util::cleanInput($_POST['BITIS_TARIHI']),
	);
	
	$reconService = new ReconService();
	$result = $reconService->updateRecon($takip_no, $recon_update_params);
	
	if($result){
		Session::flash(Session::FLASH, "Mutabakat bilgileri güncellendi.");
	}
	
	echo json_encode($result);
}

?>
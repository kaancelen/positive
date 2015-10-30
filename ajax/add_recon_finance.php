<?php
include_once (__DIR__.'/../Util/init.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}else{
		$user = Session::get(Session::USER);
		if(!($user[User::ROLE] == User::FINANCE || $user[User::ROLE] == User::ADMIN)){
			$logger->write(ALogger::INFO, __FILE__, "Request not from personel");
			echo json_encode(false);
			return;
		}
	}
	
	$takip_no = Util::cleanInput($_POST['TAKIP_NO']);
	
	$heroKomisyon = Util::cleanInput($_POST['HERO_KOMISYON']);
	$heroKomisyon = str_replace(".", "", $heroKomisyon);
	$heroKomisyon = str_replace(",", ".", $heroKomisyon);
	$heroKomisyon = floatval($heroKomisyon);
	$bolgeKomisyon = Util::cleanInput($_POST['BOLGE_KOMISYON']);
	$bolgeKomisyon = str_replace(".", "", $bolgeKomisyon);
	$bolgeKomisyon = str_replace(",", ".", $bolgeKomisyon);
	$bolgeKomisyon = floatval($bolgeKomisyon);
	$subeKomisyon = Util::cleanInput($_POST['SUBE_KOMISYON']);
	$subeKomisyon = str_replace(".", "", $subeKomisyon);
	$subeKomisyon = str_replace(",", ".", $subeKomisyon);
	$subeKomisyon = floatval($subeKomisyon);
	$bagliKomisyon = Util::cleanInput($_POST['BAGLI_KOMISYON']);
	$bagliKomisyon = str_replace(".", "", $bagliKomisyon);
	$bagliKomisyon = str_replace(",", ".", $bagliKomisyon);
	$bagliKomisyon = floatval($bagliKomisyon);
	$ustProduktorKomisyon = Util::cleanInput($_POST['UST_PRODUKTOR_KOMISYON']);
	$ustProduktorKomisyon = str_replace(".", "", $ustProduktorKomisyon);
	$ustProduktorKomisyon = str_replace(",", ".", $ustProduktorKomisyon);
	$ustProduktorKomisyon = floatval($ustProduktorKomisyon);
	$musteriyeIade = Util::cleanInput($_POST['MUSTERIYE_IADE']);
	$musteriyeIade = str_replace(".", "", $musteriyeIade);
	$musteriyeIade = str_replace(",", ".", $musteriyeIade);
	$musteriyeIade = floatval($musteriyeIade);
	$prodKomisyon = Util::cleanInput($_POST['PROD_KOMISYON']);
	$prodKomisyon = str_replace(".", "", $prodKomisyon);
	$prodKomisyon = str_replace(",", ".", $prodKomisyon);
	$prodKomisyon = floatval($prodKomisyon);
	$merkez = Util::cleanInput($_POST['MERKEZ']);
	$merkez = str_replace(".", "", $merkez);
	$merkez = str_replace(",", ".", $merkez);
	$merkez = floatval($merkez);
	
	$recon_update_params = array(
			'BOLGE' => Util::cleanInput($_POST['BOLGE']),
			'BAGLI' => Util::cleanInput($_POST['BAGLI']),
			'UST_PRODUKTOR' => Util::cleanInput($_POST['UST_PRODUKTOR']),
			'TAHSILAT_DURUMU' => Util::cleanInput($_POST['TAHSILAT_DURUMU']),
			'SIRKET_TAHSILAT_DURUMU' => Util::cleanInput($_POST['SIRKET_TAHSILAT_DURUMU']),
			'ACIKLAMA' => Util::cleanInput($_POST['ACIKLAMA']),
			'HERO_KOMISYON' => $heroKomisyon,
			'BOLGE_KOMISYON' => $bolgeKomisyon,
			'SUBE_KOMISYON' => $subeKomisyon,
			'BAGLI_KOMISYON' => $bagliKomisyon,
			'UST_PRODUKTOR_KOMISYON' => $ustProduktorKomisyon,
			'MUSTERIYE_IADE' => $musteriyeIade,
			'PROD_KOMISYON' => $prodKomisyon,
			'MERKEZ' => $merkez
	);
	
	$reconService = new ReconService();
	$result = $reconService->updateRecon($takip_no, $recon_update_params);
	
	if($result){
		Session::flash(Session::FLASH, "Mutabakat bilgileri güncellendi.");
	}
	
	echo json_encode($result);
}

?>
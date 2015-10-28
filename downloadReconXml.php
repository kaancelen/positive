<!-- BRANCH -->
<head>
<?php 
	include_once(__DIR__.'/head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/Util/init.php');
	include_once (__DIR__.'/classes/Recon.php');
	if($loggedIn){
		$user = Session::get(Session::USER);
	}
	$month = Util::cleanInput(urldecode($_GET['month']));
	$year = Util::cleanInput(urldecode($_GET['year']));
	
	$reconService = new ReconService();
	$allRecons = $reconService->getRecons($month, $year, $user[User::ID], $user[User::ROLE]);
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" '.'standalone="yes"?><recons/>');
	
	foreach ($allRecons as $recon){
		$track = $xml->addChild('recon');
		$track->addChild('TAKİP_NO', $year.'-'.$month.'-'.$recon[Recon::TAKIP_NO]);
		$track->addChild('ÜRETİM_İPTAL', $recon[Recon::URETIM_IPTAL]);
		$track->addChild('KAYNAK', $recon[Recon::KAYNAK]);
		$track->addChild('ÜRETİM_KANALI', $recon[Recon::URETIM_KANALI]);
		$track->addChild('MÜŞTERİ_TİPİ', $recon[Recon::MUSTERI_TIPI]);
		$track->addChild('YENİ_TECDİT', $recon[Recon::YENI_TECDIT]);
		$track->addChild('POLİÇE_NO', $recon[Recon::POLICE_NO]);
		$track->addChild('ZEYİL_NO', $recon[Recon::ZEYIL_NO]);
		$track->addChild('MÜŞTERİ_ADI', $recon[Recon::MUSTERI_ADI]);
		$track->addChild('TC_VKN', $recon[Recon::TCKN]." ".$recon[Recon::VERGI_NO]);
		$track->addChild('TANZİM_TARİHİ', $recon[Recon::TANZIM_TARIHI]);
		$track->addChild('PRODÜKTÖR', $recon[Recon::PRODUKTOR]);
		$track->addChild('BAŞLANGIÇ_TARİHİ', $recon[Recon::BASLANGIC_TARIHI]);
		$track->addChild('BİTİŞ_TARİHİ', $recon[Recon::BITIS_TARIHI]);
		$track->addChild('SİGORTA_ŞİRKETİ', $recon[Recon::SIRKET]);
		$track->addChild('POLİÇE_TÜRÜ', $recon[Recon::POLICE_TURU]);
		$track->addChild('PARA_BİRİMİ', $recon[Recon::PARA_BIRIMI]);
		$track->addChild('BRÜT', $recon[Recon::BRUT]);
		$track->addChild('NET', $recon[Recon::NET]);
		$track->addChild('KOMİSYON', $recon[Recon::KOMISYON]);
		$track->addChild('PRODÜKTÖR_KOMİSYONU', $recon[Recon::PROD_KOMISYON]);
		//BRANCH shouldnt see this infos
		if($user[User::ROLE] != User::BRANCH){
			$track->addChild('BÖLGE', $recon[Recon::BOLGE]);
			$track->addChild('BAĞLI', $recon[Recon::BAGLI]);
			$track->addChild('BAĞLI_KOMİSYON', $recon[Recon::BAGLI_KOMISYON]);
			$track->addChild('ÜST_PRODÜKTÖR', $recon[Recon::UST_PRODUKTOR]);
			$track->addChild('ÜST_PRODÜKTÖR_KOMİSYON', $recon[Recon::UST_PRODUKTOR_KOMISYON]);
			$track->addChild('TAHSİLAT_DURUMU', $recon[Recon::TAHSILAT_DURUMU]);
			$track->addChild('ŞİRKET_TAHSİLAT_DURUMU', $recon[Recon::SIRKET_TAHSILAT_DURUMU]);
			$track->addChild('AÇIKLAMA', $recon[Recon::ACIKLAMA]);
			$track->addChild('HERO_KOMİSYON', $recon[Recon::HERO_KOMISYON]);
			$track->addChild('BÖLGE_KOMİSYON', $recon[Recon::BOLGE_KOMISYON]);
			$track->addChild('ŞUBE_KOMİSYON', $recon[Recon::SUBE_KOMISYON]);
			$track->addChild('MÜŞTERİYE_İADE', $recon[Recon::MUSTERIYE_IADE]);
			$track->addChild('MERKEZ', $recon[Recon::MERKEZ]);
		}
	}
	$filePath = __DIR__."/files/recon.xml";
	$xml->asXML($filePath);
	Util::redirect("/positive/download2.php?file=".$filePath);
?>
</body>
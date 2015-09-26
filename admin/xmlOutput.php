<!-- ADMIN -->
<head>
<?php 
	include_once(__DIR__.'/../head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/../Util/init.php');
	include_once (__DIR__.'/../service/SearchService.php');
	if($loggedIn){
		$user = Session::get(Session::USER);
		if($user[User::ROLE] != User::ADMIN){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');
	
	$month = 8;
	$year = 2015;
	//ALL POLICIES
	$searchService = new SearchService();
	$policies = $searchService->getPoliciesInMonth($month, $year);
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" '.'standalone="yes"?><policies/>');
	
	foreach ($policies as $policy){
		$track = $xml->addChild('policy');
		$track->addChild('PoliçeNo', $policy[Policy::POLICY_NUMBER]);
		$track->addChild('PoliçeTarihi', DateUtil::format($policy[Policy::POLICY_COMPLETE_DATE]));
		$track->addChild('PoliçeTürü', $policy[Policy::POLICY_TYPE]);
		$track->addChild('Şirket', $policy[Policy::COMPANY_NAME]);
		$track->addChild('TalepNo', $policy[Policy::REQUEST_ID]);
		$track->addChild('TCKimlikNo', $policy[Policy::TCKN]);
		$track->addChild('VergiNo', $policy[Policy::VERGI]);
		$track->addChild('EkBilgi', $policy[Policy::EK_BILGI]);
		$track->addChild('Acenta', $policy[Policy::BRANCH_NAME]);
		$track->addChild('Prim', $policy[Policy::PRIM]);
		$track->addChild('Komisyon', $policy[Policy::KOMISYON]);
		$track->addChild('ProdKomisyonu', $policy[Policy::PROD_KOMISYON]);
	}
	$filePath = __DIR__."/../files/policy_test.xml";
	$xml->asXML($filePath);
	
	//ALL OFFERS
	$offers = $searchService->getOffersInMonth($month, $year);
	
	$xmlOffer = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" '.'standalone="yes"?><offers/>');
	
	foreach ($offers as $offer){
		$durum = "";
		if($offer['POLICY_ID'] != 0){
			$durum = "Teklif poliçeleşti";
		}else{
			switch ($offer['STATUS']) {
				case 0: $durum="Teklif seçilmedi"; break;
				case 1: $durum="Teklif seçildi, ancak poliçeleşmedi"; break;
				case 2: $durum="Talep kapatıldı"; break;
				case 3: $durum="Poliçe isteği kapatıldı"; break;
			}
		}
		
		$track = $xmlOffer->addChild('offer');
		$track->addChild('Talep_No', $offer['REQUEST_ID']);
		$track->addChild('Talep_Tarihi', DateUtil::format($offer['REQUEST_DATE']));
		$track->addChild('Acente', $offer['BRANCH_NAME']);
		$track->addChild('Şirket', $offer['COMPANY_NAME']);
		$track->addChild('Poliçe_Türü', $offer['POLICY_TYPE']);
		$track->addChild('Plaka', $offer['PLAKA']);
		$track->addChild('Tckn', $offer['TCKN']);
		$track->addChild('Vergi', $offer['VERGI']);
		$track->addChild('Belge', $offer['BELGE']);
		$track->addChild('Asbis', $offer['ASBIS']);
		$track->addChild('Ek_Bilgi', $offer['EK_BILGI']);
		$track->addChild('Teklif_No', $offer['OFFER_ID']);
		$track->addChild('Teklif_Tarihi', $offer['OFFER_DATE']);
		$track->addChild('Teklif_Veren', $offer['PERSONEL_NAME']);
		$track->addChild('Prim', $offer['PRIM']);
		$track->addChild('Komisyon', $offer['KOMISYON']);
		$track->addChild('Prod_Komisyon', $offer['PROD_KOMISYON']);
		$track->addChild('Takip_No', $offer['POLICY_ID']);
		$track->addChild('Durum', $durum);
		$track->addChild('Sohbet', $offer['CHAT']);
	}
	$filePathOffer = __DIR__."/../files/offer_test.xml";
	$xmlOffer->asXML($filePathOffer);
?>
<a href="/positive/download2.php?file=<?php echo $filePath?>">XML poliçe raporu indir</a>
<a href="/positive/download2.php?file=<?php echo $filePathOffer?>">XML teklif raporu indir</a>
</body>
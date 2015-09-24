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
	
	$searchService = new SearchService();
	$policies = $searchService->getPoliciesInMonth(8, 2015);
	
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
	$filePath = __DIR__."/../files/test.xml";
	$xml->asXML($filePath);
?>
<a href="/positive/download2.php?file=<?php echo $filePath?>">XML dosyasını indir</a>
</body>
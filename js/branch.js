function writeToOfferRow(offer, username){
	$('#offer_id_'+offer['COMPANY_ID']).html(offer['ID']);
	$('#personel_id_'+offer['COMPANY_ID']).html(username);
	$('#prim_'+offer['COMPANY_ID']).val(offer['PRIM']);
	$('#komisyon_'+offer['COMPANY_ID']).val(offer['KOMISYON']);
	$('#prod_komisyon_'+offer['COMPANY_ID']).val(offer['PROD_KOMISYON']);
	$('#make_policy_'+offer['COMPANY_ID']).css("display", "block");
}

function navigateToPolicyRequest(companyId){
	var offer_id = $('#offer_id_'+companyId).html();
	location.href = '/positive/branch/policyRequest.php?offer_id='+offer_id;
}
function disableOfferRow(offer){
	$('#offer_id_'+offer['COMPANY_ID']).html(offer['ID']);
	$('#prim_'+offer['COMPANY_ID']).val(offer['PRIM']);
	$('#prim_'+offer['COMPANY_ID']).prop('readonly', true);
	$('#komisyon_'+offer['COMPANY_ID']).val(offer['KOMISYON']);
	$('#komisyon_'+offer['COMPANY_ID']).prop('readonly', true);
	$('#give_offer_'+offer['COMPANY_ID']).remove();
}

function giveOffer(talepNo, companyId, companyName, user_id){
	var prim = $('#prim_'+companyId).val();
	var komisyon = $('#komisyon_'+companyId).val();
	
	if(prim == null || prim.length == 0){
		alert("Lütfen prim bilgisini giriniz!");
		return;
	}
	if(komisyon == null || komisyon.length == 0){
		alert("Lütfen komisyon bilgisini giriniz!");
		return;
	}
	
	var message = companyName + " şirketi için\n";
	message += "Prim : "+prim+" TL\nKomisyon : "+komisyon+" TL\n";
	message += "Teklifi sisteme kaydedilecektir.Onaylıyor musunuz?";
	if(!confirm(message)){
		return;
	}
	
	var data = new FormData();
	data.append('user_id', user_id);
	data.append('talep_no', talepNo);
	data.append('company_id', companyId);
	data.append('prim', prim);
	data.append('komisyon', komisyon);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/add_offer.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	disableOfferRow(data);
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('add offer ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("add offer ajax call complete : " + textStatus);
        }
    });
}
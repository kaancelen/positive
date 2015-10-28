function disableOfferRow(offer){
	$('#offer_id_'+offer['COMPANY_ID']).html(offer['ID']);
	$('#prim_'+offer['COMPANY_ID']).val(offer['PRIM']);
	$('#prim_'+offer['COMPANY_ID']).prop('readonly', true);
	$('#komisyon_'+offer['COMPANY_ID']).val(offer['KOMISYON']);
	$('#prod_komisyon_'+offer['COMPANY_ID']).val(offer['PROD_KOMISYON']);
	$('#komisyon_'+offer['COMPANY_ID']).prop('readonly', true);
	$('#give_offer_'+offer['COMPANY_ID']).remove();
	$('#remove_offer_'+offer['COMPANY_ID']).css('display','');
}

function giveOffer(talepNo, companyId, companyName, user_id){
	var prim = $('#prim_'+companyId).val();
	var komisyon = $('#komisyon_'+companyId).val();
	var prod_komisyon = $('#prod_komisyon_'+companyId).val();
	var ust_komisyon = $('#ust_komisyon_'+companyId).val();
	var bagli_komisyon = $('#bagli_komisyon_'+companyId).val();
	
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
	data.append('prod_komisyon', prod_komisyon);
	data.append('ust_komisyon', ust_komisyon);
	data.append('bagli_komisyon', bagli_komisyon);
	
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

function removeOffer(talepNo, companyId, companyName){
	var offerId = $('#offer_id_'+companyId).html();//get offer id
	
	var r = confirm("["+companyName+"] için verilen ["+offerId+"] numaralı teklifi silmek istediğinize emin misiniz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('talep_no', talepNo);
	data.append('company_id', companyId);
	data.append('offer_id', offerId);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/remove_offer.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	console.log(data);
        	if(data){
        		location.reload();
        	}else{
        		alert("Teklif silinemedi, bir hata ile karşılaşıldı!");
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('add offer ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("add offer ajax call complete : " + textStatus);
        }
    });
}

function pullOfferPageControl(request_id){
	var data = new FormData();
	data.append('request_id', request_id);
	//in every 5 second make ajax request
	setInterval(function(){
		//make ajax request
	    $.ajax({
	        url: '/positive/ajax/pullOfferControl.php',
	        type: 'POST',
	        data: data,
	        cache: false,
	        dataType: 'json',
	        processData: false,
	        contentType: false,
	        success: function(data, textStatus, jqXHR){
	        	if(data){//new policy request has come
	        		location.reload();
	        	}
	        },
	        error: function(jqXHR, textStatus, errorThrown){
	            console.log('pullOfferPageControl ajax error : ' + textStatus);
	        },
	        complete: function(jqXHR, textStatus){
	            console.log("pullOfferPageControl ajax call complete : " + textStatus);
	        }
	    });
	}, 
	5000);
}
function requestSearch(){
	clearDatas();
	var request_id = $('#request_id').val();
	if(request_id == null || request_id.length == 0){
		$('#request-search-error').html("Talep numarasını boş bırakmayınız.");
		return;
	}else{
		$('#request-search-error').html("");
	}
	
	var data = new FormData();
	data.append('request_id', request_id);
	data.append('type', 1);
	search(data);
}

function offerSearch(){
	clearDatas();
	var offer_id = $('#offer_id').val();
	if(offer_id == null || offer_id.length == 0){
		$('#offer-search-error').html("Teklif numarasını boş bırakmayınız.");
		return;
	}else{
		$('#offer-search-error').html("");
	}
	var data = new FormData();
	data.append('offer_id', offer_id);
	data.append('type', 2);
	search(data);
}

function policySearch(){
	clearDatas();
	var policy_id = $('#policy_no').val();
	if(policy_id == null || policy_id.length == 0){
		$('#policy-search-error').html("Poliçe numarasını boş bırakmayınız.");
		return;
	}else{
		$('#policy-search-error').html("");
	}
	var data = new FormData();
	data.append('policy_id', policy_id);
	data.append('type', 3);
	search(data);
}

function extendSearch(){
	clearDatas();
	var plaka_no = $('#plaka_no').val();
	var tckn = $('#tckn').val();
	var vergi_no = $('#vergi_no').val();
	var belge_no = $('#belge_no').val();
	var asbis = $('#asbis').val();
	
	if((plaka_no == null || plaka_no.length == 0) &&
			(tckn == null || tckn.length == 0) &&
			(vergi_no == null || vergi_no.length == 0) &&
			(belge_no == null || belge_no.length == 0) &&
			(asbis == null || asbis.length == 0)){
		$('#extend-search-error').html("En az 1 alanı doldurmalısınız.");
		return;
	}else{
		$('#extend-search-error').html("");
	}
	var data = new FormData();
	data.append('plaka_no', plaka_no);
	data.append('tckn', tckn);
	data.append('vergi_no', vergi_no);
	data.append('belge_no', belge_no);
	data.append('asbis', asbis);
	data.append('type', 4);
	search(data);
}

function search(formData){
	//make ajax request
    $.ajax({
        url: '/positive/ajax/search.php',
        type: 'POST',
        data: formData,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	console.log(data);
        	if(data){
        		putDatas(data);
        	}else{
        		errorDatas();
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('search ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("search ajax call complete : " + textStatus);
        }
    });
}

function clearDatas(){
	$('#request_part').html("");
	$('#offer_part').html("");
	$('#policy_part').html("");
	$('#request-part-error').html("");
	$('#offer-part-error').html("");
	$('#policy-part-error').html("");
}

function errorDatas(){
	$('#request-part-error').html("Aradığınız kriterlerde kayıt bulunamadı.");
	$('#offer-part-error').html("Aradığınız kriterlerde kayıt bulunamadı.");
	$('#policy-part-error').html("Aradığınız kriterlerde kayıt bulunamadı.");
}

function putDatas(data){
	var request = data[1];
	if(request){
		var text = "";
		for(var i=0; i < request.length; i++){
			var requestObj = request[i];
			text += requestObj['BRANCH_NAME'] + " - ";
			text += "<a href='"+requestObj['LINK']+"' target='_blank'>Talep sayfası</a>";
			text += "<br>";
		}
		$('#request_part').html(text);
	}
	
	var offer = data[2];
	if(offer){
		var text = "";
		for(var i=0; i < offer.length; i++){
			var offerObj = offer[i];
			text += offerObj['BRANCH_NAME'] + " - " + offerObj['PERSONEL_NAME'] + " - ";
			text += "<a href='"+offerObj['LINK']+"' target='_blank'>Talep sayfası</a>";
			text += "<br>";
		}
		$('#offer_part').html(text);
	}
	
	var policy = data[3][0];
	if(policy){
		var text = "";
		for(var i=0; i < policy.length; i++){
			var policyObj = policy[i];
			text += policyObj['BRANCH_NAME'] + " - " + policyObj['PERSONEL_NAME'] + " - ";
			text += policyObj['POLICY_COMPLETE_PERSONEL'] + " - ";
			text += "<a href='"+policyObj['LINK']+"' target='_blank'>Poliçe sayfası</a>";
			text += "<br>";
		}
		$('#policy_part').html(text);
	}
}
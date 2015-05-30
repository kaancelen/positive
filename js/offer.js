function onCompanyAllChange(companies){
	var isChecked = $('#company_all').is(':checked');
	for(var i=0; i<companies.length; i++){
		var checkbox = $('#company_'+companies[i].ID);
		if(checkbox != null){
			checkbox.prop('checked', isChecked);
		}
	}
}

function radio_tckn(){
	$('#radio_vergi').prop('checked', false);
	$('#tckn').prop('readonly', false);
	$('#vergiNo').prop('readonly', true);
	$('#vergiNo').val("");
}
function radio_vergi(){
	$('#radio_tckn').prop('checked', false);
	$('#vergiNo').prop('readonly', false);
	$('#tckn').prop('readonly', true);
	$('#tckn').val("");
}

function radio_belge(){
	$('#radio_asbis').prop('checked', false);
	$('#belgeNo').prop('readonly', false);
	$('#asbis').prop('readonly', true);
	$('#asbis').val("");
}
function radio_asbis(){
	$('#radio_belge').prop('checked', false);
	$('#asbis').prop('readonly', false);
	$('#belgeNo').prop('readonly', true);
	$('#belgeNo').val("");
}

function validateOfferRequest(companies){
	var companySelected = false;
	for(var i=0; i<companies.length; i++){
		var checkbox = $('#company_'+companies[i].ID);
		if(checkbox != null && checkbox.is(':checked')){
			companySelected = true;
		}
	}
	if(!companySelected){
		$('#offer-request-company-error').html("En az bir sigorta şirketi seçiniz.");
	}else{
		$('#offer-request-company-error').html("");
	}
	
	var message = "";
	var plaka = $('#plaka').val();
	if(plaka == null || plaka.length == 0){
		message += "Plaka bilgisini boş bırakmayınız.<br>";
	}else if(!plaka.match("[0-9]{2} [A-Z]{1,3} [0-9]{1,4}$")){
		message += "Plaka formata uygun değildir[ör: '01 ABC 23'].<br>";
	}
	
	if($('#radio_tckn').is(':checked')){
		var tckn = $('#tckn').val();
		if(tckn == null || tckn.length == 0){
			message += "TC kimlik bilgisini boş bırakmayınız.<br>";
		}else if(tckn.length != 11){
			message += "TC kimlik bilgisi 11 haneli olmalıdır.<br>";
		}
	}
	
	if($('#radio_vergi').is(':checked')){
		var vergi = $('#vergiNo').val();
		if(vergi == null || vergi.length == 0){
			message += "Vergi no bilgisini boş bırakmayınız.<br>";
		}else if(vergi.length != 10){
			message += "Vergi no bilgisi 10 haneli olmalıdır.<br>";
		}
	}
	
	if($('#radio_belge').is(':checked')){
		var belge = $('#belgeNo').val();
		if(belge == null || belge.length == 0){
			message += "Belge no bilgisini boş bırakmayınız.<br>";
		}else if(belge.length != 10){
			message += "Belge no bilgisi 10 haneli olmalıdır.<br>";
		}
	}
	
	if($('#radio_asbis').is(':checked')){
		var asbis = $('#asbis').val();
		if(asbis == null || asbis.length == 0){
			message += "ASBİS bilgisini boş bırakmayınız.<br>";
		}else if(asbis.length != 19){
			message += "ASBİS bilgisi 19 haneli olmalıdır.<br>";
		}
	}
	
	$('#offer-request-error').html(message);
	if(message.length == 0){
		$('#offer-request-form').submit();
	}
}
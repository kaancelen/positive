function onCompanyAllChange(companies){
	var isChecked = $('#company_all').is(':checked');
	for(var i=0; i<companies.length; i++){
		var checkbox = $('#company_'+companies[i].ID);
		if(checkbox != null){
			checkbox.prop('checked', isChecked);
		}
	}
}
function on_radio_trafik_change(){
	if($('#radio_trafik').is(':checked')){
		$('#radio_kasko').prop('checked', false);
		$('#radio_kasko_trafik').prop('checked', false);
	}
}
function on_radio_kasko_change(){
	if($('#radio_kasko').is(':checked')){
		$('#radio_trafik').prop('checked', false);
		$('#radio_kasko_trafik').prop('checked', false);
	}
}
function on_radio_kasko_trafik_change(){
	if($('#radio_kasko_trafik').is(':checked')){
		$('#radio_trafik').prop('checked', false);
		$('#radio_kasko').prop('checked', false);
	}
}
function on_radio_tckn_change(){
	$('#radio_vergi').prop('checked', false);
	$('#tckn').prop('readonly', false);
	$('#vergiNo').prop('readonly', true);
	$('#vergiNo').val("");
}
function on_radio_vergi_change(){
	$('#radio_tckn').prop('checked', false);
	$('#vergiNo').prop('readonly', false);
	$('#tckn').prop('readonly', true);
	$('#tckn').val("");
}

function on_radio_belge_change(){
	$('#radio_asbis').prop('checked', false);
	$('#belgeNo').prop('readonly', false);
	$('#asbis').prop('readonly', true);
	$('#asbis').val("");
}
function on_radio_asbis_change(){
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
	
	var message = "";
	if(!companySelected){
		$('#offer-request-company-error').html("En az bir sigorta şirketi seçiniz.");
		message += "En az bir sigorta şirketi seçiniz."
	}else{
		$('#offer-request-company-error').html("");
	}
	
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
		}else if(!tckn.match("[0-9]{11}$")){
			message += "TC kimlik bilgisi 11 rakam olmalıdır.<br>";
		}
	}
	
	if($('#radio_vergi').is(':checked')){
		var vergi = $('#vergiNo').val();
		if(vergi == null || vergi.length == 0){
			message += "Vergi no bilgisini boş bırakmayınız.<br>";
		}else if(!vergi.match("[0-9]{10}$")){
			message += "Vergi no bilgisi 10 rakam olmalıdır.<br>";
		}
	}
	
	if($('#radio_belge').is(':checked')){
		var belge = $('#belgeNo').val();
		if(belge == null || belge.length == 0){
			message += "Belge no bilgisini boş bırakmayınız.<br>";
		}else if(!belge.match("[A-Z]{2}[0-9]{6}$")){
			message += "Belge no formata uygun değildir[ör: 'UC123456']<br>";
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
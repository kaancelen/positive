function validateAgentForm(){
	var accept_terms = $("#accept_terms").is(':checked');
	var executive = $('#executive').val();
	var address = $('#address').val();
	var phone = $('#phone').val();
	var fax = $('#fax').val();
	var gsm = $('#gsm').val();
	var email = $('#email').val();
	var iban = $('#iban').val();
	var bank = $('#bank').val();
	var agents = $('#agents').val();
	
	var success_flag = true;

	if(!accept_terms){
		$('#terms-error').html("Sözleşme şartlarını okumalı ve kabul etmelisiniz.");
		success_flag = false;
	}else{
		$('#terms-error').html("");
	}
	
	if(executive == null || executive.length == 0){
		$('#executive-error').html("Yetkili ismini boş bırakmayınız.");
		success_flag = false;
	}else if(executive.length > 128){
		$('#executive-error').html("Yetkili ismi en fazla 128 karakter olabilir.");
		success_flag = false;
	}else{
		$('#executive-error').html("");
	}
	
	if(address == null || address.length == 0){
		$('#address-error').html("Adresi boş bırakmayınız.");
		success_flag = false;
	}else if(address.length > 1024){
		$('#address-error').html("Adres en fazla 1024 karakter olabilir.");
		success_flag = false;
	}else{
		$('#address-error').html("");
		address = address.replace(/(\r\n|\n|\r)/gm," ");
		address = address.replace(/['"]/g,"");
	}
	
	if(phone == null || phone.length == 0){
		$('#phone-error').html("Sabit telefonu boş bırakmayınız.");
		success_flag = false;
	}else if(phone.length != 11){
		$('#phone-error').html("Lütfen sabit telefon numaranızı başında 0 ile 11 karakter olarak giriniz.");
		success_flag = false;
	}else{
		$('#phone-error').html("");
	}
	
	if(fax == null || fax.length == 0){
		$('#fax-error').html("Fax numaranızı boş bırakmayınız.");
		success_flag = false;
	}else if(fax.length != 11){
		$('#fax-error').html("Lütfen Fax numaranızı başında 0 ile 11 karakter olarak giriniz.");
		success_flag = false;
	}else{
		$('#fax-error').html("");
	}
	
	if(gsm == null || gsm.length == 0){
		$('#gsm-error').html("Cep telefonu numaranızı boş bırakmayınız.");
		success_flag = false;
	}else if(gsm.length != 11){
		$('#gsm-error').html("Lütfen Cep telefonu numaranızı başında 05 ile 11 karakter olarak giriniz.");
		success_flag = false;
	}else{
		$('#gsm-error').html("");
	}
	
	if(email == null || email.length == 0){
		$('#email-error').html("E-postanızı boş bırakmayınız.");
		success_flag = false;
	}else if(email.length > 128){
		$('#email-error').html("E-posta en fazla 128 karakter olabilir.");
		success_flag = false;
	}else if(!validateEmail(email)){
		$('#email-error').html("Lütfen E-posta adresinizi geçerli formatta giriniz.ör['abc@xyz.com']");
		success_flag = false;
	}else{
		$('#email-error').html("");
	}
		
	if(iban == null || iban.length == 0){
		$('#iban-error').html("IBAN numaranızı boş bırakmayınız.");
		success_flag = false;
	}else if(!IBAN.isValid(iban)){
		$('#iban-error').html("Lütfen geçerli formatta bir iban numarası giriniz.");
		success_flag = false;
	}else{
		$('#iban-error').html("");
	}
	
	if(bank == null || bank.length == 0){
		$('#bank-error').html("Banka adını boş bırakmayınız.");
		success_flag = false;
	}else if(bank.length > 128){
		$('#bank-error').html("Banka adı en fazla 128 karakter olabilir.");
		success_flag = false;
	}else{
		$('#bank-error').html("");
	}
	
	if(agents == null || agents.length == 0){
		$('#agents-error').html("Acentelikleri boş bırakmayınız.");
		success_flag = false;
	}else if(agents.length > 2048){
		$('#agents-error').html("Acentelikler en fazla 2048 karakter olabilir.");
		success_flag = false;
	}else{
		$('#agents-error').html("");
		agents = agents.replace(/(\r\n|\n|\r)/gm," ");
		agents = agents.replace(/['"]/g,"");
	}
	
	if(success_flag){
		$('#agent_detail').submit();
	}
	
}
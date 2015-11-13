function validateCompanyForm(){
	var durum = $('#durum').val();
	var name = $('#name').val();
	var ic_dis = $('#ic_dis').val();
	var uretim_kanali = $('#uretim_kanali').val();
	
	var message = "";
	
	if(durum == null || durum.length == 0){
		message += "Lütfen Aktif/Pasif bilgisini seçiniz.<br>";
	}
	
	if(name == null || name.length == 0){
		message += "Lütfen şirket adını boş bırakmayınız.<br>";
	}else if(name.length > 128){
		message += "Şirket adı en çok 128 karakter olabilir.<br>";
	}
	
	if(ic_dis == null || ic_dis.length == 0){
		message += "Lütfen İç/Dış bilgisini seçiniz.<br>";
	}
	
	if(uretim_kanali == null || uretim_kanali.length == 0){
		message += "Lütfen Üretim kanalı bilgisini boş bırakmayınız.<br>";
	}else if(uretim_kanali.length > 16){
		message += "Üretim kanalı bilgisi en çok 16 karakter olabilir.<br>";
	}
	
	$('#company-error').html(message);
	if(message == null || message.length == 0){
		var company_form = $('#company_form');
		company_form.submit();
	}
}
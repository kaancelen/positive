function validateOfferRequestChange(type, request_id){
	
	var message = "";
	
	if(type == 'Diger'){
		var description = $('#description').val();
		if(description == null || description.length == 0){
			message += "Lütfen poliçe bilgilerini boş bırakmayınız.<br>";
		}else if(description.length > 2048){
			message += "Poliçe bilgileri en fazla 2048 karakter olabilir.<br>";
		}
	}else{
		var plaka = $('#plaka').val();
		plaka = plaka.trim();
		if(plaka == null || plaka.length == 0){
			message += "Plaka bilgisini boş bırakmayınız.<br>";
		}else if(!plaka.match("[0-9]{2}[A-Za-z]{1,3}[0-9]{1,4}$")){
			message += "Plaka formata uygun değildir[ör: '01 ABC 23'].<br>";
		}
		
		var tckn = $('#tckn').val();
		if(!$('#tckn').is('[readonly]')){
			if(tckn == null || tckn.length == 0){
				message += "TC kimlik bilgisini boş bırakmayınız.<br>";
			}else if(!tckn.match("[0-9]{11}$")){
				message += "TC kimlik bilgisi 11 rakam olmalıdır.<br>";
			}
		}
		
		var vergi = $('#vergiNo').val();
		if(!$('#vergiNo').is('[readonly]')){
			if(vergi == null || vergi.length == 0){
				message += "Vergi no bilgisini boş bırakmayınız.<br>";
			}else if(!vergi.match("[0-9]{10}$")){
				message += "Vergi no bilgisi 10 rakam olmalıdır.<br>";
			}
		}
		
		var belge = $('#belgeNo').val();
		if(!$('#belgeNo').is('[readonly]')){
			if(belge == null || belge.length == 0){
				message += "Belge no bilgisini boş bırakmayınız.<br>";
			}else if(!belge.match("[A-Z]{2}[0-9]{6}$")){
				message += "Belge no formata uygun değildir[ör: 'UC123456']<br>";
			}
		}
		
		var asbis = $('#asbis').val();
		if(!$('#asbis').is('[readonly]')){
			if(asbis == null || asbis.length == 0){
				message += "ASBİS bilgisini boş bırakmayınız.<br>";
			}else if(asbis.length != 19){
				message += "ASBİS bilgisi 19 haneli olmalıdır.<br>";
			}
		}
		
		var marka_kodu = $('#marka_kodu').val();
		if(marka_kodu != null && marka_kodu.length > 10){
			message += "Marka kodu en fazla 10 haneli olabilir.<br>";
		}
	
		var description = $('#description').val();
		if(description != null && description.length > 2048){
			message += "Poliçe bilgileri en fazla 2048 karakter olabilir.<br>";
		}
	}
	
	$('#offer-request-error').html(message);
	if(message.length == 0){
		var data = new FormData();
		data.append('type', type);
		data.append('request_id', request_id);
		if(type != 'Diger'){
			data.append('plaka', plaka);
			data.append('tckn', tckn);
			data.append('vergi', vergi);
			data.append('belge', belge);
			data.append('asbis', asbis);
			data.append('marka_kodu', marka_kodu);
		}
		data.append('description', description);
		
		//make ajax request
	    $.ajax({
	        url: '/positive/ajax/offerRequestChange.php',
	        type: 'POST',
	        data: data,
	        cache: false,
	        dataType: 'json',
	        processData: false,
	        contentType: false,
	        success: function(data, textStatus, jqXHR){
	        	if(data){
					location.reload();
				}else{
					alert("Talep bilgisi değiştirilemedi! Bir hata ile karşılaşıldı.");
				}
	        },
	        error: function(jqXHR, textStatus, errorThrown){
	            console.log('offer request change model ajax error : ' + textStatus);
	        },
	        complete: function(jqXHR, textStatus){
	            console.log("offer request change model ajax call complete : " + textStatus);
	        }
	    });
	}
}
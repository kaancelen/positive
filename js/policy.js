function validatePolicyRequest(){
	
	var name = $('#name').val();
	var card = $('#card').val();
	var cvc = $('#cvc').val();
	var failFlag = false;
	
	if(name == null || name.length == 0){
		$('#card-name-error').html("Lütfen kart üzerindeki ismi boş bırakmayınız.");
		failFlag = true;
	}else if(name.length > 255){
		$('#card-name-error').html("Kart üzerindeki isim en fazla 255 karakter olabilir.");
		failFlag = true;
	}else{
		$('#card-name-error').html("");
	}
	
	if(card == null || card.length == 0){
		$('#card-no-error').html("Lütfen kart numarasını boş bırakmayınız.");
		failFlag = true;
	}else {
		card = card.trim();
		if(card.length != 16 || !card.match(/^[0-9]+$/)){
			$('#card-no-error').html("Lütfen Geçerli bir kart numarası giriniz.");
			failFlag = true;
		}else{
			$('#card-no-error').html("");
		}
	}
	
	if(cvc == null || cvc.length == 0){
		$('#cvc-error').html("Lütfen cvc kodunu boş bırakmayınız.");
		failFlag = true;
	}else {
		cvc = cvc.trim();
		if(cvc.length != 3 || !cvc.match(/^[0-9]+$/)){
			$('#cvc-error').html("Geçerli bir cvc kodu giriniz.Cvc kodu kartın arkasında 3 haneli bir sayıdır.");
			failFlag = true;
		}else{
			$('#cvc-error').html("");
		}
	}
	
	if(!failFlag){
		var form = $('#policy-request-form');
		form.submit();
	}
}
/**
 * 
 */
function editCardInfo(card_id){
	var name = $('#name').val();
	var card = $('#card').val();
	var cvc = $('#cvc').val();
	var expire_date = $('#expireMonth').val() + "/" + $('#expireYear').val();
	var failFlag = false;
	
	if(name == null || name.length == 0){
		$('#card-name-error').html("Lütfen kart üzerindeki ismi boş bırakmayınız.");
		failFlag = true;
	}else if(name.length > 255){
		$('#card-name-error').html("Kart üzerindeki isim en fazla 255 karakter olabilir.");
		failFlag = true;
	}else{
		$('#card-name-error').html("");
	}
	
	if(card == null || card.length == 0){
		$('#card-no-error').html("Lütfen kart numarasını boş bırakmayınız.");
		failFlag = true;
	}else {
		var tempCardNo = card.trim();
		if(tempCardNo.length != 16 || !tempCardNo.match(/^[0-9]+$/)){
			$('#card-no-error').html("Lütfen Geçerli bir kart numarası giriniz.");
			failFlag = true;
		}else{
			$('#card-no-error').html("");
		}
	}
	
	if(cvc == null || cvc.length == 0){
		$('#cvc-error').html("Lütfen cvc kodunu boş bırakmayınız.");
		failFlag = true;
	}else {
		cvc = cvc.trim();
		if(cvc.length != 3 || !cvc.match(/^[0-9]+$/)){
			$('#cvc-error').html("Geçerli bir cvc kodu giriniz.Cvc kodu kartın arkasında 3 haneli bir sayıdır.");
			failFlag = true;
		}else{
			$('#cvc-error').html("");
		}
	}
	
	if(!failFlag){
		var r = confirm("Kredi kartı bilgilerini değiştirmek istediğinizden emin misiniz?");
		if(!r){
			return;
		}
		
		var data = new FormData();
		data.append('card_id', card_id);
		data.append('card_name', name);
		data.append('card_no', card);
		data.append('expire_date', expire_date);
		data.append('cvc', cvc);
		
		//make ajax request
	    $.ajax({
	        url: '/positive/ajax/edit_card.php',
	        type: 'POST',
	        data: data,
	        cache: false,
	        dataType: 'json',
	        processData: false,
	        contentType: false,
	        success: function(data, textStatus, jqXHR){
	        	alert("Kart bilgileri başarıyla değiştirildi.");
	        },
	        error: function(jqXHR, textStatus, errorThrown){
	            console.log('edit card ajax error : ' + textStatus);
	        },
	        complete: function(jqXHR, textStatus){
	            console.log("edit card ajax call complete : " + textStatus);
	        }
	    });
	}
}
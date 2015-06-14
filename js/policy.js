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
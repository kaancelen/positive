var max_file_size_byte = 10000000;//10MB
var max_file_size_mb = max_file_size_byte / 1000000;
var file_type = "application/pdf";

function validatePolicy(){
	var policyFile = $('#policyFile');
	var makbuzFile = $('#makbuzFile');
	var failFlag = false;
	
	//policy check
	if(policyFile[0].files[0] == null){
		$('#policy-file-error').html("Lütfen poliçe dosyasını seçiniz!");
		failFlag = true;
	}else if(policyFile[0].files[0].size > max_file_size_byte){
		$('#policy-file-error').html("Poliçe dosyası en fazla "+max_file_size_mb+"MB olabilir.");
		failFlag = true;
	}else if(policyFile[0].files[0].type != file_type){
		$('#policy-file-error').html("Poliçe, pdf dosyası olmalıdır.");
		failFlag = true;
	}else{
		$('#policy-file-error').html("");
	}
	//makbuz check
	if(makbuzFile[0].files[0] == null){
		$('#makbuz-file-error').html("Lütfen makbuz dosyasını seçiniz!");
		failFlag = true;
	}else if(makbuzFile[0].files[0].size > max_file_size_byte){
		$('#makbuz-file-error').html("Makbuz dosyası en fazla "+max_file_size_mb+"MB olabilir.");
		failFlag = true;
	}else if(makbuzFile[0].files[0].type != file_type){
		$('#makbuz-file-error').html("Makbuz, pdf dosyası olmalıdır.");
		failFlag = true;
	}else{
		$('#makbuz-file-error').html("");
	}
	//submit
	if(!failFlag){
		var form = $('#policy_complete_form');
		form.submit();
	}
}
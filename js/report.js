var max_file_size_byte = 3000000;//3MB
var max_file_size_mb = max_file_size_byte / 1000000;
var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];  

function validateReportForm(){
	var ss1 = $('#ss1');
	var ss2 = $('#ss2');
	var subject = $('#subject').val();
	var content = $('#content').val();
	var submit_flag = true;
	
	if(subject == null || subject.length == 0){
		$('#subject-error').html("Lütfen konuyu boş bırakmayınız.");
		submit_flag = false;
	}else if(subject.length > 64){
		$('#subject-error').html("Konu en fazla 64 karakter olabilir.");
		submit_flag = false;
	}else{
		$('#subject-error').html("");
	}
	
	if(content == null || content.length == 0){
		$('#content-error').html("Lütfen açıklamayı boş bırakmayınız");
		submit_flag = false;
	}else if(content.length > 4000){
		$('#content-error').html("Açıklama en fazla 4000 karakter olabilir.");
		submit_flag = false;
	}else{
		$('#content-error').html("");
	}
	
	if(ss1[0].files[0]){
		if(ss1[0].files[0].size > max_file_size_byte){
			$('#ss1-error').html("Ekran görüntüsü en fazla "+max_file_size_mb+"MB olabilir.");
			submit_flag = false;
		}else if(!ValidateSingleInput(ss1[0])){
			$('#ss1-error').html("Seçilen dosya .jpg,.jpeg,.bmp,.gif,.png uzantılı olmalıdır!");
			submit_flag = false;
		}else{
			$('#ss1-error').html("");
		}
	}
	
	if(ss2[0].files[0]){
		if(ss2[0].files[0].size > max_file_size_byte){
			$('#ss1-error').html("Ekran görüntüsü en fazla "+max_file_size_mb+"MB olabilir.");
			submit_flag = false;
		}else if(!ValidateSingleInput(ss2[0])){
			$('#ss1-error').html("Seçilen dosya .jpg,.jpeg,.bmp,.gif,.png uzantılı olmalıdır!");
			submit_flag = false;
		}else{
			$('#ss1-error').html("");
		}
	}
	
	if(submit_flag){
		var form = $('#positive-report');
		form.submit();
	}
}

function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
             
            if (!blnValid) {
                return false;
            }
        }
    }
    return true;
}
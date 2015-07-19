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

function removeReport(report_id){
	var r = confirm(report_id + " numarali hata bildiriminiz silmek istediğinize emin misiniz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('report_id', report_id);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/remove_report.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	if(data){
        		$('#report_'+report_id).remove();
        		alert("Kayıt başarıyla silindi.");
        	}else{
        		alert("Kayıt silinemedi, bir hata oluştu!");
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('removeReport ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("removeReport ajax call complete : " + textStatus);
        }
    });
}

function on_statu0_change(){
	if($('#statu0').is(':checked')){
		$('#statu1').prop('checked', false);
		$('#statu2').prop('checked', false);
	}
}
function on_statu1_change(){
	if($('#statu1').is(':checked')){
		$('#statu0').prop('checked', false);
		$('#statu2').prop('checked', false);
	}
}
function on_statu2_change(){
	if($('#statu2').is(':checked')){
		$('#statu0').prop('checked', false);
		$('#statu1').prop('checked', false);
	}
}

function validateFeedback(){
	var feedback = $('#feedback').val();
	
	if(feedback == null || feedback.length == 0){
		$('#feedback-error').html("Lütfen feedback'i boş bırakmayınız!");
	}else if(feedback.length > 1024){
		$('#feedback-error').html("Feedback en fazla 1024 karakter olabilir.");
	}else{
		$('#feedback-error').html("");
		var form = $('#feedback-form');
		form.submit();
	}
}

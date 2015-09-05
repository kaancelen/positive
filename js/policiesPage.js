function policyTabChange(type){
	if(type == 0){
		$('#policy_tabs_iptal').removeClass("active");
		$('#policy_tabs_uretim').addClass("active");
		
		$('#cancel_req_table').css('display', 'none');
		$('#policy_req_table').css('display', '');
	}else if(type == 1){
		$('#policy_tabs_uretim').removeClass("active");
		$('#policy_tabs_iptal').addClass("active");
		
		$('#policy_req_table').css('display', 'none');
		$('#cancel_req_table').css('display', '');
	}
}

function cancelRequestOperation(cancel_id, status){
	var confirmText = "";
	if(status == 1){
		confirmText = "ONAYLAYACAKSINIZ";
	}else if(status == 2){
		confirmText = "REDDEDECEKSİNİZ";
	}
	var r = confirm("Bu poliçe iptal isteğini "+confirmText+". Emin misiniz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('cancel_id', cancel_id);
	data.append('status', status);
	//make ajax request
	$.ajax({
		url: '/positive/ajax/cancel_request.php',
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false,
		contentType: false,
		success: function(data, textStatus, jqXHR){
			location.reload();
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log('close request ajax error : ' + textStatus);
		},
		complete: function(jqXHR, textStatus){
			console.log("close request ajax call complete : " + textStatus);
		}
	});
}
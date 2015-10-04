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
			console.log(data);
			if(data){
				location.reload();
			}else{
				alert("Bir hata ile karşılaşıldı!");
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log('close request ajax error : ' + textStatus);
		},
		complete: function(jqXHR, textStatus){
			console.log("close request ajax call complete : " + textStatus);
		}
	});
}

function refreshTime(personel){
	var month = $('#month').val();
	var year = $('#year').val();
	
	if(personel){
		location.href = "/positive/personel/policyCancels.php?month="+month+"&year="+year;
	}else{
		location.href = "/positive/branch/policyCancels.php?month="+month+"&year="+year;
	}
}

function refreshTimePolicy(personel){
	var month = $('#month').val();
	var year = $('#year').val();
	
	if(personel){
		location.href = "/positive/personel/policies.php?month="+month+"&year="+year;
	}else{
		location.href = "/positive/branch/policies.php?month="+month+"&year="+year;
	}
}
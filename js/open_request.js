function openRequest(request_id){
	var r = confirm("["+request_id+"] numaralı talep yeniden açılacaktır. Onaylıyor musunuz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('request_id', request_id);
	//make ajax request
	$.ajax({
		url: '/positive/ajax/open_request.php',
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
				alert("Talep yeniden açılamadı, bir hata ile karşılaşıldı!");
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
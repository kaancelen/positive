function closeRequest(type,request_id){
	var r = confirm("Bu "+(type==2?"talep":"poliçe isteği")+" kapatılacaktır. Onaylıyor musunuz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('request_id', request_id);
	data.append('type', type);
	//make ajax request
	$.ajax({
		url: '/positive/ajax/close_request.php',
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false,
		contentType: false,
		success: function(data, textStatus, jqXHR){
			console.log(data);
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
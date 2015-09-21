function onChangeAgentInfo(request_id){
	var agentId = $('#new_agent').val();
	var agentText = $('#new_agent option:selected').text();
	
	var r = confirm("Bu poliçenin acentasını "+agentText+" olarak değiştirmek istediğinize emin misiniz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('request_id', request_id);
	data.append('new_user_id', agentId);
	//make ajax request
	$.ajax({
		url: '/positive/ajax/change_policy_agent.php',
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
				alert("Acenta bilgisi değiştirilemedi! Bir hata ile karşılaşıldı.");
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log('policy detail ajax error : ' + textStatus);
		},
		complete: function(jqXHR, textStatus){
			console.log("policy detail ajax call complete : " + textStatus);
		}
	});
}
function edit_user(user_id){
	alert("edit "+user_id);
}

function remove_user(username, user_id){
	var r = confirm(username + " kullanıcısını silmek istediğinize emin misiniz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('user_id', user_id);
	
	//make ajax request
    $.ajax({
        url: '../ajax/remove_user.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	if(data){
        		alert(username + " silindi");
            	$('#user_'+user_id).remove();
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('remove user ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("remove user ajax call complete : " + textStatus);
        }
    });
}
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
        	var success_label = $('#user_table_success');
        	var error_label = $('#user_table_error');
        	if(data){
            	$('#user_'+user_id).remove();
            	success_label.html(username + " başarı ile silindi.");
            	success_label.css("visibility", "visible");
            	error_label.html("");
            	error_label.css("visibility", "hidden");
        	}else{
        		error_label.html(username + " kullanıcı silinirken bir hata oluştu, işlem başarısız.");
        		error_label.css("visibility", "visible");
        		success_label.html("");
        		success_label.css("visibility", "hidden");
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

function showFlash(message){
	var success_label = $('#user_table_success');
	success_label.html(message);
	success_label.css("visibility", "visible");
	
}
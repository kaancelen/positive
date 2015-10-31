function toggle_user(username, user_id, makeActive){
	var r = confirm(username + " kullanıcısını "+(makeActive==0?"aktifleştirmek":"pasifleştirmek")+" istediğinize emin misiniz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('user_id', user_id);
	data.append('make_active', makeActive);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/remove_user.php',
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
        		if(makeActive==0){
        			success_label.html(username + " başarı ile aktifleştirildi.");
        			$('#user_'+user_id).removeClass('row-offer-cancelled');
        			$('#make_user_active_'+user_id).css('visibility', 'hidden');
        			$('#make_user_passive_'+user_id).css('visibility', '');
        		}else{
        			success_label.html(username + " başarı ile pasifleştirildi.");
        			$('#user_'+user_id).addClass('row-offer-cancelled');
        			$('#make_user_active_'+user_id).css('visibility', '');
        			$('#make_user_passive_'+user_id).css('visibility', 'hidden');
        		}
            	success_label.css("visibility", "visible");
            	error_label.html("");
            	error_label.css("visibility", "hidden");
        	}else{
        		error_label.html(username + " kullanıcı durumu değiştirilirken bir hata oluştu, işlem başarısız.");
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
function resetPassword(user_id, username){
	var r = confirm(username + " kullanıcısının şifresini sıfırlamak istediğinize emin misiniz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('user_id', user_id);
	data.append('username', username);
	
	//make ajax request
    $.ajax({
        url: '../ajax/reset_password.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	var error_label = $('#user_form_error');
        	var success_label = $('#user_form_success');
        	if(data){
            	success_label.html(username + " kullanıcısının şifresi sıfırlandı.");
            	success_label.css("visibility", "visible");
            	error_label.html("");
            	error_label.css("visibility", "hidden");
        	}else{
        		error_label.html(username + " şifre sıfırlanırken hata oluştu, işlem başarısız.");
        		error_label.css("visibility", "visible");
        		success_label.html("");
        		success_label.css("visibility", "hidden");
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('reset password ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("reset password ajax call complete : " + textStatus);
        }
    });
}

function fillUserForm(selected_user){
	$('#user_id').val(selected_user['ID']);
	$('#username').val(selected_user['CODE']);
	$('#name').val(selected_user['NAME']);
	$('#description').val(selected_user['DESCRIPTION']);
	$('#select_role').val(selected_user['ROLE']);
}

function validateUserForm(){
	var username = $('#username').val();
	var name = $('#name').val();
	var role = $('#select_role').val();
	var desc = $('#description').val();
	
	var message = "";
	
	if(username == null || username.length == 0){
		message += "Lütfen kullanıcı adını boş bırakmayınız.<br>";
	}else if(username.length < 5 || username.length > 16){
		message += "Kullanıcı adı en az 5, en çok 16 karakter olabilir.<br>";
	}
	
	if(name == null || name.length == 0){
		message += "Lütfen ismi boş bırakmayınız.<br>";
	}else if(name.length < 5 || name.length > 127){
		message += "İsim en az 5, en çok 127 karakter olabilir.<br>";
	}
	
	if(role == 0){
		message += "Lütfen bir rol seçiniz.<br>";
	}
	
	if(desc != null && desc.length > 2048){
		message += "Ek bilgi en çok 2048 karakter olabilir.<br>";
	}
	
	$('#user-error').html(message);
	if(message == null || message.length == 0){
		var user_form = $('#positive_user');
		user_form.submit();
	}
}

function showPostMessage(post_flag, post_message){
	var error_label = $('#user_form_error');
	var success_label = $('#user_form_success');
	
	if(post_flag){
		success_label.html(post_message);
		success_label.css("visibility", "visible");
    	error_label.html("");
    	error_label.css("visibility", "hidden");
	}else{
		error_label.html(post_message);
		error_label.css("visibility", "visible");
		success_label.html("");
		success_label.css("visibility", "hidden");
	}
}

function onChangeUserRole(){
	var value = document.getElementById("select_role").value;
	if(value == 3){
		document.getElementById("komisyon_div").style.visibility = "visible";
		document.getElementById("master_agent_div").style.visibility = "visible";
	}else{
		document.getElementById("komisyon_div").style.visibility = "hidden";
		document.getElementById("master_agent_div").style.visibility = "hidden";
	}
}

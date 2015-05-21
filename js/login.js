$('#login_button').on("click", function(){
	var username = $('#username').val();
	var password = $('#password').val();
	
	//Check username and password
	var error_msg = "";
	if(username == null || !(username.length > 0)){
		error_msg += "Lütfen Kullanıcı Adını boş bırakmayınız<br>";
	}
	if(password == null || !(password.length > 0)){
		error_msg += "Lütfen Şifreyi boş bırakmayınız<br>";
	}
	if(error_msg.length > 0){
		$('#login-error').html(error_msg);
		return;
	}else{
		$('#login-error').html();
	}
	
	var form = $('#positive_login');
	form.submit();
});
var max_chat_file_size = 5000000;//5mb
var max_chat_file_size_mb = max_chat_file_size / 1000000;
    
function validateChatInput(){
	//Upload file if exist
    var chat_file = $('#chat_file');
    if(chat_file[0].files[0]){
    	if(chat_file[0].files[0].size > max_chat_file_size){
			$('#chat_error').html("Gönderilecek dosya en fazla "+max_chat_file_size_mb+"MB olabilir.");
			return;
		}
    	var request_id = $('#chat_request_id').val();
    	var files = chat_file[0].files;
    	
    	var data = new FormData();
    	data.append('request_id', request_id);
    	$.each(files, function name(key, value) {
			data.append(key, value);
		});
    	//make ajax request
    	$.ajax({
    		url: '/positive/ajax/add_chat_entry_file.php?files',
    		type: 'POST',
    		data: data,
    		cache: false,
    		dataType: 'json',
    		processData: false,
    		contentType: false,
    		success: function(data, textStatus, jqXHR){
    			if(data){
    				$('#chat_file').val(null);//reset file input
            		insertEntry(data['text'], data['user_name'], 'Şimdi');
            	}else{
            		$('#chat_error').html('Dosya gönderilemedi.');
            	}
    		},
    		error: function(jqXHR, textStatus, errorThrown){
    			console.log('add chat file ajax error : ' + textStatus);
    			$('#chat_error').html('Dosya gönderilemedi.');
    		},
    		complete: function(jqXHR, textStatus){
    			console.log("add chat file ajax call complete : " + textStatus);
    		}
    	});
    }
    
    //send message
	var text = $('#chat_input').val();
	var text = text.replace(/(\r\n|\n|\r)/gm,"");
	if(text == null || text.length == 0 || text.trim().length == 0){
		$('#chat_error').html('Boş mesaj gönderemezsiniz.');
		return;
	}else if(text.length > 1024){
		$('#chat_error').html('En fazla 1024 karakter gönderebilirsiniz.');
		return;
	}else{
		$('#chat_error').html('');
	}
	
	var request_id = $('#chat_request_id').val();
	
	var data = new FormData();
	data.append('request_id', request_id);
	data.append('text', text);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/add_chat_entry.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	if(data){
        		$('#chat_input').val('');
        		insertEntry(text, data['user_name'], 'Şimdi');
        	}else{
        		$('#chat_error').html('Mesaj gönderilemedi.');
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('add chat entry ajax error : ' + textStatus);
            $('#chat_error').html('Mesaj gönderilemedi.');
        },
        complete: function(jqXHR, textStatus){
            console.log("add chat entry ajax call complete : " + textStatus);
        }
    });
}

function insertEntry(text, username, timetext){
	var newline = '<li>(<i>'+timetext+'</i>)<b>'+username+'</b> : '+text+'</li>';
	$('.chatline').append(newline);
}

function pullChat(request_id){
	var data = new FormData();
	data.append('request_id', request_id);
	//in every 5 second make ajax request
	setInterval(function(){
		//make ajax request
	    $.ajax({
	        url: '/positive/ajax/get_chat.php',
	        type: 'POST',
	        data: data,
	        cache: false,
	        dataType: 'json',
	        processData: false,
	        contentType: false,
	        success: function(data, textStatus, jqXHR){
	        	$('.chatline').html('');//clear chat box
	    		for(var i=0; i< data.length; i++){
	    			insertEntry(data[i]['TEXT'], data[i]['USER_NAME'], data[i]['CREATION_DATE'].substring(11,16));
	    		}
	    		$('.chatline').scrollTop($('.chatline')[0].scrollHeight);
	        },
	        error: function(jqXHR, textStatus, errorThrown){
	            console.log('pull chat entries ajax error : ' + textStatus);
	        },
	        complete: function(jqXHR, textStatus){
	            console.log("pull chat entries ajax call complete : " + textStatus);
	        }
	    });
	}, 
	5000);
}
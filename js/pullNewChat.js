/**
 * @param request_id_string, list of request ids
 * @param page_type, page type 0 => talep ekranı, 1 => poliçe istek ekranı, 2 => iptal ekranı
 */
function pullNewChatEntries(request_id_string, page_type){
	
	if(request_id_string == null || request_id_string.length == 0){
		return;
	}
	
	var data = new FormData();
	data.append('request_id_string', request_id_string);
	data.append('page_type', page_type);
	
	setInterval(function(){
		//make ajax request
	    $.ajax({
	        url: '/positive/ajax/pullNewChatEntries.php',
	        type: 'POST',
	        data: data,
	        cache: false,
	        dataType: 'json',
	        processData: false,
	        contentType: false,
	        success: function(data, textStatus, jqXHR){
	        	if(data && data.length > 0){
	        		for(var i=0; i < data.length; i++){
	        			notifyEntry(data[i]['REQUEST_ID']);
	        		}
	        	}
	        },
	        error: function(jqXHR, textStatus, errorThrown){
	            console.log('pullNewChatEntries ajax error : ' + textStatus);
	        },
	        complete: function(jqXHR, textStatus){
	            console.log("pullNewChatEntries ajax call complete : " + textStatus);
	        }
	    });
	},
	20000);
}

function notifyEntry(request_id){
	var row = $('#request_'+request_id);
	if(row){
		row.find('#mail_gif').attr('src', '/positive/images/mail.gif');
	}
}
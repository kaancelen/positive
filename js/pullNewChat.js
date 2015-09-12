function pullNewChatEntries(){
	
	var data = new FormData();
	data.append('type', 0);
	
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
	3000);//TODO 10000
}

function notifyEntry(request_id){
	var row = $('#request_'+request_id);
	if(row){
		row.addClass('new-message');
	}
}
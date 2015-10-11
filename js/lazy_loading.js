var request_number = 30;

function getOtherRequests(){
	$('#loading_gif').css('visibility', 'visible');
	$('#get_others_link').css('visibility', 'hidden');
	cookie_companies = getCookie('companies');
	
	var data = new FormData();
	data.append('request_number', request_number);
	data.append('cookie_companies', cookie_companies);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/getOtherRequests.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data, textStatus, jqXHR){
        	$('#loading_gif').css('visibility', 'hidden');
        	$('#get_others_link').css('visibility', 'visible');
        	if(data){
        		if(data.length < 30){
        			$('#get_others_link').css('visibility', 'hidden');
        			$('#request_finished').css('visibility', 'visible');
        		}
        		request_number += 30;
        		console.log(data);
        		addOtherRequestsToTable(data);
        	}else{
        		alert("Talepleri yüklerken bir hata ile karşılaşıldı.");
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('offerPolicyPolling ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("offerPolicyPolling ajax call complete : " + textStatus);
        }
    });
}

function addOtherRequestsToTable(data){
	for(var i=0; i<data.length; i++){
		var new_row = data[i];
		addRowToTable(new_row);
	}
}

function addRowToTable(new_row){
	class_data = "row-offer-nothing";
	if(new_row['STATUS'] == 2){
		class_data = "row-offer-cancelled";
	}else if(new_row['WAITING_OFFER_NUM'] == 0){
		class_data = "row-offer-completed";
	}
	
	data_string = "<tr class='"+class_data+"'>";
	data_string += "<td id='request_'"+new_row['ID']+"><img id='mail_gif' width='24'><img id='look_gif' width='24'></td>";
	data_string += "<td><b id='req_id'>"+new_row['ID']+"</b></td>";
	data_string += "<td id='ratio'>"+new_row['OFFER_RATIO']+"</td>";
	data_string += "<td>"+new_row['BRANCH_NAME']+"</td>";
	data_string += "<td>"+new_row['POLICY_TYPE']+"</td>";
	data_string += "<td id='date'>"+new_row['CREATION_DATE']+"</td>";
	data_string += "<td>"+new_row['PLAKA']+"</td>";
	data_string += "<td>";
	data_string += "<button id='remove_user' type='button' class='btn btn-default btn-sm' aria-label='Left Align'";
	data_string += "onclick='location.href = '/positive/personel/offer.php?request_id="+new_row['ID']+"'>";
	data_string += "<span class='glyphicon glyphicon-open-file' aria-hidden='true'></span>";
	data_string += "</button>";
	data_string += "</td>";
	data_string += "</tr>";
	
	$('#request_table tr:last').after(data_string);
}
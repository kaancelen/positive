function writeToOfferRow(offer){
	$('#offer_id_'+offer['COMPANY_ID']).html(offer['ID']);
	$('#personel_id_'+offer['COMPANY_ID']).html(offer['PERSONEL_NAME']);
	$('#prim_'+offer['COMPANY_ID']).val(offer['PRIM']);
	$('#komisyon_'+offer['COMPANY_ID']).val(offer['KOMISYON']);
	$('#prod_komisyon_'+offer['COMPANY_ID']).val(offer['PROD_KOMISYON']);
	$('#make_policy_'+offer['COMPANY_ID']).css("display", "block");
}

function navigateToPolicyRequest(companyId){
	var offer_id = $('#offer_id_'+companyId).html();
	location.href = '/positive/branch/policyRequest.php?offer_id='+offer_id;
}

function pullOffers(request_id){
	var data = new FormData();
	data.append('request_id', request_id);
	
	//in every 5 second make ajax request
	setInterval(function(){
		//make ajax request
	    $.ajax({
	        url: '/positive/ajax/pull_offers.php',
	        type: 'POST',
	        data: data,
	        cache: false,
	        dataType: 'json',
	        processData: false,
	        contentType: false,
	        success: function(data, textStatus, jqXHR){
	        	console.log(data);
	        	if(data){
	        		var min_value = Number.MAX_VALUE;
	        		var min_value_comp = 0;
	        		//write offers
		    	    for (var i = 0; i < data.length; i++) {
		    	    	var temp_value = parseFloat(data[i]['PRIM']);
		    			if(temp_value < min_value){
		    				min_value = temp_value;
		    				min_value_comp = data[i]['COMPANY_ID'];
		    			}
		    			$('#offer_row_'+data[i]['COMPANY_ID']).css("background-color","");//remove css
		    	    	writeToOfferRow(data[i]);
		    	    }
		    	    if(min_value_comp > 0){
		    			$('#offer_row_'+min_value_comp).css("background-color","#00FF66");//paint to green
		    		}
	        	}
	        },
	        error: function(jqXHR, textStatus, errorThrown){
	            console.log('pull offer ajax error : ' + textStatus);
	        },
	        complete: function(jqXHR, textStatus){
	            console.log("pull offer ajax call complete : " + textStatus);
	        }
	    });
	    
	}, 
	5000);
}
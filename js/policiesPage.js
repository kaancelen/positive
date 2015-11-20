function cancelRequestOperation(cancel_id, status){
	var confirmText = "";
	if(status == 1){
		confirmText = "ONAYLAYACAKSINIZ";
	}else if(status == 2){
		confirmText = "REDDEDECEKSİNİZ";
	}
	var r = confirm("Bu poliçe iptal isteğini "+confirmText+". Emin misiniz?");
	if(!r){
		return;
	}
	
	var data = new FormData();
	data.append('cancel_id', cancel_id);
	data.append('status', status);
	//make ajax request
	$.ajax({
		url: '/positive/ajax/cancel_request.php',
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false,
		contentType: false,
		success: function(data, textStatus, jqXHR){
			console.log(data);
			if(data){
				location.reload();
			}else{
				alert("Bir hata ile karşılaşıldı!");
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log('close request ajax error : ' + textStatus);
		},
		complete: function(jqXHR, textStatus){
			console.log("close request ajax call complete : " + textStatus);
		}
	});
}

function refreshTime(personel){
	var month = $('#month').val();
	var year = $('#year').val();
	
	if(personel){
		location.href = "/positive/personel/policyCancels.php?month="+month+"&year="+year;
	}else{
		location.href = "/positive/branch/policyCancels.php?month="+month+"&year="+year;
	}
}

function refreshTimePolicy(personel){
	var month = $('#month').val();
	var year = $('#year').val();
	
	if(personel){
		location.href = "/positive/personel/policies.php?month="+month+"&year="+year;
	}else{
		location.href = "/positive/branch/policies.php?month="+month+"&year="+year;
	}
}

function onDropdownChange(){
	agent_name = $('#selected_agent').val();
	policy_type = $('#selected_policy_type').val();
	company = $('#selected_company').val();
	
	$("table tr").each(function(index) {
        if (index > 1) {
            $row = $(this);
            var agent_name_id = $row.find("td:nth-child(4)").text();
            var policy_type_id = $row.find("td:nth-child(5)").text();
            var company_id = $row.find("td:nth-child(8)").text();
            
            if( (agent_name === 'NULL' && policy_type === 'NULL' && company === 'NULL') ||
        		(agent_name === 'NULL' && policy_type === 'NULL' && company_id.indexOf(company) === 0) ||
        		(agent_name === 'NULL' && company === 'NULL' && policy_type_id.indexOf(policy_type) === 0) ||
        		(policy_type === 'NULL' && company === 'NULL' && agent_name_id.indexOf(agent_name) === 0) ||
        		(agent_name === 'NULL' && policy_type_id.indexOf(policy_type) === 0 && company_id.indexOf(company) === 0) ||
        		(policy_type === 'NULL' && agent_name_id.indexOf(agent_name) === 0 && company_id.indexOf(company) === 0) ||
        		(company === 'NULL' && agent_name_id.indexOf(agent_name) === 0 && policy_type_id.indexOf(policy_type) === 0) ||
        		(agent_name_id.indexOf(agent_name) === 0 && policy_type_id.indexOf(policy_type) === 0 && company_id.indexOf(company) === 0)){
            	
            	$row.show();
            }else{
            	$row.hide();
            }
        }
    });
}

function onDropdownChangeCancels(){
	agent_name = $('#selected_agent').val();
	policy_type = $('#selected_policy_type').val();
	company = $('#selected_company').val();
	
	$("table tr").each(function(index) {
        if (index > 1) {
            $row = $(this);
            var agent_name_id = $row.find("td:nth-child(3)").text();
            var policy_type_id = $row.find("td:nth-child(4)").text();
            var company_id = $row.find("td:nth-child(7)").text();
            
            if( (agent_name === 'NULL' && policy_type === 'NULL' && company === 'NULL') ||
        		(agent_name === 'NULL' && policy_type === 'NULL' && company_id.indexOf(company) === 0) ||
        		(agent_name === 'NULL' && company === 'NULL' && policy_type_id.indexOf(policy_type) === 0) ||
        		(policy_type === 'NULL' && company === 'NULL' && agent_name_id.indexOf(agent_name) === 0) ||
        		(agent_name === 'NULL' && policy_type_id.indexOf(policy_type) === 0 && company_id.indexOf(company) === 0) ||
        		(policy_type === 'NULL' && agent_name_id.indexOf(agent_name) === 0 && company_id.indexOf(company) === 0) ||
        		(company === 'NULL' && agent_name_id.indexOf(agent_name) === 0 && policy_type_id.indexOf(policy_type) === 0) ||
        		(agent_name_id.indexOf(agent_name) === 0 && policy_type_id.indexOf(policy_type) === 0 && company_id.indexOf(company) === 0)){
            	
            	$row.show();
            }else{
            	$row.hide();
            }
        }
    });
}
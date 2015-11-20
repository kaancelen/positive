function refreshTime(personel){
	var month = $('#month').val();
	var year = $('#year').val();
	
	if(personel){
		location.href = "/positive/personel/completedPolicies.php?month="+month+"&year="+year;
	}else{
		location.href = "/positive/branch/completedPolicies.php?month="+month+"&year="+year;
	}
}

function onSelectedAgentChange(){
	agent_name = $('#selected_agent').val();
	$("table tr").each(function(index) {
        if (index > 1) {
            $row = $(this);
            var id = $row.find("td:nth-child(6)").text();
            if(agent_name === 'NULL'){
            	$row.show();
            }else if (id.indexOf(agent_name) !== 0) {
                $row.hide();
            }else {
                $row.show();
            }
        }
    });
}

function onDropdownChange(){
	agent_name = $('#selected_agent').val();
	policy_type = $('#selected_policy_type').val();
	company = $('#selected_company').val();
	
	$("table tr").each(function(index) {
        if (index > 1) {
            $row = $(this);
            var agent_name_id = $row.find("td:nth-child(6)").text();
            var policy_type_id = $row.find("td:nth-child(2)").text();
            var company_id = $row.find("td:nth-child(4)").text();
            
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
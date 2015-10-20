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
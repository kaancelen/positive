function refreshTime(personel){
	var month = $('#month').val();
	var year = $('#year').val();
	
	if(personel){
		location.href = "/positive/personel/completedPolicies.php?month="+month+"&year="+year;
	}else{
		location.href = "/positive/branch/completedPolicies.php?month="+month+"&year="+year;
	}
}
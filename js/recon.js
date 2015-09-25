function refreshReconTime(){
	var month = $('#recon_month').val();
	var year = $('#recon_year').val();
	
	location.href = "/positive/recons.php?month="+month+"&year="+year;
}

function refreshReconTable(){
	var month = $('#recon_month').val();
	var year = $('#recon_year').val();
	
	var data = new FormData();
	data.append('month', month);
	data.append('year', year);
	
	//make ajax request
	$.ajax({
		url: '/positive/ajax/updateReconTable.php',
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false,
		contentType: false,
		success: function(data, textStatus, jqXHR){
			if(data){
				location.reload();
			}else{
				alert("Bir hata ile karşılaşıldı!");
				$('#loading_modal').modal('hide');
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
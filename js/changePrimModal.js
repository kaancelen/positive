function updateOffer(offer_id){
	var prim = $('#prim').val();
	var komisyon = $('#komisyon').val();
	var prod_komisyon = $('#prod_komisyon').val();
	var ust_komisyon = $('#ust_komisyon').val();
	var bagli_komisyon = $('#bagli_komisyon').val();
	
	if(prim == null || prim.length == 0){
		alert("Lütfen prim bilgisini giriniz!");
		return;
	}
	if(komisyon == null || komisyon.length == 0){
		alert("Lütfen komisyon bilgisini giriniz!");
		return;
	}
	
	var message = "Prim("+prim+") Komisyon("+komisyon+") olarak güncellenecektir, onaylıyormusunuz?";
	if(!confirm(message)){
		return;
	}
	
	var data = new FormData();
	data.append('offer_id', offer_id);
	data.append('prim', prim);
	data.append('komisyon', komisyon);
	data.append('prod_komisyon', prod_komisyon);
	data.append('ust_komisyon', ust_komisyon);
	data.append('bagli_komisyon', bagli_komisyon);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/change_offer.php',
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
				alert("Teklif bilgisi değiştirilemedi! Bir hata ile karşılaşıldı.");
			}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('add offer ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("add offer ajax call complete : " + textStatus);
        }
    });
}
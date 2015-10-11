function validateBranchRecon(takipNo){
	var yeniTecdit = $('#YENI_TECDIT').val();
	var zeyilNo = $('#ZEYIL_NO').val();
	var musteriTipi = $('#MUSTERI_TIPI').val();
	var musteriAdi = $('#MUSTERI_ADI').val();
	var baslangicTarihi = $('#BASLANGIC_TARIHI').val();
	var bitisTarihi = $('#BITIS_TARIHI').val();
	var paraBirimi = $('#PARA_BIRIMI').val();
	var net = $('#NET').val();
	
	var message = "";
	
	if(yeniTecdit == null || yeniTecdit.length == 0){
		message += "Lütfen Yeni/Tecdit bilgisini boş bırakmayınız.<br>";
	}
	if(zeyilNo == null || zeyilNo.length == 0){
		message += "Lütfen Zeyil no bilgisini boş bırakmayınız.<br>";
	}else if(!isNumeric(zeyilNo)){
		message += "Zeyil no bilgisi tam sayı olmalıdır.<br>";
	}
	if(musteriTipi == null || musteriTipi.length == 0){
		message += "Lütfen Müşteri tipi bilgisini boş bırakmayınız.<br>";
	}else if(musteriTipi.length > 32){
		message += "Müşteri tipi bilgisi en fazla 32 karakter olabilir.<br>";
	}
	if(musteriAdi == null || musteriAdi.length == 0){
		message += "Lütfen Müşteri adı bilgisini boş bırakmayınız.<br>";
	}else if(musteriAdi.length > 128){
		message += "Müşteri adı bilgisi en fazla 128 karakter olabilir.<br>";
	}
	if(baslangicTarihi == null || baslangicTarihi.length == 0){
		message += "Lütfen Başlangıç tarihi bilgisini boş bırakmayınız.<br>";
	}else if(baslangicTarihi.length > 32){
		message += "Başlangıç tarihi bilgisi en fazla 32 karakter olabilir.<br>";
	}
	if(bitisTarihi == null || bitisTarihi.length == 0){
		message += "Lütfen Bitiş tarihi bilgisini boş bırakmayınız.<br>";
	}else if(bitisTarihi.length > 32){
		message += "Bitiş tarihi bilgisi en fazla 32 karakter olabilir.<br>";
	}
	if(paraBirimi == null || paraBirimi.length == 0){
		message += "Lütfen Para birimi bilgisini boş bırakmayınız.<br>";
	}else if(paraBirimi.length > 4){
		message += "Para birimi bilgisi en fazla 4 karakter olabilir.<br>";
	}
	if(net == null || net.length == 0){
		message += "Lütfen Net bilgisini boş bırakmayınız.<br>";
	}
	
	if(message != ""){
		$('#branch-error').html(message);
		return;
	}else{
		$('#branch-error').html('');
	}
	
	var data = new FormData();
	data.append('TAKIP_NO', takipNo);
	data.append('YENI_TECDIT', yeniTecdit);
	data.append('ZEYIL_NO', zeyilNo);
	data.append('MUSTERI_TIPI', musteriTipi);
	data.append('MUSTERI_ADI', musteriAdi);
	data.append('BASLANGIC_TARIHI', baslangicTarihi);
	data.append('BITIS_TARIHI', bitisTarihi);
	data.append('PARA_BIRIMI', paraBirimi);
	data.append('NET', net);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/add_recon_branch.php',
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
        		alert('Mutabakat bilgileri güncellenemedi, bir hata ile karşılaşıldı!');
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('validateBranchRecon ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("validateBranchRecon ajax call complete : " + textStatus);
        }
    });
}

function validateFinanceRecon(takipNo){
	var bolge = $('#BOLGE').val();
	var bagli = $('#BAGLI').val();
	var tahsilatDurumu = $('#TAHSILAT_DURUMU').val();
	var sirketTahsilatDurumu = $('#SIRKET_TAHSILAT_DURUMU').val();
	var aciklama = $('#ACIKLAMA').val();
	var heroKomisyon = $('#HERO_KOMISYON').val();
	var bolgeKomisyon = $('#BOLGE_KOMISYON').val();
	var subeKomisyon = $('#SUBE_KOMISYON').val();
	var bagliKomisyon = $('#BAGLI_KOMISYON').val();
	var musteriyeIade = $('#MUSTERIYE_IADE').val();
	var merkez = $('#MERKEZ').val();
	
	var message = "";
	
	if(bolge == null || bolge.length == 0){
		message += "Lütfen bölge bilgisini boş bırakmayınız.<br>";
	}else if(bolge.length > 16){
		message += "Bölge bilgisi en fazla 16 karakter olabilir.<br>";
	}
	if(bagli == null || bagli.length == 0){
		message += "Lütfen bağlı bilgisini boş bırakmayınız.<br>";
	}else if(bagli.length > 128){
		message += "Bağlı bilgisi en fazla 128 karakter olabilir.<br>";
	}
	if(tahsilatDurumu == null || tahsilatDurumu.length == 0){
		message += "Lütfen tahsilat durumu bilgisini boş bırakmayınız.<br>";
	}else if(tahsilatDurumu.length > 32){
		message += "Tahsilat durumu bilgisi en fazla 32 karakter olabilir.<br>";
	}
//	if(sirketTahsilatDurumu == null || sirketTahsilatDurumu.length == 0){
//		message += "Lütfen şirket tahsilat durumu bilgisini boş bırakmayınız.<br>";
//	}else if(sirketTahsilatDurumu.length > 32){
//		message += "Şirket tahsilat durumu bilgisi en fazla 32 karakter olabilir.<br>";
//	}
//	if(aciklama == null || aciklama.length == 0){
//		message += "Lütfen Açıklama bilgisini boş bırakmayınız.<br>";
//	}else if(aciklama.length > 256){
//		message += "Açıklama bilgisi en fazla 256 karakter olabilir.<br>";
//	}
	if(heroKomisyon == null || heroKomisyon.length == 0){
		message += "Lütfen Hero Komisyon bilgisini boş bırakmayınız.<br>";
	}
	if(bolgeKomisyon == null || bolgeKomisyon.length == 0){
		message += "Lütfen Bölge Komisyon bilgisini boş bırakmayınız.<br>";
	}
	if(subeKomisyon == null || subeKomisyon.length == 0){
		message += "Lütfen Şube Komisyon bilgisini boş bırakmayınız.<br>";
	}
	if(bagliKomisyon == null || bagliKomisyon.length == 0){
		message += "Lütfen Bağlı Komisyon bilgisini boş bırakmayınız.<br>";
	}
	if(musteriyeIade == null || musteriyeIade.length == 0){
		message += "Lütfen Müşteriye iade bilgisini boş bırakmayınız.<br>";
	}
	if(merkez == null || merkez.length == 0){
		message += "Lütfen Merkez bilgisini boş bırakmayınız.<br>";
	}
	
	if(message != ""){
		$('#finans-error').html(message);
		return;
	}else{
		$('#finans-error').html('');
	}
	
	var data = new FormData();
	data.append('TAKIP_NO', takipNo);
	data.append('BOLGE', bolge);
	data.append('BAGLI', bagli);
	data.append('TAHSILAT_DURUMU', tahsilatDurumu);
	data.append('SIRKET_TAHSILAT_DURUMU', sirketTahsilatDurumu);
	data.append('ACIKLAMA', aciklama);
	data.append('HERO_KOMISYON', heroKomisyon);
	data.append('BOLGE_KOMISYON', bolgeKomisyon);
	data.append('SUBE_KOMISYON', subeKomisyon);
	data.append('BAGLI_KOMISYON', bagliKomisyon);
	data.append('MUSTERIYE_IADE', musteriyeIade);
	data.append('MERKEZ', merkez);
	
	//make ajax request
    $.ajax({
        url: '/positive/ajax/add_recon_finance.php',
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
        		alert('Mutabakat bilgileri güncellenemedi, bir hata ile karşılaşıldı!');
        	}
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('validateFinanceRecon ajax error : ' + textStatus);
        },
        complete: function(jqXHR, textStatus){
            console.log("validateFinanceRecon ajax call complete : " + textStatus);
        }
    });
}

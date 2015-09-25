<!-- BRANCH -->
<head>
<?php 
	include_once(__DIR__.'/head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/Util/init.php');
	include_once (__DIR__.'/classes/Recon.php');
	if($loggedIn){
		$user = Session::get(Session::USER);
	}
	include_once (__DIR__.'/navigationBar.php');
	
	if(isset($_GET['recon_id'])){
		$takip_no = urlencode($_GET['recon_id']);
	}
	if(empty($takip_no)){
		Util::redirect('/positive/error/404.php');
	}
	
	$reconService = new ReconService();
	$reconDetail = $reconService->getRecon($takip_no, $user[User::ID], $user[User::ROLE]);
	if(is_null($reconDetail)){
		Util::redirect('/positive/error/403.php');
	}
	
	if(Session::exists(Session::FLASH)){
		?>
		<div id="user_form_msg" align="center">
			<div class="alert alert-success" role="alert"><?php echo Session::get(Session::FLASH); ?></div>
		</div>
		<?php
		Session::delete(Session::FLASH);//Remove message
	}
?>
	<script src="/positive/js/reconDetail.js"></script>
	<div class="container">
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<td><b>Takip No</b></td>
					<td><b>Tanzim Tarihi</b></td>
					<td><b>Şirket</b></td>
					<td><b>Poliçe No</b></td>
					<td><b>Poliçe Türü</b></td>
					<td><b>Prodüktör</b></td>
				</tr>
				<tr>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $reconDetail[Recon::TAKIP_NO]; ?></td>
					<td><?php echo DateUtil::format($reconDetail[Recon::TANZIM_TARIHI]);?></td>
					<td><?php echo $reconDetail[Recon::SIRKET]; ?></td>
					<td><?php echo $reconDetail[Recon::POLICE_NO]; ?></td>
					<td><?php echo $reconDetail[Recon::POLICE_TURU]; ?></td>
					<td><?php echo $reconDetail[Recon::PRODUKTOR]; ?></td>
				</tr>
			</tbody>
		</table>
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<td><b>TC Kimlik No</b></td>
					<td><b>Vergi No</b></td>
					<td><b>Ek Bilgi</b></td>
					<td><b>Brüt</b></td>
					<td><b>Komisyon</b></td>
					<td><b>Prod Komisyon</b></td>
				</tr>
				<tr>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $reconDetail[Recon::TCKN]; ?></td>
					<td><?php echo $reconDetail[Recon::VERGI_NO]; ?></td>
					<td><?php echo $reconDetail[Recon::EK_BILGI]; ?></td>
					<td><?php echo $reconDetail[Recon::BRUT]; ?></td>
					<td><?php echo $reconDetail[Recon::KOMISYON]; ?></td>
					<td><?php echo $reconDetail[Recon::PROD_KOMISYON]; ?></td>
				</tr>
			</tbody>
		</table>
		<br/>
		<?php if ($user[User::ROLE] == User::PERSONEL || $user[User::ROLE] == User::ADMIN){?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Teknikçi dolduracak</h3>
			</div>
			<label class="login-error" id="personel-error"></label>
			<div class="panel-body">
				<div class="search-column">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Kaynak</span>
						<select id="KAYNAK" name="KAYNAK" class="form-control">
							<option value="">Seçiniz</option>
							<option value="İÇ" <?php if($reconDetail[Recon::KAYNAK] == "İÇ") echo 'selected="selected"';?>>İÇ</option>
							<option value="DIŞ" <?php if($reconDetail[Recon::KAYNAK] == "DIŞ") echo 'selected="selected"';?>>DIŞ</option>
						</select>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Üretim Kanalı</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="URETIM_KANALI" name="URETIM_KANALI"
						value="<?php echo $reconDetail[Recon::URETIM_KANALI];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Müşteri Tipi</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="MUSTERI_TIPI" name="MUSTERI_TIPI"
						value="<?php echo $reconDetail[Recon::MUSTERI_TIPI]?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Yeni/Tecdit</span>
						<select id="YENI_TECDIT" name="YENI_TECDIT" class="form-control">
							<option value="">Seçiniz</option>
							<option value="YENİ" <?php if($reconDetail[Recon::YENI_TECDIT] == "YENİ") echo 'selected="selected"';?>>YENİ</option>
							<option value="TECDİT" <?php if($reconDetail[Recon::YENI_TECDIT] == "TECDİT") echo 'selected="selected"';?>>TECDİT</option>
						</select>
					</div>
				</div>
				<div class="result-column">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Zeyil No</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="ZEYIL_NO" name="ZEYIL_NO"
						value="<?php echo $reconDetail[Recon::ZEYIL_NO];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Para Birimi</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="PARA_BIRIMI" name="PARA_BIRIMI"
						value="<?php echo $reconDetail[Recon::PARA_BIRIMI];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Net</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="NET" name="NET"
						value="<?php echo $reconDetail[Recon::NET];?>">
					</div>
				</div>
			</div>
			<button id="recon_personel" type="button" class="btn btn-default" onclick="validatePersonelRecon(<?php echo $reconDetail[Recon::TAKIP_NO]; ?>);">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Tamamla
			</button>
		</div>
		<?php } ?>
		<br/>
		<?php if ($user[User::ROLE] == User::BRANCH || $user[User::ROLE] == User::ADMIN) {?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Acente dolduracak</h3>
			</div>
			<label class="login-error" id="branch-error"></label>
			<div class="panel-body">
				<div class="search-column">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Müşteri Adı</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="MUSTERI_ADI" name="MUSTERI_ADI"
						value="<?php echo $reconDetail[Recon::MUSTERI_ADI];?>">
					</div>
				</div>
				<div class="result-column">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Başlangıç Tarihi</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="BASLANGIC_TARIHI" name="BASLANGIC_TARIHI"
						value="<?php echo $reconDetail[Recon::BASLANGIC_TARIHI];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Bitiş Tarihi</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="BITIS_TARIHI" name="BITIS_TARIHI"
						value="<?php echo $reconDetail[Recon::BITIS_TARIHI];?>">
					</div>
				</div>
			</div>
			<button id="recon_branch" type="button" class="btn btn-default" onclick="validateBranchRecon(<?php echo $reconDetail[Recon::TAKIP_NO]; ?>);">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Tamamla
			</button>
		</div>
		<?php } ?>
		<br/>
		<?php if ($user[User::ROLE] == User::FINANCE || $user[User::ROLE] == User::ADMIN) {?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Finans dolduracak</h3>
			</div>
			<label class="login-error" id="finans-error"></label>
			<div class="panel-body">
				<div class="search-column">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Bölge</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="BOLGE" name="BOLGE"
						value="<?php echo $reconDetail[Recon::BOLGE];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Bağlı</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="BAGLI" name="BAGLI"
						value="<?php echo $reconDetail[Recon::BAGLI];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Tahsilat Durumu</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="TAHSILAT_DURUMU" name="TAHSILAT_DURUMU"
						value="<?php echo $reconDetail[Recon::TAHSILAT_DURUMU];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Şirket Tahsilat Durumu</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="SIRKET_TAHSILAT_DURUMU" name="SIRKET_TAHSILAT_DURUMU"
						value="<?php echo $reconDetail[Recon::SIRKET_TAHSILAT_DURUMU];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Açıklama</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="ACIKLAMA" name="ACIKLAMA"
						value="<?php echo $reconDetail[Recon::ACIKLAMA];?>">
					</div>
				</div>
				<div class="result-column">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Hero Komisyon</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="HERO_KOMISYON" name="HERO_KOMISYON"
						value="<?php echo $reconDetail[Recon::HERO_KOMISYON];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Bölge Komisyon</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="BOLGE_KOMISYON" name="BOLGE_KOMISYON"
						value="<?php echo $reconDetail[Recon::BOLGE_KOMISYON];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Şube Komisyon</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="SUBE_KOMISYON" name="SUBE_KOMISYON"
						value="<?php echo $reconDetail[Recon::SUBE_KOMISYON];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Bağlı Komisyon</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="BAGLI_KOMISYON" name="BAGLI_KOMISYON"
						value="<?php echo $reconDetail[Recon::BAGLI_KOMISYON];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Müşteriye İade</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="MUSTERIYE_IADE" name="MUSTERIYE_IADE"
						value="<?php echo $reconDetail[Recon::MUSTERIYE_IADE];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Merkez</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="MERKEZ" name="MERKEZ"
						value="<?php echo $reconDetail[Recon::MERKEZ];?>">
					</div>
				</div>
			</div>
			<button id="recon_finance" type="button" class="btn btn-default" onclick="validateFinanceRecon(<?php echo $reconDetail[Recon::TAKIP_NO]; ?>);">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Tamamla
			</button>
		</div>
		<?php }?>
	</div>
	<script type="text/javascript">
		//initialize mask
		$('#NET').mask('000.000.000.000.000,00', {reverse: true});
		$('#HERO_KOMISYON').mask('000.000.000.000.000,00', {reverse: true});
		$('#BOLGE_KOMISYON').mask('000.000.000.000.000,00', {reverse: true});
		$('#SUBE_KOMISYON').mask('000.000.000.000.000,00', {reverse: true});
		$('#BAGLI_KOMISYON').mask('000.000.000.000.000,00', {reverse: true});
		$('#MUSTERIYE_IADE').mask('000.000.000.000.000,00', {reverse: true});
		$('#MERKEZ').mask('000.000.000.000.000,00', {reverse: true});
		//datepicker
		$('#BASLANGIC_TARIHI').datepicker();
		$('#BITIS_TARIHI').datepicker();
		//active
		$('#recon_1').addClass("active");
	</script>
</body>
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
	
	$agentService = new AgentService();
	$agentRelation = $agentService->getAgentRelation($reconDetail[Recon::PRODUKTOR_ID]);

	$enteredUserType = 0; //Admin,Finance; 1=> Acente, 2 => Üst Acente, 3 => Bağlı
	if($user[User::ROLE] == User::BRANCH){
		if($agentRelation[AgentRelation::ACENTE] == $user[User::ID]){
			$enteredUserType = 1;
		}else if($agentRelation[AgentRelation::UST_ACENTE] == $user[User::ID]){
			$enteredUserType = 2;
		}else if($agentRelation[AgentRelation::BAGLI_ACENTE] == $user[User::ID]){
			$enteredUserType = 3;
		}
	}
	
	$userService = new UserService();
	$allAgents = $userService->allTypeOfUsers(User::BRANCH);
	
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
					<td><b>Üretim/İptal</b></td>
					<td><b>Poliçe No</b></td>
					<td><b>TC/VKN</b></td>
					<td><b>Tanzim Tarihi</b></td>
					<td><b>Prodüktör</b></td>
					<td><b>Şirket</b></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $reconDetail[Recon::TAKIP_NO]; ?></td>
					<td><?php echo $reconDetail[Recon::URETIM_IPTAL]; ?></td>
					<td><?php echo $reconDetail[Recon::POLICE_NO]; ?></td>
					<td><?php echo $reconDetail[Recon::TCKN].''.$reconDetail[Recon::VERGI_NO]; ?></td>
					<td><?php echo DateUtil::format($reconDetail[Recon::TANZIM_TARIHI]);?></td>
					<td><?php echo $reconDetail[Recon::SIRKET]; ?></td>
					<td><?php echo $reconDetail[Recon::PRODUKTOR]; ?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<td><b>Bağlı</b></td>
					<td><b>Üst Acente</b></td>
					<td><b>Tahsilat Durumu</b></td>
					<td><b>Şirket Tah. Dur.</b></td>
					<td><b>Açıklama</b></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $reconDetail[Recon::BAGLI]; ?></td>
					<td><?php echo $reconDetail[Recon::UST_PRODUKTOR]; ?></td>
					<td><?php echo $reconDetail[Recon::TAHSILAT_DURUMU]; ?></td>
					<td><?php echo $reconDetail[Recon::SIRKET_TAHSILAT_DURUMU]; ?></td>
					<td><?php echo $reconDetail[Recon::ACIKLAMA]; ?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<?php if($enteredUserType == 0){?>
						<td><b>Kaynak</b></td>
						<td><b>Üretim Kanalı</b></td>
					<?php }?>
					<td><b>Brüt</b></td>
					<td><b>Komisyon</b></td>
					<td><b>Müşteriye İade</b></td>
					<td><b>Prodüktör Komisyonu</b></td>
					<?php if($enteredUserType == 0 || $enteredUserType == 3){?>
						<td><b>Bağlı Komisyonu</b></td>
					<?php } ?>
					<?php if($enteredUserType == 0 || $enteredUserType == 2){?>
						<td><b>Üst Acente Komisyonu</b></td>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<?php if($enteredUserType == 0){?>
						<td><?php echo $reconDetail[Recon::KAYNAK]; ?></td>
						<td><?php echo $reconDetail[Recon::URETIM_KANALI]; ?></td>
					<?php }?>
					<td><?php echo $reconDetail[Recon::BRUT]; ?></td>
					<td><?php echo $reconDetail[Recon::KOMISYON]; ?></td>
					<td><?php echo $reconDetail[Recon::MUSTERIYE_IADE]; ?></td>
					<td><?php echo $reconDetail[Recon::PROD_KOMISYON]; ?></td>
					<?php if($enteredUserType == 0 || $enteredUserType == 3){?>
						<td><?php echo $reconDetail[Recon::BAGLI_KOMISYON]; ?></td>
					<?php } ?>
					<?php if($enteredUserType == 0 || $enteredUserType == 2){?>
						<td><?php echo $reconDetail[Recon::UST_PRODUKTOR_KOMISYON]; ?></td>
					<?php } ?>
				</tr>
			</tbody>
		</table>
		<br/>
		<?php $class = $reconService->isReconCompleted(User::BRANCH, $reconDetail); ?>
		<div class="panel <?php if(empty($class)){echo "panel-danger";}else{echo "panel-success";}?>">
			<div class="panel-heading">
				<h3 class="panel-title">Acente dolduracak</h3>
			</div>
			<label class="login-error" id="branch-error"></label>
			<div class="panel-body">
				<h6><i>Tarihleri takvim kullanmak yerine klavyeyi kullanarak da doldurabilirsiniz.</i></h6>
				<div class="search-column">
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
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Zeyil No</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="ZEYIL_NO" name="ZEYIL_NO"
						value="<?php echo $reconDetail[Recon::ZEYIL_NO];?>">
					</div>
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
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Poliçe Türü</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="POLICE_TURU" name="POLICE_TURU"
						value="<?php echo $reconDetail[Recon::POLICE_TURU];?>">
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
			<button id="recon_branch" type="button" class="btn btn-default" onclick="validateBranchRecon(<?php echo $reconDetail[Recon::TAKIP_NO]; ?>);">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Tamamla
			</button>
		</div>
		<br/>
		<?php if ($user[User::ROLE] == User::FINANCE || $user[User::ROLE] == User::ADMIN) {?>
		<?php $class = $reconService->isReconCompleted(User::FINANCE, $reconDetail); ?>
		<div class="panel <?php if(empty($class)){echo "panel-danger";}else{echo "panel-success";}?>">
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
						<select id="BAGLI" name="BAGLI" class="form-control">
							<option value="">Yok</option>
							<?php foreach ($allAgents as $agent){?>
								<option value="<?php echo $agent[User::NAME];?>"><?php echo $agent[User::NAME];?></option>
							<?php }?>
						</select>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Üst Acente</span>
						<select id="UST_PRODUKTOR" name="UST_PRODUKTOR" class="form-control">
							<option value="">Yok</option>
							<?php foreach ($allAgents as $agent){?>
								<option value="<?php echo $agent[User::NAME];?>"><?php echo $agent[User::NAME];?></option>
							<?php }?>
						</select>
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
						<span class="input-group-addon" id="basic-addon1">Bağlı Komisyon</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="BAGLI_KOMISYON" name="BAGLI_KOMISYON"
						value="<?php echo $reconDetail[Recon::BAGLI_KOMISYON];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Üst Acente Komisyon</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="UST_PRODUKTOR_KOMISYON" name="UST_PRODUKTOR_KOMISYON"
						value="<?php echo $reconDetail[Recon::UST_PRODUKTOR_KOMISYON];?>">
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
						<span class="input-group-addon" id="basic-addon1">Müşteriye İade</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="MUSTERIYE_IADE" name="MUSTERIYE_IADE"
						value="<?php echo $reconDetail[Recon::MUSTERIYE_IADE];?>">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Prodüktör Komisyonu</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="PROD_KOMISYON" name="PROD_KOMISYON"
						value="<?php echo $reconDetail[Recon::PROD_KOMISYON];?>">
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
		$('#UST_PRODUKTOR_KOMISYON').mask('000.000.000.000.000,00', {reverse: true});
		$('#SUBE_KOMISYON').mask('000.000.000.000.000,00', {reverse: true});
		$('#BAGLI_KOMISYON').mask('000.000.000.000.000,00', {reverse: true});
		$('#MUSTERIYE_IADE').mask('000.000.000.000.000,00', {reverse: true});
		$('#PROD_KOMISYON').mask('000.000.000.000.000,00', {reverse: true});
		$('#MERKEZ').mask('000.000.000.000.000,00', {reverse: true});
		//datepicker
		$('#BASLANGIC_TARIHI').datepicker();
		$('#BITIS_TARIHI').datepicker();
		//bagli, ust acente
		$('#BAGLI').val('<?php echo $reconDetail[Recon::BAGLI];?>');
		$('#UST_PRODUKTOR').val('<?php echo $reconDetail[Recon::UST_PRODUKTOR];?>');
		//active
		$('#recon_1').addClass("active");
	</script>
</body>
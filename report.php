<head>
<?php 
	include_once(__DIR__.'/head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/Util/init.php');
	include_once (__DIR__.'/navigationBar.php');
?>
	<script src="/positive/js/report.js"></script>
	<div class="container">
		<form class="form-signin" id="positive-report" action="/positive/saveReport.php" method="post" autocomplete="off" enctype="multipart/form-data">
			<label for="subject" class="login-error" id="subject-error"></label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Başlık</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="subject" name="subject">
			</div>
			<br>
			<label for="subject" class="login-error" id="content-error"></label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">İçerik</span>
				<textarea rows="8" class="form-control" aria-describedby="basic-addon1" id="content" name="content"></textarea>
			</div>
			<h6>(Max 4000 karakter)</h6>
			<br>
			<h6>Hata açıklamasına yardımcı olması için en çok 2 ekran görüntüsü yükleyebilirsiniz.</h6>
			<label for="subject" class="login-error" id="ss1-error"></label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Ekran Görüntüsü 1</span>
				<input type="file" class="form-control" aria-describedby="basic-addon1" id="ss1" name="ss1">
			</div>
			<br>
			<label for="subject" class="login-error" id="ss2-error"></label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Ekran Görüntüsü 2</span>
				<input type="file" class="form-control" aria-describedby="basic-addon1" id="ss2" name="ss2">
			</div>
			<br>
			<button class="btn btn-lg btn-primary btn-block" type="button" id="submit_button" onclick="validateReportForm()">Bildir</button>
		</form>
	</div>
<script type="text/javascript">
	$('#report_1').addClass("active");
</script>
</body>
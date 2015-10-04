<?php 
	if(!$loggedIn){
		Util::redirect("/positive");
	}else{ 
		$nav_user = Session::get(Session::USER);
	}
?>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
	    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
		<a class="navbar-brand" href="/positive">
			<span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
			Positive
		</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav nav-pills">
      	<?php if($nav_user[User::ROLE] == User::ADMIN){?>
      		<li id="admin_1"><a href="/positive/admin/users.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Kullanıcılar</a></li>
      		<li id="admin_2"><a href="/positive/admin/userExceptions.php"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>Hata Bildirimleri</a></li>
<!--       		<li id="admin_3"><a href="/positive/admin/xmlOutput.php"><span class="glyphicon glyphicon-download" aria-hidden="true"></span>Excel Çıktısı</a></li> -->
      	<?php }?>
      	<?php if($nav_user[User::ROLE] == User::PERSONEL){?>
      		<li id="personel_1"><a href="/positive/personel/offers.php"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>Talep listesi</a></li>
      		<li id="personel_2"><a href="/positive/personel/policies.php"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>Poliçe istek</a></li>
      		<li id="personel_4"><a href="/positive/personel/policyCancels.php"><span class="glyphicon glyphicon-zoom-out" aria-hidden="true"></span>Poliçe iptal</a></li>
      		<li id="personel_3"><a href="/positive/personel/completedPolicies.php"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>Poliçeler</a></li>
      		<script src="/positive/js/offerPolicyPolling.js"></script>
      	<?php }?>
      	<?php if($nav_user[User::ROLE] == User::BRANCH){?>
      		<li id="branch_1"><a href="/positive/branch/offerRequest.php"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>Yeni Talep</a></li>
      		<li id="branch_2"><a href="/positive/branch/offers.php"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>Taleplerim</a></li>
      		<li id="branch_3"><a href="/positive/branch/policies.php"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>Poliçe istek</a></li>
      		<li id="branch_5"><a href="/positive/branch/policyCancels.php"><span class="glyphicon glyphicon-zoom-out" aria-hidden="true"></span>Poliçe iptal</a></li>
      		<li id="branch_4"><a href="/positive/branch/completedPolicies.php"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>Poliçeler</a></li>
          	<script src="/positive/js/policyPolling.js"></script>
        <?php }?>
        <?php if($nav_user[User::ROLE] == User::ADMIN || $nav_user[User::ROLE] == User::FINANCE){?>
      		<li id="recon_1"><a href="/positive/recons.php"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>Mutabakat</a></li>
      	<?php }?>
     	<li id="search_1"><a href="/positive/search.php"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>Arama</a></li>
      	<li id="report_1"><a href="/positive/report.php"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>Hata Bildir</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <ul class="nav navbar-nav">
          <li><a href="/positive/usage.pdf" target="_blank"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></li>
      	  <li><a href="/positive"><?php echo $nav_user[User::NAME]; ?></a></li>
        </ul>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $nav_user[User::CODE];?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#" onclick="location.href = '/positive/profile.php'">Profil</a></li>
            <li><a href="#" onclick="location.href = '/positive/password.php'">Şifre Değiştir</a></li>
            <li class="divider"></li>
            <li><a href="#" onclick="location.href = '/positive/logout.php'">Çıkış</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
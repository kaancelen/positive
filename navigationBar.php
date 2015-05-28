<?php 
	if(!$loggedIn){
		Util::redirect("/positive");
	}
	$nav_user = Session::get(Session::USER);
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
      <ul class="nav navbar-nav">
      	<?php if($nav_user[User::ROLE] == User::ADMIN){?>
      		<li><a href="/positive/admin/users.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Kullanıcılar</a></li>
      	<?php }?>
      	<?php if($nav_user[User::ROLE] == User::PERSONEL){?>
      		<li><a href="#">Teknik</a></li>
      	<?php }?>
      	<?php if($nav_user[User::ROLE] == User::BRANCH){?>
      		<li><a href="/positive/branch/offer.php"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>Teklif İste</a></li>
      	<?php }?>
      	<?php if($nav_user[User::ROLE] == User::FINANCE){?>
      		<li><a href="#">Finans</a></li>
      	<?php }?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <ul class="nav navbar-nav">
      	  <li><a href="/positive"><?php echo "Sayın ".$nav_user[User::NAME]; ?></a></li>
        </ul>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $nav_user[User::CODE];?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/positive/profile.php">Profil</a></li>
            <li><a href="/positive/password.php">Şifre Değiştir</a></li>
            <li class="divider"></li>
            <li><a href="/positive/logout.php">Çıkış</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
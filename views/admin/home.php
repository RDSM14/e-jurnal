	<div class="wrapper">
	    <div class="header">
	        <div class="logo"></div>
			<div class="logo_panel"></div>
			<div class="logo_wrapper">
			    <img src="logoS.png" height="110" alt=""/>
			</div>
			<div class="judul">
				E-Jurnal
			</div>
			<div class="userpanel">
				<div class="userpanelwrapper">
				    <h1> LOGGED IN AS: </h1>
					<table class="userpaneltable" border="0">
						<tr>
							<td class="foto"><div class="userphoto"></div></td>
						</tr>
						<tr>
							<td class="username"> <?php echo $nama; ?> </td>
						</tr>
						<tr>
							<td class="jabatan"> <?php echo $jabatan; ?> </td>
						</tr>
						<tr>
							<td class="userid"> <?php echo $nip; ?> </td>
						</tr>
					</table> 
					<div class="logoutbtn"><a title="" href="logout.php">LOG OUT <span class="glyphicon glyphicon-log-out"></span></a></div>
				</div>
			</div>
	    </div>
	</div>
    <div class="pagebody">
    	<div class="sidenav">
    		<ul>
				<li class="menu-item"><a href="#">Home</a></li>
			</ul>
    		<ul>
				<li class="menu-item"><a href="#">Submit Jurnal</a></li>
			</ul>
    		<ul>
				<li class="menu-item"><a href="#">Daftar Jurnal</a></li>
			</ul>
			<ul>
				<li class="menu-item"><a href="#">Profil Anda</a></li>
			</ul>
			<?php
				if ($level == 99 || $level == 98){
			?>
			<ul>
				<li class="menu-item"><a href="#">Edit Account</a></li>
			</ul>
			<?php
				}
			?>
        </div> 
		<div class="content">
			<div class="tab">
				<div class="tabN">
					<?php require_once "views/admin/submenu/kalender.php";?>
				</div>
			</div>
			<div class="tab">
				<div class="tabN">
					<?php require_once "views/admin/submenu/submit.php";?>
				</div>
			</div>
			<div class="tab">
				<div class="tabN">
					<?php require_once "views/admin/submenu/jurnaladmin.php";?>
				</div>
			</div>
			<div class="tab">
				<div class="tabN">
					<?php require_once "views/admin/submenu/profil.php";?>
				</div>
			</div>
			<?php
				if ($level == 99 || $level == 98){
			?>
			<div class="tab">
				<div class="tabN">
					<?php require_once "views/admin/submenu/promosi.php";?>
				</div>
			</div>
			<?php
				}
			?>
		</div>
    </div>


	<div class="wrapper">
	    <div class="header">
	        <div class="logo">
			    <div class="logo_wrapper">
			        <img src="logoS.png" height="110" alt=""/>
			    </div>
			</div>
			<div class="judul">
				E-Jurnal
			</div>
			<div class="userpanel">
				<div class="UPwrapper">
				    <h1> LOGGED IN AS: </h1>
					<table class="UPtable" border="0">
						<tr>
							<td class="foto"><div class="userphoto"></div></td>
						</tr>
						<tr>
							<td class="username"> <?php echo $nama; ?> </td>
						</tr>
						<tr>
							<td class="userid"> <?php echo $nip; ?> </td>
						</tr>
					</table> 
					<div class="logoutbtn"><a title="" href="logout.php">LOG OUT</a></div>
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
        </div> 
		<div class="content">
			<div class="tab">
				<div id="tab1">
					<?php require_once "views/admin/submenu/kalender.php";?>
				</div>
			</div>
			<div class="tab">
				<div id="tab2">
					<?php require_once "views/admin/submenu/submit.php";?>
				</div>
			</div>
			<div class="tab">
				<div id="tab3">
					<?php require_once "views/admin/submenu/jurnaladmin.php";?>
				</div>
			</div>
			<div class="tab">
				<div id="tab4">
					<?php require_once "views/admin/submenu/profil.php";?>
				</div>
			</div>
		</div>
    </div>

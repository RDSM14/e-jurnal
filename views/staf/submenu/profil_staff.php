
				<div class="content_profile">
					<div class="content_wrapper_profile">
						<div class="profile_header">
							<label>DATA PEMILIK ACCOUNT</label>
						</div>
						<div class="body_profile">
							<table class="actTable" id="actTable" border="1" cellpadding="20" align="center">
								<tr>
									<td><label>NIP</label></td>
									
									<td><span id="nip"><?php echo $nip; ?></span></td>
								</tr>
								<tr>
									<td><label>Nama Pegawai</label></td>
									<td><span id="nama"><?php echo $nama; ?></span></td>
								</tr>
								<tr>
									<td><label>Email</label></td>
									<td><span id="email"><?php echo $email; ?></span></td>
								</tr>
                                
                                
							</table>
                                
						</div>
						
					</div>
                    <div class="UPwrapper">
                    <button class="tombol_ubah" onclick="pass_selectActivity()">Ubah Password</button>
                    <button class="tombol_ubah" onclick="bio_selectActivity('<?php echo $nip; ?>')">Ubah Biodata</button>
                     </div>
		              <div class="content">
                        <div class="ubah_ubah">
                            <div id="ubah_ubah1">
                                <?php require_once "views/staf/submenu/ubahpassword_staff.php";?>
                            </div>
                        </div>
                        <div class="ubah_ubah">
                            <div id="ubah_ubah2">
                                <?php require_once "views/staf/submenu/ubahbiodata_staff.php";?>
                            </div>
                        </div>
				</div>
	
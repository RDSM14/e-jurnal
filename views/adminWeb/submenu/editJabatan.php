				<div class="tabContent">
					<div class="tCWrapper">
						<div class="tCheader">
							<div class="tchbox">
								
							</div>
						</div>
						<div class="tCbody" id="EJBTableWrapper" style="padding-right: 45px;"></div>
						<div id="ModalTJ" class="tCmodal">
			                <div class="tCmodal-content">
			                    <span class="TJclose closeModal">&times;</span>
			                    <div id="tCModalLabel">Edit Jabatan <label id="labelAtasan"></label></div>
			                    <form name="FormEJB" id="FormEJB" method="post" action="">
			                        <table border="0" cellpadding="8" cellspacing="0" width="650" align="center" class="tableEJB">
			                                <tr><input type="hidden" name="TJidJabatan" id="TJidJabatan" value=""/></tr>
			                                <tr>
			                                	<td><label>Nama Jabatan</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input style="width: 100%" type="text" id="TJnamaJabatan" name="nama" value="" title="Masukkan nama baru jabatan"></td>
			                                </tr>
			                                <tr>
			                                    <td colspan="5" align="right" style="height: 40px; padding: 10px; padding-top: 20px"><a name="EJBSubmit" class="EJBbtnSubmit pencetan" onclick="validateEJB()">Simpan</a></td>
			                                </tr>
			                        </table>
			                    </form>
			                </div>
			            </div>
                        <div class="lihat_pegawai" id="EJBlihat_pegawai">
                            <div class="lihat_pegawai-content">
                            <span class="EJBtutup_lihat closeModal">&times;</span>
                            <div id="detail_label">Daftar Pegawai</div>
                            <div class="detail_jurnal">
                            </div>
                            <div id="tablelihatpegawai">
                            </div>
                            </div>
                        </div>
                        <div id="modalLJ" class="tCmodal">
			                    <div class="modalLJ-content">
			                        <span class="tutupLJ">&times;</span>
			                        <div id="tCModalLabel">Daftar jurnal milik: <label id="labelPemilikJurnal"></label></div>
			                        <div class="headerLJ">
			                        	<div class="dropdownCat" title="Klik untuk melihat jurnal pegawai berdasarkan :&#013;-Harian&#013;-Mingguan&#013;-Bulanan">
						                    <button class="dropbtn" id="repBtn"><span class="glyphicon glyphicon-chevron-down"></span> <span id="repbtnLabel" style="pointer-events: none;">Mingguan</span></button>
						                    <div class="dropdownCat-content" id="repContent">
				                        		<a onclick="selectReport('Harian')" href="#">Harian <span class="glyphicon glyphicon-chevron-right"></span></a>
						                        <a onclick="selectReport('Mingguan')" href="#">Mingguan <span class="glyphicon glyphicon-chevron-right"></span></a>
						                        <a onclick="selectReport('Bulanan')" href="#">Bulanan <span class="glyphicon glyphicon-chevron-right"></span></a>
						                    </div>
						                </div>
										<div class="LJSfilter" style="display: none">
											<div class="LJSpilihHari">
												<input id="LJSpilihHari" class="w163 h30" type="date" value="<?php echo date('Y-m-d');?>"/><div class="fa fa-calendar showCalendar" aria-hidden="true" style="cursor:pointer;margin-left: 10px;margin-top: 3px;" title="Pilih tanggal"></div>
											</div>
										</div>
										<div class="LJSfilter" style="display: none">
											<div class="LJSpilihMinggu">
												<input id="LJSpilihMinggu" class="w163 h30" type="text" value="<?php echo date("Y-W");?>"/>
											</div>
										</div>
						                <div class="LJSfilter" style="display: none">
											<div class="LJSpilihTahun">
												<select id="LJSpilihTahun" class="h30">
													<?php
														$lsjTahunA = date("Y") - 10;
														$lsjTahunB = date("Y");
														for($i=$lsjTahunA; $i < $lsjTahunB; $i++){
													?>
													<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
													<?php
														}
													?>
													<option value="<?php echo $i; ?>" selected><?php echo $i; ?></option>
												</select>
											</div>
											<div class="LJSpilihBulan">
												<select id="LJSpilihBulan" class="h30">
													<?php
														$m = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
														for($i=1;$i<=12;$i++){
															$x = str_pad($i,2,0, STR_PAD_LEFT);
															if($x == date("m")){
														?>
													<option value="<?php echo $x; ?>" selected><?php echo $m[$i-1]; ?></option>
														<?php
															} else {
														?>
													<option value="<?php echo $x; ?>"><?php echo $m[$i-1]; ?></option>
														<?php
															}
														}
														?>
												</select>
											</div>
										</div>
										<input id="LJSfilterType" type="hidden" value="">
										<input id="LJSnip" type="hidden" value="">
										<a class="LJSbtn" id="LJSbtn" onclick="lihatJurnalSemua('<?php echo $nip; ?>')"><span class="glyphicon glyphicon-ok" title="klik untuk selesai meihat jurnal"></span></a>
			                        </div>
			                        <div id="tabelLJstaffContainer">
			                        </div>
			                    </div>
			                </div>
					</div>
				</div>
	
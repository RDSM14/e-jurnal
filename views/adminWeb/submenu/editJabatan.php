				<div class="tabContent">
					<div class="tCWrapper">
						<div class="tCheader">
							<div class="tchbox">
								
							</div>
						</div>
						<div class="tCbody" id="EJBTableWrapper" style="padding-right: 45px;"></div>
						<div id="ModalEA" class="tCmodal">
			                <div class="tCmodal-content">
			                    <span class="EJBclose">&times;</span>
			                    <div id="tCModalLabel">Edit Jabatan: <label id="labelJabatan"></label></div>
			                    <form name="FormEJB" id="FormEJB" method="post" action="">
			                        <table border="0" cellpadding="8" cellspacing="0" width="650" align="center" class="tableEJB">
			                                <tr><input type="hidden" name="EJBnip" id="EJBnip" value=""/></tr>
			                                <tr>
			                                	<td><label>Nama</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input style="width: 100%" type="text" id="inputNama" name="nama" value="" title="Masukkan nama pegawai yang ingin diubah"></td>
			                                </tr>
			                                <tr>
			                                	<td><label>Bagian</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input style="width: 100%" type="text" id="inputBagian" name="bagian" value="" title="Masukkan bagian dari pegawai yang ingin diubah :&#013;-Kosong&#40; &#41; untuk Kepala Biro dan Kepala Bagian&#013;-bagian :&#013;  *pembinaan dan kesejahteraan pegawai&#013;  *pengembangan kompetensi pegawai&#013;  *tata usaha kepegawaian"></td>
			                                </tr>
			                                <tr>
			                                	<td><label>Jabatan</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><textarea style="width: 100%" type="text" id="inputJabatan" name="jabatan" value="" form="FormEA" title="Jabatan dari pegawai yang ingin diubah"></textarea></td>
			                                </tr>
			                                <tr>
			                                	<td><label>Password</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input style="width: 100%" type="text" id="inputPassword" name="password" value="" title="Password dari akun pegawai yang ingin diubah"></td>
			                                </tr>
			                                <tr>
			                                	<td><label>Level</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input type="text" name="level" id="inputLevel" value="" onkeypress='return event.charCode >= 48 && event.charCode <= 57' title="Masukkan level dari pegawai yang ingin diubah :&#013;1 &#40;level untuk staff &#41;&#013;2 &#40;level untuk kepala Sub-Bagian &#41; &#013;3 &#40;level untuk Kepala Bagian &#41; &#013;4 &#40;level untuk Kepala Biro &#41;"></td>
			                                </tr>
			                                <tr>
			                                    <td colspan="5" align="right" style="height: 40px; padding: 10px; padding-top: 20px"><a name="EASubmit" class="EAbtnSubmit" onclick="validateEA()" title="Simpan Perubahan">Submit</a></td>
			                                </tr>
			                        </table>
			                    </form>
			                </div>
			            </div>
			            <div id="ModalDJB" class="tCmodal">
			                <div class="tCmodal-content">
			                    <span class="DJBclose">&times;</span>
			                    <div id="tCModalLabel">Tambah account</div>
			                    <form name="FormDJB" id="FormDJB" method="post" action="">
			                        <table border="0" cellpadding="8" cellspacing="0" width="650" align="center" class="tableDJB">
			                                <tr>
			                                	<td><label>NIP</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input style="width: 100%" type="text" id="inputNip" name="nip" value="" title="Masukkan NIP Lama pegawai yang ingin ditambahkan"></td>
			                                </tr>
			                                <tr>
			                                	<td><label>NIP Baru</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input style="width: 100%" type="text" id="inputNipB" name="nipbaru" value="" title="Masukkan NIP Baru pegawai yang ingin ditambahkan"></td>
			                                </tr>
			                                <tr>
			                                	<td><label>Nama</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input style="width: 100%" type="text" id="inputNama" name="nama" value="" title="Masukkan nama pegawai yang ingin ditambahkan"></td>
			                                </tr>
			                                <tr>
			                                	<td><label>Bagian</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input style="width: 100%" type="text" id="inputBagian" name="bagian" value="" title="Masukkan bagian dari pegawai yang ingin ditambahkan :&#013;-Kosong&#40; &#41; untuk Kepala Biro dan Kepala Bagian&#013;-bagian :&#013;  *pembinaan dan kesejahteraan pegawai&#013;  *pengembangan kompetensi pegawai&#013;  *tata usaha kepegawaian"></td>
			                                </tr>
			                                <tr>
			                                	<td><label>Jabatan</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><textarea style="width: 100%" type="text" id="inputJabatan" name="jabatan" value="" form="FormTA" title="Jabatan dari pegawai yang ingin ditambahkan"></textarea></td>
			                                </tr>
			                                <tr>
			                                	<td><label>Password</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input style="width: 100%" type="text" id="inputPassword" name="password" value="" title="Jabatan dari pegawai yang ingin ditambahkan"></td>
			                                </tr>
			                                <tr>
			                                	<td><label>Level</label></td>
			                                	<td>:</td>
			                                    <td colspan="3"><input type="text" name="level" id="inputLevel" value="" onkeypress='return event.charCode >= 48 && event.charCode <= 57' title="Masukkan level dari pegawai yang ingin ditambahkan :&#013;1 &#40;level untuk staff &#41;&#013;2 &#40;level untuk kepala Sub-Bagian &#41; &#013;3 &#40;level untuk Kepala Bagian &#41; &#013;4 &#40;level untuk Kepala Biro &#41;"></td>
			                                </tr>
			                                <tr>
			                                    <td colspan="5" align="right" style="height: 40px; padding: 10px; padding-top: 20px"><a name="TASubmit" class="TAbtnSubmit" onclick="validateTA()" title="Tambah Account Pegawai">Submit</a></td>
			                                </tr>
			                        </table>
			                    </form>
			                </div>
			            </div>
					</div>
				</div>
	
<?php
   session_start();
   include("config.php");

    if ($_SESSION['timeout'] + 20 * 60 < time()){
      echo "<script>alert(\"Waktu session anda sudah habis, silahkan login kembali.\")</script>";
      session_unset();
      session_destroy();
    } else {
      $_SESSION['timeout'] = time();
    }

   function Redirect($url, $permanent = false)
   {
       header('Location: ' . $url, true, $permanent ? 301 : 302);
       exit();
   }
   	function days_in_month($month, $year){
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); 
	} 

   function getTglSubmit()
   {
      include("config.php");
      $tglsubmit = strtotime(date('Y-m-\5'));

      $HLsql = "SELECT * FROM hari_libur ORDER BY end_date";
      $HLresult = mysqli_query($db,$HLsql);
      while($data = mysqli_fetch_row($HLresult)){
        $start = strtotime($data[2]);
        $end = strtotime($data[3]);
        //echo '<script>console.log(new Date('.$end.'*1000))</script>';
        //echo '<script>console.log(new Date('.$tglsubmit.'*1000))</script>';
        //echo '<script>console.log(\'===============\')</script>';
        if($tglsubmit >= $start && $tglsubmit <= $end){
          $tglsubmit = strtotime('+1 day', $end);
          //echo '<script>console.log(new Date('.$tglsubmit.'*1000))</script>';
        }
        $mon = date("D", $tglsubmit);
        if($mon == "Mon"){
          $tglsubmit = strtotime('+1 day', $tglsubmit);
        }
      }
      //$test = date("Ymd", $tglsubmit);
      //echo '<script>console.log("'.$test.'")</script>';
      $_SESSION['tglsubmit'] = $tglsubmit;
      return date("Y-m-d", $tglsubmit);
   }
   $nip = $_SESSION['nip'];

   if (isset($_SESSION['nip'])){

      $today = date('Y-m-d');
      $month = date('m');
      $month2 = date('m')-1;
      $tglSubmit = getTglSubmit();
      if($today >= $tglSubmit){
        $sql = "UPDATE jurnal SET tanggal_kirim = '$today', status_jurnal = 'terkirim' WHERE month(tanggal_simpan) < '$month' AND validasi = 1";
        mysqli_query($db,$sql);
      } else {
        $sql = "UPDATE jurnal SET tanggal_kirim = '$today', status_jurnal = 'terkirim' WHERE month(tanggal_simpan) < '$month2' AND validasi = 1";
        mysqli_query($db,$sql);
      }

      //echo '<script>alert(new Date('.$_SESSION['tglsubmit'].'*1000))</script>';

      $nip = $_SESSION['nip'];
      $nipb = $_SESSION['nipb'];
      $level = $_SESSION['level'];
      $nama = $_SESSION['nama'];    
      $eselon= $_SESSION['eselon'];
      $idjabatan = $_SESSION['idjabatan'];
      $jabatan = $_SESSION['jabatan'];

      // Activity List
      $ALsql = "SELECT k.id_kategori,a.id_aktivitas, a.nama_aktivitas, a.durasi, k.nama_kategori FROM aktivitas AS a LEFT JOIN kategori AS k ON a.id_kategori = k.id_kategori";
      $ALquery = mysqli_query($db,$ALsql);
       
      $ALsql2 = "SELECT a.id_aktivitas, a.nama_aktivitas, a.durasi, k.nama_kategori FROM aktivitas AS a LEFT JOIN kategori AS k ON a.id_kategori = k.id_kategori WHERE k.nama_kategori != 'izin harian'";
      $ALsql3 = "SELECT a.id_aktivitas, a.nama_aktivitas, a.durasi, k.nama_kategori FROM aktivitas AS a LEFT JOIN kategori AS k ON a.id_kategori = k.id_kategori";
      $ALquery2 = mysqli_query($db,$ALsql2);
      $ALquery3 = mysqli_query($db,$ALsql3);
      // Category
      $Catsql = "SELECT * FROM kategori";
      $Catquery = mysqli_query($db,$Catsql);
      $Catquery2 = mysqli_query($db,$Catsql);
      $Catquery3 = mysqli_query($db,$Catsql);
      $Catquery4 = mysqli_query($db,$Catsql);
      $Catquery5 = mysqli_query($db,$Catsql);
      $Catquery6 = mysqli_query($db,$Catsql);
      $Catquery7 = mysqli_query($db,$Catsql);
      //melihat aktivitas yang diajukan pegawai yang bersangkutan
      $Sqlajuan = "SELECT id_ajuan, ajuan_aktivitas.id_kategori, nama_aktivitas, durasi, nip_pengaju, tanggal_ajuan,kategori.nama_kategori,status_ajuan FROM ajuan_aktivitas,kategori WHERE nip_pengaju=180003512 AND kategori.id_kategori=ajuan_aktivitas.id_kategori";
      
      //melihat semua aktivitas yang diajukan
      $Sqlajuanadmin = "SELECT id_ajuan, ajuan_aktivitas.id_kategori, nama_aktivitas, durasi, user.nama_pegawai, tanggal_ajuan,kategori.nama_kategori,status_ajuan FROM ajuan_aktivitas,kategori,user WHERE kategori.id_kategori=ajuan_aktivitas.id_kategori AND user.nip=ajuan_aktivitas.nip_pengaju";
      $daftarajuanadmin = mysqli_query($db,$Sqlajuanadmin);
      // Semua Pegawai
      $ALLsql = "SELECT user.nip,user.nama_pegawai,a.nama_jabatan as jabatan ,b.nama_jabatan as atasan,user.password FROM user,jabatan as a, jabatan as b WHERE user.level < 99 AND user.id_jabatan=a.id_jabatan AND a.atasan=b.id_jabatan ORDER BY user.nama_pegawai";
      $ALLquery = mysqli_query($db,$ALLsql);
      // Daftar Pegawai
      $DPsql = "SELECT * FROM user WHERE user.level < '$level' ORDER BY user.nama_pegawai";
      $DPquery = mysqli_query($db,$DPsql);
      // Jurnal Staff
      $LJstaffsql = "SELECT j.id_jurnal, j.volume, j.jenis_output, j.waktu_mulai, j.waktu_selesai, j.tanggal_jurnal, j.jenis_aktivitas, a.nama_aktivitas, a.id_kategori, k.nama_kategori FROM jurnal as j LEFT JOIN aktivitas as a ON a.id_aktivitas = j.id_aktivitas LEFT JOIN kategori as k ON k.id_kategori = a.id_kategori WHERE j.nip = '$nip'";
      $LJstaffquery = mysqli_query($db, $LJstaffsql);

      $BiroSql = "SELECT * FROM jabatan WHERE eselon = 2";
      $BiroQuery = mysqli_query($db, $BiroSql);

      if(count($_POST)>0) {
         if(!empty($_POST['tcm_idAct'])){
            $id = $_POST['tcm_idAct'];
            $vol = $_POST['volume'];
            $voltype = $_POST['volumeType'];
            $mulai = $_POST['tglMulai'] .' '. $_POST['jamMulai'] . ':00';
            $selesai = $_POST['tglSelesai'] .' '. $_POST['jamSelesai'] . ':00';
            $tgljurnal = date("Y-m-d");
            $acttype = $_POST['actType'];
            $ket = $_POST['SJketerangan'];
            $SJsql = "INSERT INTO jurnal(`id_aktivitas`, `nip`, `volume`, `jenis_output`, `waktu_mulai`, `waktu_selesai`, `tanggal_simpan`, `status_jurnal`, `jenis_aktivitas`, `keterangan`)  
                        VALUES ('$id','$nip','$vol','$voltype','$mulai','$selesai','$tgljurnal','draft','$acttype','$ket')";
            mysqli_query($db,$SJsql);
         } else if(!empty($_POST['EIJ_idAct'])){
         	$id = $_POST['EIJ_idAct'];
            $vol = $_POST['EIJvolume'];
            $voltype = $_POST['EIJvolumeType'];
            $mulai = $_POST['EIJtglMulai'] .' '. $_POST['EIJjamMulai'] . ':00';
            $selesai = $_POST['EIJtglSelesai'] .' '. $_POST['EIJjamSelesai'] . ':00';
            $tgljurnal = date("Y-m-d");
            $acttype = $_POST['EIJactType'];
            $ket = $_POST['EIJketerangan'];
            $SJsql = "INSERT INTO jurnal(`id_aktivitas`, `nip`, `volume`, `jenis_output`, `waktu_mulai`, `waktu_selesai`, `tanggal_simpan`, `status_jurnal`, `jenis_aktivitas`, `keterangan`)  
                        VALUES ('$id','$nip','$vol','$voltype','$mulai','$selesai','$tgljurnal','draft','$acttype','$ket')";
            mysqli_query($db,$SJsql);
         } else if( !empty($_POST['password_baru'])){
              $nip = $_SESSION['nip'];
              $password = $_POST['password_baru'];
              $pass_update = ("UPDATE user SET password='$password' WHERE nip = '$nip'");
              mysqli_query($db,$pass_update);
         } else if( !empty($_POST['aktivitas'])){
            $aktivitas = $_POST['aktivitas'];
            $kategori = $_POST['kategori'];
            $durasi = $_POST['durasi'];
            $insertact = "INSERT INTO aktivitas(`id_kategori`, `nama_aktivitas`, `durasi`) VALUES ('$kategori','$aktivitas','$durasi')";
            mysqli_query($db,$insertact);
         } else if( !empty($_POST['id_aktivitas'])){
            $aktivitas = $_POST['inputaktivitas'];
            $idaktivitas = $_POST['id_aktivitas'];
            $kategori = $_POST['input_idkategori'];
            $durasi = $_POST['inputdurasi'];
            $updateact = "update aktivitas SET id_kategori='$kategori', nama_aktivitas='$aktivitas' , durasi='$durasi' WHERE id_aktivitas ='$idaktivitas'" ;
            mysqli_query($db,$updateact);
         } else if( !empty($_POST['aktivitas_ajuan'])){
            $aktivitas = $_POST['aktivitas_ajuan'];
            $kategori = $_POST['kategori_ajuan'];
            $durasi = $_POST['durasi_ajuan'];
            $status_ajuan = $_POST['status_ajuan'];
            $tanggal_ajuan = $_POST['tanggal_ajuan'];
            $nip=$nip;
            $insertajuan = "INSERT INTO ajuan_aktivitas(id_kategori, nama_aktivitas, durasi,nip_pengaju,tanggal_ajuan,status_ajuan) VALUES ('$kategori','$aktivitas','$durasi','$nip','$tanggal_ajuan','$status_ajuan')";
            mysqli_query($db,$insertajuan);
         } else if( !empty($_POST['id_ajuan'])){
            $aktivitas = $_POST['inputaktivitasajuan'];
            $idajuan = $_POST['id_ajuan'];
            $kategori = $_POST['input_idkategoriajuan'];
            $durasi = $_POST['inputdurasiajuan'];
            $status_ajuan = $_POST['status_ajuan'];
            $updateajuan = "update ajuan_aktivitas SET id_kategori='$kategori', nama_aktivitas='$aktivitas' , durasi='$durasi',status_ajuan='$status_ajuan' WHERE id_ajuan ='$idajuan'" ;
            mysqli_query($db,$updateajuan);
         }
        Redirect('index.php');
        }
      
?>
<!DOCTYPE HTML>
<html>
   <head>
   <meta charset="utf-8">
   <title>E-Jurnal Setwapres</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="css/css.css">
   <link type="text/css" rel="stylesheet" href="css/calendarstyle.css"/>    
   <link rel="stylesheet" type="text/css" href="css/oswald.css">
   <link rel="stylesheet" type="text/css" href="css/opensanss.css">
   <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
   <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
   <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="dist/bootstrap-clockpicker.min.css">
   <link rel="stylesheet" type="text/css" href="css/bootstrap-year-calendar.min.css">
   <link rel="stylesheet" type="text/css" href="css/profile.css">
   <link rel="stylesheet" type="text/css" href="css/pure.css">
   <link rel="stylesheet" type="text/css" href="css/style.css">
   <link rel="stylesheet" type="text/css" href="css/a.css">
	
   <script type="text/javascript" src="assets/js/jquery.min.js"></script>
   <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="dist/bootstrap-clockpicker.min.js"></script>
   <script type="text/javascript" src="js/jquery-ui.js"></script>
   <script type="text/javascript" src="js/FileSaver/FileSaver.min.js"></script>
   <script type="text/javascript" src="js/js-xlsx/xlsx.core.min.js"></script>
   <script type="text/javascript" src="js/jsPDF/jspdf.min.js"></script>
   <script type="text/javascript" src="js/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
   <script type="text/javascript" src="js/html2canvas/html2canvas.min.js"></script>
   <script type="text/javascript" src="js/tableExport.min.js"></script>
   <script type="text/javascript" src="js/weekPicker.js"></script>
   <script type="text/javascript" src="js/bootstrap-year-calendar.min.js"></script>
   <script type="text/javascript" src="js/moment.js"></script>
   <script type="text/javascript" src="js/combodate.js"></script>
   <script type="text/javascript" src="js/jquery.tablesorter.js"></script>
    
   </head>
   <body class="background">
      <div class="loadingpage" id="loadingpage">
        <div class="LPwrapper">
          <div class="loadingIcon">
          </div>
          <div class="loadingIcon2">
          </div>
        </div>
      </div>
      <div class="page" id="page">
          <input type="hidden" id="selectedTab" value="<?php echo $_SESSION['tab']; ?>"/>
          <input type="hidden" id="userNip" value="<?php echo $_SESSION['nip']; ?>"/>
         <?php
            if ($eselon == '5'){
                include_once "functions_staff.php";
                include_once "views/staf/home_staff.php";
            } else if ($level >= '99') {
                include_once "functions.php";
                include_once "views/adminWeb/home.php";
            } else {
                include_once "functions.php";
                include_once "views/admin/home.php";
            }
         ?>
         <script type="text/javascript" src="js/scripts.js"></script>
         <script type="text/javascript">
            var modal = document.getElementById('tCModal');
            var namaAct = document.getElementById('tcmNamaAct');
            var durasiAct = document.getElementById('tcmDurasi');
            var namaCat = document.getElementById('tcmNamaCat');
            var idInput = document.getElementsByClassName('tcm_idAct')[0];
            var span = document.getElementsByClassName("close")[0];
            var ddc = document.getElementById("ddcContent");
            var pass_select = document.getElementById('pass_select');
            var tutup = document.getElementsByClassName("tutup")[0];
            var detail_select = document.getElementById('detail_select');
            var staff_detail_select = document.getElementById('staff_detail_select');
            var staff_tutup_detail = document.getElementsByClassName("staff_tutup_detail")[0];
            var tutup_detail = document.getElementsByClassName("tutup_detail")[0];
            var tutupLJ = document.getElementsByClassName("tutupLJ")[0];
            var modalLJ = document.getElementById('modalLJ');
            var closeEA = document.getElementsByClassName("EAclose")[0];
            var modalEA = document.getElementById('ModalEA');
            var closeDJS = document.getElementsByClassName("DJSclose")[0];
            var modalDJS = document.getElementById('modalDJS');
            var closeDJS2 = document.getElementsByClassName("DJS2close")[0];
            var modalDJS2 = document.getElementById('modalDJS2');
            var foto_select = document.getElementById('foto_select');
            var foto_tutup = document.getElementsByClassName("foto_tutup")[0];
            var ModalTA = document.getElementById("ModalTA");
            var closeTA = document.getElementsByClassName("TAclose")[0];
            var modalKal = document.getElementById("ModalKal");
            var closeKal = document.getElementsByClassName("Kalclose")[0];
            var modalactajuan = document.getElementById("ModalActajuan");
            var closeActajuan = document.getElementsByClassName("Actcloseajuan")[0];
            var modalact = document.getElementById("ModalAct");
            var closeAct = document.getElementsByClassName("Actclose")[0];
            var modalEact = document.getElementById("ModalEact");
            var closeEAct = document.getElementsByClassName("EActclose")[0];
            var modalEactajuan = document.getElementById("ModalEactajuan");
            var closeEActajuan = document.getElementsByClassName("EActcloseajuan")[0];
            var modalTJ = document.getElementById("ModalTJ");
            var closeTJ = document.getElementsByClassName("TJclose")[0];
            var modalEJ = document.getElementById("ModalEJ");
            var closeEJ = document.getElementsByClassName("EJclose")[0];
            var lihat_pegawai = document.getElementById('EJBlihat_pegawai');
            var tutup_lihat = document.getElementsByClassName("EJBtutup_lihat")[0];
            var modalVJ = document.getElementById("modalVJ");
            var closeVJ = document.getElementsByClassName("VJclose")[0];
            var modalEVJ = document.getElementById("modalEVJ");
            var closeEVJ = document.getElementsByClassName("EVJclose")[0];
            var modalEIJ = document.getElementById("modalEIJ");
            var closeEIJ = document.getElementsByClassName("EIJclose")[0];
            var modalEIJ2 = document.getElementById("modalEIJ2");
            var closeEIJ2 = document.getElementsByClassName("EIJ2close")[0];
             
            span.onclick = function() {
                modal.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
            }
            tutup.onclick = function() {
                pass_select.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
            }
            
            if(typeof foto_tutup != 'undefined'){
              foto_tutup.onclick = function() {
                  foto_select.style.display = "none";
                  document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
             
            if(typeof staff_tutup_detail != 'undefined'){
              staff_tutup_detail.onclick = function() {
                  staff_detail_select.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            
            if(typeof tutup_detail != 'undefined'){   
                tutup_detail.onclick = function() {
                  detail_select.style.display = "none";
                  document.getElementsByTagName("body")[0].style.overflow = "";
                }
            }
            if(typeof tutup_lihat != 'undefined'){   
                tutup_lihat.onclick = function() {
                  lihat_pegawai.style.display = "none";
                  document.getElementsByTagName("body")[0].style.overflow = "";
                }
            }
            if ( typeof tutupLJ != 'undefined' ){
              tutupLJ.onclick = function() {
                modalLJ.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeEA != 'undefined' ){
              closeEA.onclick = function() {
                modalEA.style.display = "none";
              }
            }
            if ( typeof closeDJS != 'undefined' ){
              closeDJS.onclick = function() {
                modalDJS.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeDJS2 != 'undefined' ){
              closeDJS2.onclick = function() {
                modalDJS2.style.display = "none";
              }
            }
            if ( typeof closeTA != 'undefined' ){
              closeTA.onclick = function() {
                ModalTA.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeKal != 'undefined' ){
              closeKal.onclick = function() {
                modalKal.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
             
            if ( typeof closeAct != 'undefined' ){
              closeAct.onclick = function() {
                modalact.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }

            if ( typeof closeActajuan != 'undefined' ){
              closeActajuan.onclick = function() {
                modalactajuan.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeEAct != 'undefined' ){
              closeEAct.onclick = function() {
                modalEact.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeEActajuan != 'undefined' ){
              closeEActajuan.onclick = function() {
                modalEactajuan.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeTJ != 'undefined' ){
              closeTJ.onclick = function() {
                modalTJ.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeEJ != 'undefined' ){
              closeEJ.onclick = function() {
                modalEJ.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeVJ != 'undefined' ){
              closeVJ.onclick = function() {
                modalVJ.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeEVJ != 'undefined' ){
              closeEVJ.onclick = function() {
                modalEVJ.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeEIJ != 'undefined' ){
              closeEIJ.onclick = function() {
                modalEIJ.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            if ( typeof closeEIJ2 != 'undefined' ){
              closeEIJ2.onclick = function() {
                modalEIJ2.style.display = "none";
                document.getElementsByTagName("body")[0].style.overflow = "";
              }
            }
            
            
            window.onclick = function(event){
                var detail_select2 = document.getElementById('detail_select');
                var tutup_detail2 = document.getElementsByClassName("tutup_detail")[0];
                if(event.target == modal || event.target == modalLJ || event.target == pass_select || event.target == detail_select || event.target == staff_detail_select || event.target == modalEA || event.target == modalDJS || event.target == modalDJS2 || event.target == foto_select || event.target == ModalTA || event.target == modalact || event.target == modalactajuan || event.target == modalEact || event.target == modalEactajuan || event.target == modalKal || event.target == detail_select2 || event.target == tutup_detail2 || event.target == modalTJ || event.target == modalEJ || event.target == lihat_pegawai || event.target == modalVJ || event.target == modalEVJ || event.target == modalEIJ || event.target == modalEIJ2 ){
                    modal.style.display = "none";
                    pass_select.style.display = "none";

                    if(foto_select){
                      foto_select.style.display = "none";
                    }
                    if (detail_select){
                      detail_select.style.display = "none";
                    }
                    if(staff_detail_select){
                      staff_detail_select.style.display = "none";
                    }
                    if(modalLJ){
                      modalLJ.style.display = "none";
                    }
                    if(modalEA){
                      modalEA.style.display = "none";
                    }
                    if(ModalTA){
                      ModalTA.style.display = "none";
                    }
                    if(modalact){
                      modalact.style.display = "none";
                    }
                    if(modalactajuan){
                      modalactajuan.style.display = "none";
                    }
                    if(modalEact){
                      modalEact.style.display = "none";
                    }
                    if(modalEactajuan){
                      modalEactajuan.style.display = "none";
                    }
                    if(modalTJ){
                      modalTJ.style.display = "none";
                    }
                    if(modalEJ){
                      modalEJ.style.display = "none";
                    }
                    if(lihat_pegawai){
                      lihat_pegawai.style.display = "none";
                    }
                    if(modalVJ){
                      modalVJ.style.display = "none";
                    }
                    if(modalEVJ){
                      modalEVJ.style.display = "none";
                    }
                    if(modalEIJ){
                      modalEIJ.style.display = "none";
                    }
                    if(modalEIJ2){
                      modalEIJ2.style.display = "none";
                    }
                    document.getElementsByTagName("body")[0].style.overflow = "";
                    if(modalKal){
                      if(!detail_select2 || detail_select2.style.display != "block"){
                        modalKal.style.display = "none";
                      } else {
                        detail_select2.style.display = "none";
                        document.getElementsByTagName("body")[0].style.overflow = "hidden";
                      }
                    } else {
                      if (detail_select2){
                        detail_select2.style.display = "none";
                      }
                    }
                    if(modalDJS){
                      if(modalDJS2.style.display != "block"){
                        modalDJS.style.display = "none";
                      } else {
                        modalDJS2.style.display = "none";
                        document.getElementsByTagName("body")[0].style.overflow = "hidden";
                      }
                    }
                    
                }else if (!event.target.matches('.dropbtn')){
                    var ddc = document.getElementById("ddcContent");
                    var rep = document.getElementById("repContent");
                    var aju = document.getElementById("ajuContent");
                    var vj = document.getElementById("vjContent");
                    var fil = document.getElementById("filContent");
                    var djs = document.getElementById("djsContent");
                    var pac = document.getElementById("pacContent");
                    var ej = document.getElementById("EJContent");
                    var eij = document.getElementById("EIJ2Content");
                    if ( ddc.classList.contains("show")){
                        ddc.classList.toggle("show");
                    }
                    if (rep){
                      if ( rep.classList.contains("show")){
                          rep.classList.toggle("show");
                      }
                    }
                    if (fil){
                      if ( fil.classList.contains("show")){
                          fil.classList.toggle("show");
                      }
                    }
                    if (djs){
                      if ( djs.classList.contains("show")){
                          djs.classList.toggle("show");
                      }
                    }
                    if (pac){
                      if ( pac.classList.contains("show")){
                          pac.classList.toggle("show");
                      }
                    }
                    if (ej){
                      if ( ej.classList.contains("show")){
                          ej.classList.toggle("show");
                      }
                    }
                    if (vj){
                      if ( vj.classList.contains("show")){
                          vj.classList.toggle("show");
                      }
                    }
                    if (aju){
                      if ( aju.classList.contains("show")){
                          aju.classList.toggle("show");
                      }
                    }
                    if (eij){
                      if ( eij.classList.contains("show")){
                          eij.classList.toggle("show");
                      }
                    }
                }
                if(!event.target.matches('.ratingBtn')){
                  if(document.getElementsByClassName('ratingDiv')[0]){
                    var x = document.getElementsByClassName("ratingDiv");
                    var y = document.getElementsByClassName("ratingBtn");
                    for (var i = 0; i < x.length; i++) {
                        x[i].style.display = "none";
                    }
                    for (var i = 0; i < y.length; i++) {
                        y[i].style.display = "";
                    }
                  }
                }
            }
            function select_file(){
    			    document.getElementById('image').click();
    			    return false;
    		    }
            var ubah = document.querySelectorAll('.tombol_ubah')
            var ubah_ubah = document.querySelectorAll('.ubah_ubah')
            var forEach = Array.prototype.forEach;
            
            forEach.call(ubah, ubah_addListener)
            
            function ubah_addListener (r, m) {
               r.addEventListener('click', function () {
                   setActive_ubah(m)
               })
            }
            function ubah_removeActive(r) {
               r.classList.remove('active')
            }
             
            function setActive_ubah(m) {
                forEach.call(ubah, ubah_removeActive)
                forEach.call(ubah_ubah, ubah_removeActive)
                ubah[m].classList.add('active')
                ubah_ubah[m].classList.add('active')
            }
             
            function selectActivity(id, nama, durasi, cat){
                document.getElementsByTagName("body")[0].style.overflow = "hidden";
                var tabel = document.getElementById("tableSJ");
                for(var i=0; i<tabel.rows.length;i++ ){tabel.rows[i].style.display = ""; vt = "";}
                modal.style.display = "block";
                namaAct.innerHTML = nama;
                durasiAct.innerHTML = durasi;
                namaCat.innerHTML = cat;
                idInput.value = id;
                if(cat == "izin harian"){
                  tabel.rows[2].style.display = "none";
                  tabel.rows[4].style.display = "none";
                  tabel.rows[5].style.display = "none";
                  tabel.rows[7].style.display = "none";
                  tabel.rows[10].style.display = "none";
                  tabel.rows[11].style.display = "none";
                  document.getElementById("volumeType").value = "-";
                  document.getElementById("tanggal").style.width = "190px";
                  //document.getElementById("tglMulai").style.display = "";
                  //document.getElementById("tglSelesai").style.display = "";
                  document.getElementById("jamMulai").value = "00:00";
                  document.getElementById("jamSelesai").value = "23:59";
                  document.getElementById("iconJamMulai").style.display = "none";
                  document.getElementById("iconJamSelesai").style.display = "none";
                  document.getElementById("waktuMulai").style.display = "none";
                  document.getElementById("waktuSelesai").style.display = "none";
                  document.getElementById("tanggalMulai").style.display = "";
                  document.getElementById("tanggalSelesai").style.display = "";
                  document.getElementById("jenisAktivitas").style.display = "none";
               } else {
                  document.getElementById("jenisAktivitas").style.display = "";
                  document.getElementById("tanggalMulai").style.display = "none";
                  document.getElementById("tanggalSelesai").style.display = "none";
                  document.getElementById("iconJamMulai").style.display = "";
                  document.getElementById("iconJamSelesai").style.display = "";
                  document.getElementById("tanggal").style.width = "3px";
                  document.getElementById("jam").style.width = "88px";
                  //document.getElementById("tglMulai").style.display = "none";
                  //document.getElementById("tglSelesai").style.display = "none";
                  document.getElementById("volumeType").value = "";
               }
            }
            function lihatPegawai(id_jabatan){
              if(document.getElementById("EJBlihat_pegawai")){
                document.getElementById("idJabatanDilihat").innerHTML = id_jabatan;
                $.ajax({    //create an ajax request to load_page.php
                  type: "POST",
                  url: "ajax/lihatpegawai.php",             
                  dataType: "html",   //expect html to be returned
                  data: { 'id_jabatan':id_jabatan },               
                  success: function(response){
                    if(!$.trim(response)){
                      alert("Tidak ada pegawai di jabatan ini");
                    } else {
                      document.getElementById("EJBlihat_pegawai").style.display = "block";
                      document.getElementsByTagName("body")[0].style.overflow = "hidden";
                      $("#tablelihatpegawai").html(response);
                    }
                  }
                });
              } else {
                alert("test");
              }
            }
            function detail_selectActivity(tanggal_tanggal,nip,namapegawai){
                document.getElementById("detail_select").style.display = "block";
                document.getElementById("jurnal_nama").innerHTML = namapegawai;
                document.getElementsByTagName("body")[0].style.overflow = "hidden";
                $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "ajax/detailajax.php",             
                dataType: "html",   //expect html to be returned
                data: { tanggal_tanggal:tanggal_tanggal,nip_nip:nip},               
                success: function(response){        
                    $("#tabledata").html(response);
                    if(document.getElementById("tabledata")){
                        var csv = document.getElementById("csvBtn_admin");
                        var xls = document.getElementById("xlsBtn_admin");
                        var pdf = document.getElementById("pdfBtn_admin");
                        csv.addEventListener('click', function(e){
                          $('#tabledata').tableExport({
                            type:'csv',
                            fileName: 'Jurnal-'+nip+"-"+tanggal_tanggal,
                            escape:'false'
                          });
                        });
                        xls.addEventListener('click', function(e){
                          $('#tabledata').tableExport({
                            type:'xls',
                            fileName: 'Jurnal-'+nip+"-"+tanggal_tanggal,
                            escape:'false'
                          });
                        });
                        pdf.addEventListener('click', function(e){
                          $('#tabledata').tableExport({
                            type:'pdf',
                            jspdf: {
                              orientation: 'l'
                            },
                            fileName: 'Jurnal-'+nip+"-"+tanggal_tanggal,
                            escape:'false'
                          });
                        });
                      }
                    }
              });  
            }
            function staff_detail_selectActivity( staff_tanggal_tanggal){
                document.getElementById("staff_detail_select").style.display = "block";
                document.getElementsByTagName("body")[0].style.overflow = "hidden";
                //document.getElementById("jurnal_nama").innerHTML = nama;
                $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "ajax/staff_detailajax.php",             
                dataType: "html",   //expect html to be returned
                data: { staff_tanggal_tanggal:staff_tanggal_tanggal},               
                success: function(response){   
                    $("#staff_tabledata").html(response);
                    if(document.getElementById("staff_tabledata")){
                        var csv = document.getElementById("csvBtn_staff");
                        var xls = document.getElementById("xlsBtn_staff");
                        var pdf = document.getElementById("pdfBtn_staff");
                        csv.addEventListener('click', function(e){
                          $('#staff_tabledata').tableExport({
                            type:'csv',
                            fileName: 'Jurnal-'+staff_tanggal_tanggal,
                            escape:'false'
                          });
                        });
                        xls.addEventListener('click', function(e){
                          $('#staff_tabledata').tableExport({
                            type:'xls',
                            fileName: 'Jurnal-'+staff_tanggal_tanggal,
                            escape:'false'
                          });
                        });
                        pdf.addEventListener('click', function(e){
                          $('#staff_tabledata').tableExport({
                            type:'pdf',
                            jspdf: {
                              orientation: 'l'
                            },
                            fileName: 'Jurnal-'+staff_tanggal_tanggal,
                            escape:'false'
                          });
                        });
                    }
                }
              });  
            }
             
             /*function staff_detail_selectActivity(id_jurnal, nama_aktivitas, nama_pegawai,volume,satuan_output,durasi,tanggal_mulai,bulan_mulai,tahun_mulai,tanggal_selesai,bulan_selesai,tahun_selesai,jam_mulai,jam_selesai,durasi_pekerjaan,jurnal_tanggal,jurnal_bulan,jurnal_tahun){
                console.log(id_jurnal + nama_aktivitas + nama_pegawai + volume + satuan_output + durasi + tanggal_mulai + bulan_mulai + tahun_mulai + tanggal_selesai + bulan_selesai + tahun_selesai + jam_mulai + jam_selesai + durasi_pekerjaan + jurnal_tanggal + jurnal_bulan + jurnal_tahun);
                document.getElementById("staff_detail_select").style.display = "block";
                document.getElementById("labelID").innerHTML = id_jurnal;
                document.getElementById("nama_aktiv").innerHTML = nama_aktivitas;
                document.getElementById("pembuat").innerHTML = nama_pegawai;
                document.getElementById("vol").innerHTML = volume;
                document.getElementById("satuan").innerHTML = satuan_output; 
                document.getElementById("waktu_efektif").innerHTML = durasi;
                document.getElementById("mulai_tanggal").innerHTML = tanggal_mulai;
                document.getElementById("mulai_bulan").innerHTML = bulan_mulai;
                document.getElementById("mulai_tahun").innerHTML = tahun_mulai;
                document.getElementById("selesai_tanggal").innerHTML = tanggal_selesai;
                document.getElementById("selesai_bulan").innerHTML = bulan_selesai;
                document.getElementById("selesai_tahun").innerHTML = tahun_selesai;
                document.getElementById("mulai_jam").innerHTML = jam_mulai;
                document.getElementById("selesai_jam").innerHTML = jam_selesai;
                document.getElementById("lama_kerja").innerHTML = durasi_pekerjaan;
                document.getElementById("tanggal_jurnal").innerHTML = jurnal_tanggal;
                document.getElementById("bulan_jurnal").innerHTML = jurnal_bulan;
                document.getElementById("tahun_jurnal").innerHTML = jurnal_tahun;
               
            }*/
             
              function validatepass(){
                 var password_lama = document.forms['Formpass']['password_lama'].value;
                 var password_baru = document.forms['Formpass']['password_baru'].value;
                 var password_baru_konfirmasi = document.forms['Formpass']['password_baru_konfirmasi'].value;
                 if (password_lama == "" || password_baru == "" || password_baru_konfirmasi=="")                 {
                     alert("Semua kolom harus diisi");
                 } else {
                     <?php  $passnya = "SELECT password FROM user WHERE nip = '$nip'";
                            $pass = mysqli_query($db,$passnya);
                    while($password=mysqli_fetch_array($pass)){
                     ?>
                    
                     if (password_lama == "<?php echo $password['password']; ?>"){
                         if(password_baru == password_baru_konfirmasi){
                                document.getElementById("Formpass").submit();
                                alert("Password Telah Diganti");
                            } else {
                                alert("Password Baru Tidak Sesuai dengan Konfirmasi");
                            }
                     }
                     else{
                         alert("Password Lama Tidak Sesuai");
                     }
                  <?php
                    }
                  ?>
                 }
              }
             
            function pass_selectActivity(){
                    pass_select.style.display = "block";
                    document.getElementsByTagName("body")[0].style.overflow = "hidden";
            }
            function ubah_foto(){
                    foto_select.style.display = "block";
                    document.getElementsByTagName("body")[0].style.overflow = "hidden";
            }
             
            function searchAct() {
               var input, filter, catFilter, catBtn, table, tr, td, i, showCount = 0;

               catBtn = document.getElementById("ddcBtn");
               input = document.getElementById("actSearch");
               filter = input.value.toUpperCase();
               table = document.getElementById("actListTable");
               tr = table.getElementsByTagName("tr");
               document.getElementById("actTableMessage").style.display = "";
               tr[1].style.display = "";
               document.getElementById("actCount").innerHTML = "0";

               if(catBtn.classList.contains("selectd")){
                  catFilter = ddcbtnLabel.innerHTML;
               } else {
                  catFilter = '';
               }
               for (i = 2; i < tr.length; i++){
                  td = tr[i].getElementsByTagName("td")[1];
                  if(td){
                     tr[i].style.display = "none";
                  }
               }
               if(filter != '' && catFilter != ''){
                  for (i = 2; i < tr.length; i++) {
                     td = tr[i].getElementsByTagName("td")[1];
                     tdcat = tr[i].getElementsByTagName("td")[3];
                     if(td){
                        if(td.innerHTML.toUpperCase().indexOf(filter) > -1 && tdcat.innerHTML.indexOf(catFilter) > -1){
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
                  console.log(showCount);
                  if(catFilter == 'izin harian'){
                    document.getElementById("headerStandarWaktu").style.display = "none";
                  } else {
                    document.getElementById("headerStandarWaktu").style.display = "";
                  }
               } else if(filter != ''){
                  for (i = 2; i < tr.length; i++) {
                     td = tr[i].getElementsByTagName("td")[1];
                     if(td){
                        if(td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
               } else if( catFilter != ''){
                  for (i = 2; i < tr.length; i++) {
                     tdcat = tr[i].getElementsByTagName("td")[3];
                     if(tdcat){
                        if(tdcat.innerHTML.indexOf(catFilter) > -1) {
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
                  if(document.getElementById("headerStandarWaktu")){
                    if(catFilter == 'izin harian'){
                      document.getElementById("headerStandarWaktu").style.display = "none";
                    } else {
                      document.getElementById("headerStandarWaktu").style.display = "";
                    }
                  }
               }
               document.getElementById("actCount").innerHTML = showCount;
                if( showCount <= 0 ){
                  if( catFilter == 'Pilih Kategori' && filter == ''){
                      document.getElementById("actTableMessage").innerHTML = "Mulai pencarian dengan mengetik pada kolom search atau pilih kategori";
                  } else if( filter != '' || catFilter != ''){
                     document.getElementById("actTableMessage").innerHTML = "No Result";
                  } else {
                     document.getElementById("actTableMessage").innerHTML = "Mulai pencarian dengan mengetik pada kolom search atau pilih kategori";
                  }
                  if(document.getElementById("btn-toolbar")){
                    document.getElementById("btn-toolbar").style.display = "none";
                  }
                } else {
                  if(document.getElementById("btn-toolbar")){
                    document.getElementById("btn-toolbar").style.display = "";
                  }
                  document.getElementById("actTableMessage").style.display = "none";
                  tr[1].style.display = "none";
                }
            }

            function printTabel(type){
              var cat = ddcbtnLabel.innerHTML;
              if(cat == "Pilih Kategori"){
                cat == "Semua Kategori";
              }
              if(document.getElementById("csvBtn_activity")){
                if(type == 'csv'){
                  $('#actListTable').tableExport({
                    type:'csv',
                    fileName: 'Aktivitas dengan Kategori: '+cat,
                    escape:'false'
                  });
                } else if( type == 'xls'){
                  $('#actListTable').tableExport({
                    type:'xls',
                    fileName: 'Aktivitas dengan Kategori: '+cat,
                     escape:'false'
                  });
                } else {
                  $('#actListTable').tableExport({
                    type:'pdf',
                    jspdf: {
                      orientation: 'l'
                    },
                    fileName: 'Aktivitas dengan Kategori: '+cat,
                  escape:'false'
                  });
                }
              }
            }

            function searchAct2() {
               var input, filter, catFilter, catBtn, table, tr, td, i, showCount = 0;
               catBtn = document.getElementById("pacBtn");
               input = document.getElementById("pacSearch");
               filter = input.value.toUpperCase();
               table = document.getElementById("pacListTable");
               tr = table.getElementsByTagName("tr");

               if(catBtn.classList.contains("selectd")){
                  catFilter = document.getElementById("pacbtnLabel").innerHTML;
               } else {
                  catFilter = '';
               }
               for (i = 2; i < tr.length; i++){
                  td = tr[i].getElementsByTagName("td")[1];
                  if(td){
                     tr[i].style.display = "none";
                  }
               }
               if(filter != '' && catFilter != ''){
                  for (i = 2; i < tr.length; i++) {
                     td = tr[i].getElementsByTagName("td")[1];
                     tdcat = tr[i].getElementsByTagName("td")[3];
                     if(td){
                        if(td.innerHTML.toUpperCase().indexOf(filter) > -1 && tdcat.innerHTML.indexOf(catFilter) > -1){
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
                  if(catFilter == 'izin harian'){
                    document.getElementById("DJSheaderStandarWaktu").style.display = "none";
                  } else {
                    document.getElementById("DJSheaderStandarWaktu").style.display = "";
                  }
               } else if(filter != ''){
                  for (i = 2; i < tr.length; i++) {
                     td = tr[i].getElementsByTagName("td")[1];
                     if(td){
                        if(td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
               } else if( catFilter != ''){
                  for (i = 2; i < tr.length; i++) {
                     tdcat = tr[i].getElementsByTagName("td")[3];
                     if(tdcat){
                        if(tdcat.innerHTML.indexOf(catFilter) > -1) {
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
               }

               document.getElementById("pacCount").innerHTML = showCount;
               if( showCount <= 0 ){
                  tr[1].style.display = "";
                  if( filter != '' || catFilter != ''){
                     document.getElementById("pacTableMessage").innerHTML = "No Result";
                  } else {
                     document.getElementById("pacTableMessage").innerHTML = "Mulai pencarian dengan mengetik pada kolom search atau pilih kategori";
                  }
               } else {
                  tr[1].style.display = "none";
               }
            }
            function searchAct3() {
               var input, filter, catFilter, catBtn, table, tr, td, i, showCount = 0;
               catBtn = document.getElementById("ajuBtn");
               input = document.getElementById("ajuSearch");
               filter = input.value.toUpperCase();
               table = document.getElementById("ajuListTable");
               tr = table.getElementsByTagName("tr");

               if(catBtn.classList.contains("selectd")){
                  catFilter = document.getElementById("ajubtnLabel").innerHTML;
               } else {
                  catFilter = '';
               }
               for (i = 2; i < tr.length; i++){
                  td = tr[i].getElementsByTagName("td")[1];
                  if(td){
                     tr[i].style.display = "none";
                  }
               }
               if(filter != '' && catFilter != ''){
                  for (i = 2; i < tr.length; i++) {
                     td = tr[i].getElementsByTagName("td")[1];
                     tdcat = tr[i].getElementsByTagName("td")[3];
                     if(td){
                        if(td.innerHTML.toUpperCase().indexOf(filter) > -1 && tdcat.innerHTML.indexOf(catFilter) > -1){
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
                  if(catFilter == 'izin harian'){
                    document.getElementById("headerStandarWaktuajuan").style.display = "none";
                  } else {
                    document.getElementById("headerStandarWaktuajuan").style.display = "";
                  }
               } else if(filter != ''){
                  for (i = 2; i < tr.length; i++) {
                     td = tr[i].getElementsByTagName("td")[1];
                     if(td){
                        if(td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
               } else if( catFilter != ''){
                  for (i = 2; i < tr.length; i++) {
                     tdcat = tr[i].getElementsByTagName("td")[3];
                     if(tdcat){
                        if(tdcat.innerHTML.indexOf(catFilter) > -1) {
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
               }
               document.getElementById("actCountajuan").innerHTML = showCount;
               if( showCount <= 0 ){
                  tr[1].style.display = "";
                  if( filter != '' || catFilter != ''){
                     document.getElementById("actTableMessageajuan").innerHTML = "No Result";
                  } else {
                     document.getElementById("actTableMessageajuan").innerHTML = "Mulai pencarian dengan mengetik pada kolom search atau pilih kategori";
                  }
               } else {
                  tr[1].style.display = "none";
               }
            }

            function searchAct4() {
               var input, filter, catFilter, catBtn, table, tr, td, i, showCount = 0;
               catBtn = document.getElementById("EIJ2Btn");
               input = document.getElementById("EIJ2Search");
               filter = input.value.toUpperCase();
               table = document.getElementById("EIJ2ListTable");
               tr = table.getElementsByTagName("tr");

               if(catBtn.classList.contains("selectd")){
                  catFilter = document.getElementById("EIJ2btnLabel").innerHTML;
               } else {
                  catFilter = '';
               }
               for (i = 2; i < tr.length; i++){
                  td = tr[i].getElementsByTagName("td")[1];
                  if(td){
                     tr[i].style.display = "none";
                  }
               }
               if(filter != '' && catFilter != ''){
                  for (i = 2; i < tr.length; i++) {
                     td = tr[i].getElementsByTagName("td")[1];
                     tdcat = tr[i].getElementsByTagName("td")[3];
                     if(td){
                        if(td.innerHTML.toUpperCase().indexOf(filter) > -1 && tdcat.innerHTML.indexOf(catFilter) > -1){
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
                  if(catFilter == 'izin harian'){
                    document.getElementById("DJSheaderStandarWaktu").style.display = "none";
                  } else {
                    document.getElementById("DJSheaderStandarWaktu").style.display = "";
                  }
               } else if(filter != ''){
                  for (i = 2; i < tr.length; i++) {
                     td = tr[i].getElementsByTagName("td")[1];
                     if(td){
                        if(td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
               } else if( catFilter != ''){
                  for (i = 2; i < tr.length; i++) {
                     tdcat = tr[i].getElementsByTagName("td")[3];
                     if(tdcat){
                        if(tdcat.innerHTML.indexOf(catFilter) > -1) {
                           tr[i].style.display = "";
                           showCount++;
                        }
                     }
                  }
               }

               document.getElementById("EIJ2Count").innerHTML = showCount;
               if( showCount <= 0 ){
                  tr[1].style.display = "";
                  if( filter != '' || catFilter != ''){
                     document.getElementById("EIJ2TableMessage").innerHTML = "No Result";
                  } else {
                     document.getElementById("EIJ2TableMessage").innerHTML = "Mulai pencarian dengan mengetik pada kolom search atau pilih kategori";
                  }
               } else {
                  tr[1].style.display = "none";
               }
            }

            function searchAcc() {
               var input, filter, sbFilter, ssbBtn, table, tr, td, i, showCount = 0;
               input = document.getElementById("pegSearch");
               if(input){
                 filter = input.value.toUpperCase();
                 table = document.getElementById("epTable");
                 tr = table.getElementsByTagName("tr");

                 for (i = 2; i < tr.length; i++){
                    td = tr[i].getElementsByTagName("td")[1];
                    if(td){
                       tr[i].style.display = "";
                    }
                 }
                 if(filter != ''){
                    for (i = 2; i < tr.length; i++) {
                       td = tr[i].getElementsByTagName("td")[1];
                       if(td){
                          if(td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                             
                             tr[i].style.display = "";
                             showCount++;
                          } else {
                             tr[i].style.display = "none";
                          }
                       }
                    }
                 }

                 if( filter != ''){
                  document.getElementById("pegCount").innerHTML = showCount;
                  if( showCount <= 0){
                    tr[1].style.display = "";
                    document.getElementById("pegTableMessage").innerHTML = "No Result";
                  } else {
                    tr[1].style.display = "none";
                  }
                 } else {
                  document.getElementById("pegCount").innerHTML = tr.length;
                 }
               }
               
            }

            function selectCat(cat) {
               catBtn = document.getElementById("ddcBtn");
               label = document.getElementById("ddcbtnLabel");
               if(cat != 'Semua'){
                  catBtn.classList.add("selectd");
               } else {
                  cat = "Pilih Kategori";
                  catBtn.classList.remove("selectd");
               }
               document.getElementById("ddcContent").classList.toggle("show");
               label.innerHTML = cat;
               searchAct();
            }

            function selectCat2(cat) {
               catBtn = document.getElementById("pacBtn");
               label = document.getElementById("pacbtnLabel");
               if(cat != 'Semua'){
                  catBtn.classList.add("selectd");
               } else {
                  cat = "Pilih Kategori";
                  catBtn.classList.toggle("selectd");
               }
               document.getElementById("pacContent").classList.toggle("show");
               label.innerHTML = cat;
               searchAct2();
            }
            function selectCat3(cat) {
               catBtn = document.getElementById("ajuBtn");
               label = document.getElementById("ajubtnLabel");
               if(cat != 'Semua'){
                  catBtn.classList.add("selectd");
               } else {
                  cat = "Pilih Kategori";
                  catBtn.classList.remove("selectd");
               }
               document.getElementById("ajuContent").classList.toggle("show");
               label.innerHTML = cat;
               searchAct3();
            }
            function selectCat4(cat) {
               catBtn = document.getElementById("EIJ2Btn");
               label = document.getElementById("EIJ2btnLabel");
               if(cat != 'Semua'){
                  catBtn.classList.add("selectd");
               } else {
                  cat = "Pilih Kategori";
                  catBtn.classList.toggle("selectd");
               }
               document.getElementById("EIJ2Content").classList.toggle("show");
               label.innerHTML = cat;
               searchAct4();
            }


            
            function selectReport(rep) {
               repBtn = document.getElementById("repBtn");
               label = document.getElementById("repbtnLabel");
               if (repBtn){
                 var harian = document.getElementsByClassName("LJSfilter")[0];
                 var periode = document.getElementsByClassName("LJSfilter")[1];
                 document.getElementById("repContent").classList.toggle("show");
                 label.innerHTML = rep;

                 if (harian){
                   if( rep == 'Harian'){
                      harian.style.display = "inline-block";
                      periode.style.display = "none";
                   } else {
                      periode.style.display = "inline-block";
                      harian.style.display = "none";
                   }
                    document.getElementById("LJSfilterType").value = rep;
                 }
                 eventFire(document.getElementById("LJSbtn"), 'click');
               }
            }

            function selectDJS(t) {
               btn = document.getElementById("djsBtn");
               label = document.getElementById("djsbtnLabel");
               if (btn){
                 var harian = document.getElementsByClassName("DJSfilter")[0];
                 var periode = document.getElementsByClassName("DJSfilter")[1];
                 var bulanan = document.getElementsByClassName("DJSfilter")[2];
                 document.getElementById("djsContent").classList.remove("show");
                 if(t == "Bulanan"){
                    label.innerHTML = "Semua Jurnal" 
                 } else {
                    label.innerHTML = t;
                 }

                 if (periode){
                   if( t == 'Harian'){
                      document.getElementById("DJSbtn").style.display = "";
                      harian.style.display = "inline-block";
                      bulanan.style.display = "none";
                      periode.style.display = "none";
                   } else if( t == 'Periode'){
                      document.getElementById("DJSbtn").style.display = "";
                      periode.style.display = "inline-block";
                      bulanan.style.display = "none";
                      harian.style.display = "none";
                   } else {
                      document.getElementById("DJSbtn").style.display = "none";
                      document.getElementById("DJSfilterType").value = t;
                      eventFire(document.getElementById("DJSbtn"), 'click');
                      periode.style.display = "none";
                      harian.style.display = "none";
                   }
                    document.getElementById("DJSfilterType").value = t;
                 }
               }
            }

            function selectEch(e){
               catBtn = document.getElementById("EJBtn");
               label = document.getElementById("EJbtnLabel");
               document.getElementById("jabSearch").value = "";
               document.getElementById("EJContent").classList.toggle("show");
               if(e == 5){
                  document.getElementById("searchJabatan").style.display = "";
               } else {
                  document.getElementById("searchJabatan").style.display = "none";
               }
               $.ajax({    //create an ajax request to load_page.php
                  type: "POST",
                  url: "ajax/getListJabatan.php",             
                  dataType: "html",   //expect html to be returned
                  data: { 'kat':e},               
                  success: function(response){                    
                    $("#EJTableWrapper").html(response);
                  }
               });
            }

            function toggleChild(atasan, eselon, container, el){
              if(el){
                el.getElementsByClassName("glyphicon")[0].classList.toggle('glyphicon-plus');
                el.getElementsByClassName("glyphicon")[0].classList.toggle('glyphicon-minus');
              }
              var data = { 'atasan':atasan, 'eselon':eselon };
              if(document.getElementById(container).innerHTML == ""){
                $.ajax({    //create an ajax request to load_page.php
                  type: "POST",
                  url: "ajax/getListJabatan.php",             
                  dataType: "html",   //expect html to be returned
                  data: data,               
                  success: function(response){
                    document.getElementById(container).innerHTML = response;             
                    //$("#"+container).html(response);
                  }
                });
              } else {
                document.getElementById(container).innerHTML = "";
              }
            }

            function editJabatan(id,nama){
              document.getElementById("ModalEJ").style.display = "block";
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
              document.getElementById("EJidJabatan").value = id;
              document.getElementById("EJnamaJabatan").value = nama;
            }

            function validateEJB(){
              var id = document.forms["FormEJB"]["EJidJabatan"].value;
              var nama = document.forms["FormEJB"]["EJnamaJabatan"].value;

              if(nama != ""){
                $.ajax({    //create an ajax request to load_page.php
                  type: "POST",
                  url: "ajax/editJabatan.php",             
                  dataType: "html",   //expect html to be returned
                  data: { 'id':id, 'nama':nama},               
                  success: function(response){
                    if(response == "y"){
                      alert("Berhasil edit nama jabatan");
                      location.reload();
                    } else {
                      alert("Gagal edit nama jabatan");
                    }
                  }
                });
              } else {
                alert("Kolom tidak boleh kosong");
              }
            }

            function lihatDJS(nip){
              var bulanTahun = document.getElementById("DJSpilihBulan").value;
              var split = bulanTahun.split("-");
              var tahun = split[0];
              var bulan = split[1];
              data = { 'nip': nip, 'bulan': bulan, 'tahun': tahun };
              $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "ajax/tabelDraftStaff.php",             
                dataType: "html",   //expect html to be returned
                data: data,               
                success: function(response){                    
                    $("#tabelDJstaffContainer").html(response);
                }
              });
            }

            function editDJ(idJ,idAct,dur) {
            	updateTanggalEDJS(document.getElementById("edjsPilihBulan"),'tgl');
            	updateTanggalEDJS(document.getElementById("edjsPilihBulanMulai"),'mulai');
            	updateTanggalEDJS(document.getElementById("edjsPilihBulanSelesai"),'selesai');
               document.getElementsByTagName("body")[0].style.overflow = "hidden";
               document.getElementById("modalDJS").style.display = "block";
               var table = document.getElementById("tabelDJajax");
               for(var i=1; i<table.rows.length; i++){
                  if(table.rows[i].cells.length > 2){
                    console.log(table.rows[i].cells.length);
                    if(table.rows[i].cells[4].innerHTML == idJ){
                      var row = table.rows[i];
                      document.getElementById("EDJSidJ").value = idJ;
                      document.getElementById("EDJSidAct").value = idAct;
                      document.getElementById("edjsNamaAct").innerHTML = row.cells[5].innerHTML;
                      document.getElementById("edjsDurasi").innerHTML = dur;
                      document.getElementById("edjsNamaCat").innerHTML = row.cells[6].innerHTML;
                      document.getElementById("edjsVolume").selectedIndex = row.cells[9].innerHTML-1;
                      document.getElementById("edjsVolumeType").value = row.cells[10].innerHTML;
                      document.getElementById("edjsKeterangan").value = row.cells[13].innerHTML;
                      document.getElementById("edjsActType").value = row.cells[7].innerHTML.toLowerCase();

                      var tabelEDJS = document.getElementById("tableEDJS");
                      cat = row.cells[6].innerHTML;
                      if(cat == "izin harian"){
                        tabelEDJS.rows[3].style.display = "none";
                        tabelEDJS.rows[5].style.display = "none";
                        tabelEDJS.rows[6].style.display = "none";
                        tabelEDJS.rows[8].style.display = "none";
                        tabelEDJS.rows[13].style.display = "none";
                        document.getElementById("edjsTanggal").width = "";
                        document.getElementById("edjsTglMulai").style.display = "";
                        document.getElementById("edjsTglSelesai").style.display = "";
                        document.getElementById("btnGantiAct").style.display = "none";
                        document.getElementById("edjsTanggal").style.width = "";
                        var jurnalDateMulai = new Date(row.cells[11].innerHTML);
                        var jurnalDateSelesai = new Date(row.cells[12].innerHTML);
                        var date = new Date();
                        if(jurnalDateMulai.getMonth() == date.getMonth()){
                        	document.getElementById("edjsPilihBulanMulai").selectedIndex = 0;
                        } else {
                        	document.getElementById("edjsPilihBulanMulai").selectedIndex = 1;
                        }
                        document.getElementById("edjsPilihHariMulai").selectedIndex = jurnalDateMulai.getDate()-1;
                        if(jurnalDateSelesai.getMonth() == date.getMonth()){
                        	document.getElementById("edjsPilihBulanSelesai").selectedIndex = 0;
                        } else {
                        	document.getElementById("edjsPilihBulanSelesai").selectedIndex = 1;
                        }
                        document.getElementById("edjsPilihHariSelesai").selectedIndex = jurnalDateSelesai.getDate()-1;
                        document.getElementById("edtanggalMulai").style.display = "";
                        document.getElementById("edtanggalSelesai").style.display = "";
                        document.getElementById("edwaktuMulai").style.display = "none";
                        document.getElementById("edwaktuSelesai").style.display = "none";
                      } else {
                        tabelEDJS.rows[3].style.display = "";
                        tabelEDJS.rows[5].style.display = "";
                        tabelEDJS.rows[6].style.display = "";
                        tabelEDJS.rows[8].style.display = "";
                        tabelEDJS.rows[13].style.display = "";
                        document.getElementById("edtanggalMulai").style.display = "none";
                        document.getElementById("edtanggalSelesai").style.display = "none";
                        document.getElementById("edwaktuMulai").style.display = "";
                        document.getElementById("edwaktuSelesai").style.display = "";
                        document.getElementById("edjsTanggal").width = "1px";
                        var jurnalDate = new Date(row.cells[11].innerHTML);
                        var date = new Date();
                        if(jurnalDate.getMonth() == date.getMonth()){
                        	document.getElementById("edjsPilihBulan").selectedIndex = 0;
                        } else {
                        	document.getElementById("edjsPilihBulan").selectedIndex = 1;
                        }
                        document.getElementById("edjsPilihHari").selectedIndex = jurnalDate.getDate()-1;

                        document.getElementById("btnGantiAct").style.display = "";
                        document.getElementById("edjsJamMulai").value = row.cells[1].innerHTML;
                        document.getElementById("edjsJamSelesai").value = row.cells[2].innerHTML;
                      }
                    }
                  }
               }
            }

            function deleteDJ(idJ){
              if(confirm("Jurnal Draft dengan id " + idJ + " akan dihapus")){
                $.ajax({
                    dataType: 'html',
                    url:'ajax/deleteJurnalStaff.php',
                    method:'post',
                    data : { 'id':idJ },
                    success:function(a){
                      alert(a);
                      document.getElementById("tabelDJstaffContainer").innerHTML = "";
                      eventFire(document.getElementById("DJSbtn"), 'click');
                    }
                  });
              }
            }
            

            function DJSgantiAct() {
              document.getElementById("modalDJS2").style.display = "block";
            }

            function DJSpilihAct(id,nama,dur,cat) {
              document.getElementById("EDJSidAct").value = id;
              document.getElementById("edjsNamaAct").innerHTML = nama;
              document.getElementById("edjsDurasi").innerHTML = dur;
              document.getElementById("edjsNamaCat").innerHTML = cat;
              document.getElementById("modalDJS2").style.display = "none";
            }

            function days_in_month(month, year){
				return month == 2 ? (year % 4 ? 28 : (year % 100 ? 29 : (year % 400 ? 28 : 29))) : ((month - 1) % 7 % 2 ? 30 : 31); 
			} 

            function updateTanggalEDJS(select,type){
            	var bulanTahun = select.value;
            	var split = bulanTahun.split("-");
            	var tahun = split[0];
            	var bulan = split[1];
            	var maxDay = days_in_month(bulan,tahun);
            	if(type == 'tgl'){
            		var selectHari = document.getElementById("edjsPilihHari");
            	} else if(type == 'mulai'){
            		var selectHari = document.getElementById("edjsPilihHariMulai");
            	} else if(type == 'selesai'){
            		var selectHari = document.getElementById("edjsPilihHariSelesai");
            	}
            	selectHari.innerHTML = "";
            	for (var i = 1; i<=maxDay; i++){
				    var opt = document.createElement('option');
				    opt.value = i;
				    opt.innerHTML = i;
				    selectHari.appendChild(opt);
				}
            }

            function validateEDJ() {
               var tgl = new Date();
               var cat = document.getElementById("edjsNamaCat").innerHTML;
               var idj = document.getElementById("EDJSidJ").value;
               document.getElementById("edjsNamaCat2").value = cat;
               if( cat != "izin harian"){
               	var hari = document.getElementById("edjsPilihHari").value;
               	if(hari.length == 1){ hari = "0"+hari; }
               	 document.forms["FormDJS"]["edjsTglJurnal"].value = document.getElementById("edjsPilihBulan").value+"-"+hari;
                 var tglMulai = document.forms["FormDJS"]["edjsTglJurnal"].value;
                 var tglSelesai = document.forms["FormDJS"]["edjsTglJurnal"].value;
                 document.forms["FormDJS"]["edjsTglMulai"].value = tglMulai;
                 document.forms["FormDJS"]["edjsTglSelesai"].value = tglSelesai;
               } else {
               	var hariM = document.getElementById("edjsPilihHariMulai").value;
               	var hariS = document.getElementById("edjsPilihHariSelesai").value;
               	if(hariM.length == 1){ hariM = "0"+hariM; }
               	if(hariS.length == 1){ hariS = "0"+hariS; }
               	 document.forms["FormDJS"]["edjsTglMulai"].value = document.getElementById("edjsPilihBulanMulai").value+"-"+hariM;
               	 document.forms["FormDJS"]["edjsTglSelesai"].value = document.getElementById("edjsPilihBulanSelesai").value+"-"+hariS;
                 var tglMulai = document.forms["FormDJS"]["edjsTglMulai"].value;
                 var tglSelesai = document.forms["FormDJS"]["edjsTglSelesai"].value;
               }
               var volumetype = document.forms["FormDJS"]["edjsVolumeType"].value;
               var jamMulai = document.forms["FormDJS"]["edjsJamMulai"].value;
               var jamSelesai = document.forms["FormDJS"]["edjsJamSelesai"].value;
               var keterangan = document.forms["FormDJS"]["edjsKeterangan"].value;
               var error = 0;
               var msg;
               if (volumetype == "" || tglMulai == "" || tglSelesai == ""){
                  msg = "Semua kolom harus diisi";
                  error++;
               } else if ( tglMulai > tglSelesai){
                  msg = "Tanggal selesai tidak boleh lebih awal dari tanggal mulai";
                  error++;
               } else if ( tglMulai == tglSelesai) {
                  if ( jamMulai > jamSelesai ) {
                     msg = "Jam selesai tidak boleh lebih awal dari jam mulai di hari yang sama"
                     error++;
                  } else if(jamMulai == jamSelesai) {
                     msg = "Jam selesai tidak boleh sama dengan jam mulai di hari yang sama"
                     error++;
                  }
               }
               if ( error == 0){
                  $.ajax({
                    dataType: 'html',
                    url:'ajax/cekkonflikjurnal.php',
                    async: false,
                    method:'post',
                    data : {'edit':'true','idjurnal':idj,'cat':cat,'tanggal':tglMulai,'jamMulai':jamMulai,'jamSelesai':jamSelesai,'tglSelesai':tglSelesai},
                    success:function(response){
                      if(response == 'y'){
                        submitEDJ();
                      } else {
                        alert("Sudah ada jurnal yang disimpan pada waktu yang dipilih, "+response);
                      }
                    }
                  });
                  
               } else {
                  alert(msg);
               }
            }

            function submitEDJ(){
              $.ajax({
                    dataType: 'html',
                    url:'ajax/editJurnalStaff.php',
                    method:'post',
                    data : $('#FormDJS').serialize(),
                    success:function(a){
                      alert(a);
                      document.getElementById('modalDJS').style.display = "none";
                      document.getElementsByTagName("body")[0].style.overflow = "";
                      document.getElementById("tabelDJstaffContainer").innerHTML = "";
                      eventFire(document.getElementById("DJSbtn"), 'click');
                    }
              });
            }

            function submitDraftS(){
              var table = document.getElementById("tabelDJajax");
              var len = table.rows.length - 1;
              var count = 0;
              if(confirm("Sebanyak "+len+" jurnal akan disubmit ke atasan anda, jurnal yang sudah disubmit tidak bisa diedit kembali")){
                for(var i=1; i<table.rows.length; i++){
                  var idJ = table.rows[i].cells[0].innerHTML;
                  $.ajax({
                      dataType: 'html',
                      url:'ajax/kirimJurnal.php',
                      method:'post',
                      data : { 'id':idJ },
                      success:function(a){
                        if(a=="1"){
                          count += 1;
                        }
                      }
                  });
                  if(i == table.rows.length-1){
                    if(count>0){
                      alert("Submit jurnal berhasil, jumlah jurnal yang di submit: "+count);
                    } else {
                      alert("Submit jurnal gagal");
                    }
                  }
                }
                
                document.getElementById("tabelDJstaffContainer").innerHTML = "";
                eventFire(document.getElementById("DJSbtn"), 'click');
              }
            }

            function validateSJ() {
               var tgl = new Date();
               var cat = document.getElementById("tcmNamaCat").innerHTML;
               if(cat != "izin harian"){
                 var tglMulai = tgl.getFullYear() + "-" + (tgl.getMonth()+1) + "-" + document.forms["FormSJ"]["tglJurnal"].value;
                 var tglSelesai = tgl.getFullYear() + "-" + (tgl.getMonth()+1) + "-" + document.forms["FormSJ"]["tglJurnal"].value;
                 document.forms["FormSJ"]["tglMulai"].value = tglMulai;
                 document.forms["FormSJ"]["tglSelesai"].value = tglSelesai;
               } else {
                 var tglMulai = document.forms["FormSJ"]["tglMulai"].value;
                 var tglSelesai = document.forms["FormSJ"]["tglSelesai"].value;
               }
               var volumetype = document.forms["FormSJ"]["volumeType"].value;
               var jamMulai = document.forms["FormSJ"]["jamMulai"].value;
               var jamSelesai = document.forms["FormSJ"]["jamSelesai"].value;
               var keterangan = document.forms["FormSJ"]["SJketerangan"].value;
               var error = 0;
               var msg;
               if (volumetype == "" || tglMulai == "" || tglSelesai == ""){
                  msg = "Semua kolom harus diisi";
                  error++;
               } else if ( tglMulai > tglSelesai){
                  msg = "Tanggal selesai tidak boleh lebih awal dari tanggal mulai";
                  error++;
               } else if ( tglMulai == tglSelesai) {
                  if ( jamMulai > jamSelesai ) {
                     msg = "Jam selesai tidak boleh lebih awal dari jam mulai di hari yang sama"
                     error++;
                  } else if(jamMulai == jamSelesai) {
                     msg = "Jam selesai tidak boleh sama dengan jam mulai di hari yang sama"
                     error++;
                  }
               }

               if ( error == 0){
                  $.ajax({
                    dataType: 'html',
                    url:'ajax/cekkonflikjurnal.php',
                    async: false,
                    method:'post',
                    data : {'cat':cat,'tanggal':tglMulai,'jamMulai':jamMulai,'jamSelesai':jamSelesai,'tglSelesai':tglSelesai},
                    success:function(response){
                      if(response == 'y'){
                        alert("Jurnal berhasil disimpan");
                        document.getElementById("FormSJ").submit();
                      } else {
                        alert("Sudah ada jurnal yang disimpan pada waktu yang dipilih, "+response);
                      }
                    }
                  });
               } else {
                  alert(msg);
               }
            }

            function validateEIJ() {
               var tgl = new Date();
               var cat = document.getElementById("EIJNamaCat").innerHTML;
               if(cat != "izin harian"){
                 var tglMulai = document.forms["FormEIJ"]["EIJtglJurnal"].value;
                 var tglSelesai = document.forms["FormEIJ"]["EIJtglJurnal"].value;
                 document.forms["FormEIJ"]["EIJtglMulai"].value = tglMulai;
                 document.forms["FormEIJ"]["EIJtglSelesai"].value = tglSelesai;
               } else {
                 var tglMulai = document.forms["FormEIJ"]["EIJtglMulai"].value;
                 var tglSelesai = document.forms["FormEIJ"]["EIJtglSelesai"].value;
               }
               var volumetype = document.forms["FormEIJ"]["EIJvolumeType"].value;
               var jamMulai = document.forms["FormEIJ"]["EIJjamMulai"].value;
               var jamSelesai = document.forms["FormEIJ"]["EIJjamSelesai"].value;
               var keterangan = document.forms["FormEIJ"]["EIJketerangan"].value;
               var error = 0;
               var msg;
               if (volumetype == "" || tglMulai == "" || tglSelesai == ""){
                  msg = "Semua kolom harus diisi";
                  error++;
               } else if ( tglMulai > tglSelesai){
                  msg = "Tanggal selesai tidak boleh lebih awal dari tanggal mulai";
                  error++;
               } else if ( tglMulai == tglSelesai) {
                  if ( jamMulai > jamSelesai ) {
                     msg = "Jam selesai tidak boleh lebih awal dari jam mulai di hari yang sama"
                     error++;
                  } else if(jamMulai == jamSelesai) {
                     msg = "Jam selesai tidak boleh sama dengan jam mulai di hari yang sama"
                     error++;
                  }
               }
                console.log(tglMulai);

               if ( error == 0){
                  $.ajax({
                    dataType: 'html',
                    url:'ajax/cekkonflikjurnal.php',
                    async: false,
                    method:'post',
                    data : {'cat':cat,'tanggal':tglMulai,'jamMulai':jamMulai,'jamSelesai':jamSelesai,'tglSelesai':tglSelesai},
                    success:function(response){
                      if(response == 'y'){
                        alert("Jurnal berhasil disimpan");
                        document.getElementById("FormEIJ").submit();
                      } else {
                        alert("Sudah ada jurnal yang disimpan pada waktu yang dipilih, "+response);
                      }
                    }
                  });
               } else {
                  alert(msg);
               }
            }

            function validateEA() {
               var nip = document.forms["FormEA"]["EAnip"].value;
               var nama = document.forms["FormEA"]["nama"].value;
               var jabatan = document.forms["FormEA"]["jabatanBaru"].value;
               var tgl = document.forms["FormEA"]["tglGantiJabatan"].value;
               var password = document.forms["FormEA"]["password"].value;
               var error = 0;
               var msg;
               if (nama == "" || jabatan == "" || password == ""){
                  msg = "Tidak boleh ada kolom yang kosong";
                  error++;
               } else {
                  var regEx = /^\d{4}-\d{2}-\d{2}$/;
                  if(!tgl.match(regEx)){
                    msg = "Pilihan tanggal tidak valid";
                    error++;
                  }
               }

               if ( error == 0){
                  $.ajax({
                      dataType: 'html',
                      url:'ajax/editPegawai.php',
                      method:'post',
                      data : { 'nip':nip,'nama':nama,'jabatan':jabatan,'tglGanti':tgl,'password':password },
                      success:function(response){
                        alert(response);
                        document.getElementById("ModalEA").style.display = "none";
                        document.getElementById("EJBlihat_pegawai").style.display = "none";
                        lihatPegawai(document.getElementById("idJabatanDilihat").innerHTML);
                        document.getElementsByTagName("body")[0].style.overflow = "";
                      }
                  });
               } else {
                  alert(msg);
               }
            }

            function validateTA() {
               var nip = document.forms["FormTA"]["nip"].value;
               var nipbaru = document.forms["FormTA"]["nipbaru"].value;
               var nama = document.forms["FormTA"]["nama"].value;
               var jabatan = document.forms["FormTA"]["input_id_Jabatan"].value;
               var password = document.forms["FormTA"]["password"].value;
               var error = 0;
               var msg;
               if (nip == "" || nama == "" || jabatan == "" || password == ""){
                  msg = "Tidak boleh ada kolom yang kosong";
                  error++;
               }

               data = { 'nip':nip, 'nipbaru':nipbaru, 'nama':nama, 'jabatan':jabatan, 'password':password};
               if ( error == 0){
                  $.ajax({
                      dataType: 'html',
                      url:'ajax/cekNip.php',
                      method:'post',
                      data : { 'nip':nip },
                      success:function(a){
                        if(a == 'y'){
                          $.ajax({
                            dataType: 'html',
                            url:'ajax/tambahAccount.php',
                            method:'post',
                            data : data,
                            success:function(b){
                              if(b){
                                alert("Berhasil menambahkan account baru dengan nip: "+b);
                              } else {
                                alert("Gagal menambahkan account baru");
                              }
                              location.reload();
                            }
                          });
                        } else {
                          alert("NIP yang dimasukkan sudah ada di database, nama pegawai pemilik nip tersebut: "+a);
                        }
                      }
                  });
               } else {
                  alert(msg);
               }
            }

            function tambahJabatan(atasan, eselon){
              document.getElementById("ModalTJ").style.display = "block";
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
              if(atasan == "n"){
                document.getElementById("TJidAtasan").value = 9000;
                document.getElementById("TJeselonJabatan").value = eselon;
              } else {
                eselon++;
                document.getElementById("TJidAtasan").value = atasan;
                document.getElementById("TJeselonJabatan").value = eselon;
              }
            }

            function deleteJabatan(id, nama){
              if(confirm("Jabatan " + nama + " akan dihapus")){
                $.ajax({
                  dataType: 'html',
                  url:'ajax/deleteJabatan.php',
                  method:'post',
                  data : { 'id':id, 'nama':nama },
                  success:function(a){
                    if(a == "y"){
                      alert("Jabatan " + nama + " berhasil dihapus");
                      location.reload();
                    } else if(a == "n") {
                      alert("Jabatan " + nama + " gagal dihapus");
                    } else {
                      alert(a);
                    }
                  }
                });
              }
            }

            function validateTJ() {
               var nama = document.forms["FormTJ"]["nama"].value;
               var atasan = document.forms["FormTJ"]["TJidAtasan"].value;
               var eselon = document.forms["FormTJ"]["TJeselonJabatan"].value;
               var error = 0;
               var msg;
               if ( nama == "" || jabatan == "" || eselon == ""){
                  msg = "Tidak boleh ada kolom yang kosong";
                  error++;
               }

               data = { 'nama':nama, 'atasan':atasan, 'eselon':eselon};
               if ( error == 0){
                  $.ajax({
                      dataType: 'html',
                      url:'ajax/tambahJabatan.php',
                      method:'post',
                      data : data,
                      success:function(a){
                        if(a == "y"){
                          alert("Berhasil tambah jabatan baru");
                        } else {
                          alert("Gagal tambah jabatan baru");
                        }
                        location.reload();
                      }
                  });
               } else {
                  alert(msg);
               }
            }

            function validateTA_Act(){
                 var aktivitas = document.forms['FormTA_Act']['aktivitas'].value;
                 var kategori = document.forms['FormTA_Act']['kategori'].value;
                 var durasi = document.forms['FormTA_Act']['durasi'].value;
                 if (kategori==5)
                 {
                    var durasinya = 0;
                 }else{
                    var durasinya = null;
                 } 
                 console.log(aktivitas+kategori+durasinya);
                 if (aktivitas == "" || kategori == "" || durasinya==null)                 {
                     alert("Semua kolom harus diisi");
                 } else {
                                document.getElementById("FormTA_Act").submit();
                                alert("Aktivitas Baru telah Ditambahkan");
                        }
              }

            function validateTA_ActAjuan(){
                 var aktivitas = document.forms['FormTA_Actajuan']['aktivitas_ajuan'].value;
                 var kategori = document.forms['FormTA_Actajuan']['kategori_ajuan'].value;
                 var durasi = document.forms['FormTA_Actajuan']['durasi_ajuan'].value;
                 var tanggal_ajuan = document.forms['FormTA_Actajuan']['tanggal_ajuan'].value;
                 var status_ajuan = document.forms['FormTA_Actajuan']['status_ajuan'].value;
                 if (kategori==5)
                 {
                    var durasinya = 0;
                 }else{
                    var durasinya = durasi;
                 } 
                 console.log(aktivitas+kategori+durasinya);
                 if (aktivitas == "" || kategori == "" || durasinya==null)                 {
                     alert("Semua kolom harus diisi");
                 } else {
                                document.getElementById("FormTA_Actajuan").submit();
                                alert("Aktivitas Baru telah Diajukan");
                        }
              }
            function validateTA_EAct(){
                 var id_aktivitas = document.forms['FormTA_EAct']['id_aktivitas'].value;
                 var aktivitas = document.forms['FormTA_EAct']['inputaktivitas'].value;
                 var kategori = document.forms['FormTA_EAct']['input_idkategori'].value;
                 var durasi = document.forms['FormTA_EAct']['inputdurasi'].value;
                 if (id_aktivitas == "" || aktivitas == "" || kategori == "" || durasi=="")                 {
                     alert("Semua kolom harus diisi");
                 } else {
                                document.getElementById("FormTA_EAct").submit();
                                alert("Aktivitas telah Diubah");
                        }
              }
            function validateTA_EActajuan(){
                 var id_ajuan = document.forms['FormTA_EActajuan']['id_ajuan'].value;
                 var aktivitas = document.forms['FormTA_EActajuan']['inputaktivitasajuan'].value;
                 var kategori = document.forms['FormTA_EActajuan']['input_idkategoriajuan'].value;
                 var durasi = document.forms['FormTA_EActajuan']['inputdurasiajuan'].value;
                 var status_ajuan = document.forms['FormTA_Actajuan']['status_ajuan'].value;
                 if (id_ajuan == "" || aktivitas == "" || kategori == "" || durasi=="")                 {
                     alert("Semua kolom harus diisi");
                 } else {
                                document.getElementById("FormTA_EActajuan").submit();
                                alert("Aktivitas yang diajukan telah Diubah");
                        }
              }

            function selectVJ(type, text){
              var date = new Date();
              var nip = document.getElementById("userNip").innerHTML;
              document.getElementById("vjbtnLabel").innerHTML = text;
              document.getElementById("VJpilihHari").style.display = "none";
              document.getElementById("VJbtn").style.display = "none";
              if(type == "today"){
                loadTabelVJ('today', date.getDate());
              } else if(type == "bulan"){
                loadTabelVJ('month', date.getMonth()+1);
              } else {
                document.getElementById("VJpilihHari").style.display = "block";
                document.getElementById("VJbtn").style.display = "block";
              }
            }

            function loadTabelVJ(type, date){
              if(type == 'day'){
                var date = document.getElementById("VJselectDay").value;
              } else if(type == 'today'){
                type = 'day';
              }
              $.ajax({
                dataType: 'html',
                url:'ajax/getTabelVJ.php',
                method:'POST',
                data : {'type':type,'date':date},
                success:function(response){
                  document.getElementById("tabelVJContainer").innerHTML = response;
                  $adaResult = document.getElementsByClassName("barisJurnalValidasi");
                  console.log($adaResult.length);
                  if($adaResult.length > 0){
                    document.getElementById("tidakAdaJurnal").style.display = "none";
                  } else {
                    document.getElementById("tidakAdaJurnal").style.display = "table-cell";
                  }
                }
              });
            }

            function lihatJurnal(nip, nama) {
              document.getElementById("modalLJ").style.display = "block";
              document.getElementById("labelPemilikJurnal").innerHTML = nama;
              document.getElementById("LJSnip").value = nip;
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
              document.getElementById("tabelLJstaffContainer").innerHTML = "";
              eventFire(document.getElementById("LJSbtn"), 'click');
            }

            function lihatJurnalADM(nip, nama) {
              document.getElementById("modalLJ").style.display = "block";
              document.getElementById("labelPemilikJurnal").innerHTML = nama;
              document.getElementById("LJSnip").value = nip;
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
              document.getElementById("tabelLJstaffContainer").innerHTML = "";
              eventFire(document.getElementById("LJSbtn"), 'click');
            }

            function editAccount(nip, nama, jabatan, kepala, password){
              document.getElementById("ModalEA").style.display = "block";
              document.getElementById("labelPemilikAccount").innerHTML = nip;
              document.getElementById("EAnip").value = nip;
              document.getElementById("inputNama").value = nama;
              document.getElementById("inputJabatan").value = jabatan; 
              document.getElementById("inputJabatanBaru").value = jabatan; 
              document.getElementById("inputPassword").value = password;
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
              $('.EAjabatan').each(function(i, obj) { obj.style.display = "none"});
              document.getElementById("EAinputEselon").value = 1;
            }

            function EAselectEch(i){
              document.getElementById("tanggalGanti").style.display = "none";
              var select = document.getElementById("EAinputEselon");
              var value = select.options[select.selectedIndex].value-1;
              var labels = ["Deputi", "Biro", "Bagian", "SubBagian", "Staf"];
              if(value == 0){
                $('.EAjabatan').each(function(i, obj) { obj.style.display = "none"});
                document.getElementById("EAbtnSubmit").classList.remove("disable");
              } else {
                if(i < value){
                  document.getElementById("EAbtnSubmit").classList.add("disable");
                } else {
                  document.getElementById("EAbtnSubmit").classList.remove("disable");
                }
                if( i == 0 && value >= 1){
                  document.getElementById("inputJabatanBaru").value = document.getElementById("inputJabatan").value;
                  $('.EAjabatan').each(function(i, obj) { obj.style.display = "none"});
                  for(var k = 1; k <= 5; k++){
                    var id = "EAinput-" + k;
                    var label = labels[k-1];
                    if(k <= value){
                      var pilih = "pilih-" + k;
                      document.getElementById(id).innerHTML = "<option value='" + pilih + "'>Pilih " + label + "</option>";
                    } else {
                      document.getElementById(id).innerHTML = "";
                    }
                  }
                }
                if( i < value ){
                  $('.EAjabatan').each(function(i, obj) { obj.style.display = "none"});
                  for( var j = 0; j <= i; j++){
                      document.getElementsByClassName("EAjabatan")[j].style.display = "table-row";
                  }

                  if(i>0){
                    var id = "EAinput-" + i;
                    var selectJ = document.getElementById(id);
                    var valueJ = selectJ.options[selectJ.selectedIndex].value;
                    if(valueJ == 0){
                      document.getElementsByClassName("EAjabatan")[i].style.display = "none";
                    }
                  } else {
                    var valueJ = "n";
                  }
                  if(valueJ != 0){
                    $.ajax({
                      dataType: 'html',
                      url:'ajax/getSelectJabatan.php',
                      method:'POST',
                      data : {'i': i, 'value': value, 'atasan': valueJ},
                      success:function(response){
                        var x = i + 1;
                        var id = "EAinput-" + x;
                        document.getElementById(id).innerHTML = response;
                      }
                    });
                  }
                } else {
                  var j = i++;
                  var id = "EAinput-" + j;
                  var selectJ = document.getElementById(id);
                  var jabatanDipilih = selectJ.options[selectJ.selectedIndex].value;
                  if( jabatanDipilih == 0){
                    document.getElementById("EAbtnSubmit").classList.add("disable");
                    document.getElementById("inputJabatanBaru").value = document.getElementById("inputJabatan").value;
                  } else {
                    document.getElementById("inputJabatanBaru").value = jabatanDipilih;
                    document.getElementById("tanggalGanti").style.display = "";
                  }
                }
              }
            }


            function editAktivitas(id_aktivitas, nama_aktivitas, durasi, id_kategori){
              document.getElementById("ModalEact").style.display = "block";
              document.getElementById("labelaktivitas").innerHTML = nama_aktivitas;
              document.getElementById("id_aktivitas").value = id_aktivitas;
              document.getElementById("inputaktivitas").value = nama_aktivitas;
              document.getElementById("inputdurasi").value = durasi;
              document.getElementById("input_idkategori").value = id_kategori;
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
            }
            function editAktivitasajuan(id_ajuan, nama_aktivitas, durasi, id_kategori){
              console.log(id_ajuan + nama_aktivitas + durasi + id_kategori );
              document.getElementById("ModalEactajuan").style.display = "block";
              document.getElementById("labelaktivitasajuan").innerHTML = nama_aktivitas;
              document.getElementById("id_ajuan").value = id_ajuan;
              document.getElementById("inputaktivitasajuan").value = nama_aktivitas;
              document.getElementById("inputdurasiajuan").value = durasi;
              document.getElementById("input_idkategoriajuan").value = id_kategori;
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
            }
            function editAktivitasajuanadmin(id_ajuan, nama_aktivitas, durasi, id_kategori,status_ajuan){
              console.log(id_ajuan + nama_aktivitas + durasi + id_kategori + status_ajuan);
              document.getElementById("ModalEactajuan").style.display = "block";
              document.getElementById("labelaktivitasajuan").innerHTML = nama_aktivitas;
              document.getElementById("id_ajuan").value = id_ajuan;
              document.getElementById("inputaktivitasajuan").value = nama_aktivitas;
              document.getElementById("inputdurasiajuan").value = durasi;
              document.getElementById("input_idkategoriajuan").value = id_kategori;
              document.getElementById("status").value = status_ajuan;
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
            }
            function deleteAktivitas(id_aktivitas){
               data = { 'id_aktivitas':id_aktivitas };
                  alert("Menghapus Aktivitas");
                  var jurnalExists = true;
                  $.ajax({
                      dataType: 'html',
                      url:'ajax/cekjurnal.php',
                      method:'post',
                      data : { 'id_aktivitas':id_aktivitas },
                      success:function(a){
                        if(a == 'n'){
                          //jurnalExists = false;
                          $.ajax({
                                  dataType: 'html',
                                  url:'ajax/hapusjurnal.php',
                                  method:'post',
                                  data : data,
                                  success:function(){
                                      alert("Berhasil menghapus aktivitas ");
                                      location.reload();
                                    }
                                });
                        } else {
                                alert("Terdapat "+a+" Jurnal yang menggunakan aktivitas ini");
                      }
                          }
                  });
            }

            function deleteAktivitasajuan(id_ajuan){
               data = { 'id_ajuan':id_ajuan };
               console.log(data);
                  $.ajax({
                          dataType: 'html',
                          url:'ajax/hapusajuan.php',
                          method:'post',
                          data : data,
                          success:function(){
                          alert("Berhasil menghapus ajuan aktivitas yang dipilih");
                          location.reload();
                                    }
                  });
            }

            function aktivitasoke(id_ajuan,nama_aktivitas,durasi,id_kategori){
               data = { 'id_ajuan':id_ajuan,'nama_aktivitas':nama_aktivitas,'durasi':durasi,'id_kategori':id_kategori, };
               console.log(data);
                  $.ajax({
                          dataType: 'html',
                          url:'ajax/ajuaninputdb.php',
                          method:'post',
                          data : data,
                          success:function(){
                          alert("Berhasil memasukkan aktivitas ajuan ke database");
                          location.reload();
                                    }
                  });
            }
            function selectJA(type, jabatan){
              var btn1 = document.getElementById("pjBtn1");
              var btn2 = document.getElementById("pjBtn2");
              if ( type == "Pribadi"){
                if (!btn1.classList.contains("active")){
                  var filter = document.getElementById("PJAfilter");
                  var tabelA = document.getElementById("JAtabelA");
                  var tabelS = document.getElementById("JAtabelS");
                  tabelA.style.display = "block";
                  tabelS.style.display = "none";
                  filter.style.display = "flex";
                  btn1.classList.add("active");
                  btn2.classList.remove("active");
                }
              } else {
                if (!btn2.classList.contains("active")){
                  var filter = document.getElementById("PJAfilter");
                  var tabelA = document.getElementById("JAtabelA");
                  var tabelS = document.getElementById("JAtabelS");
                  tabelS.style.display = "block";
                  tabelA.style.display = "none";
                  filter.style.display = "none";
                  btn2.classList.add("active");
                  btn1.classList.remove("active");
                  JAgetListPegawai(jabatan);
                }
              }
            }

            function JAgetListPegawai(j){
              $.ajax({
                dataType: 'html',
                url:'ajax/getListPegawai.php',
                method:'get',
                data : {'idjabatan': j},
                success:function(response){
                  $("#JAtabelSContainer").html(response);
                  $("#JPTable").tablesorter();
                }
              });
            }

            function selectTYPE(type,j){
              var tombol1 = document.getElementById("tombol1");
              var tombol2 = document.getElementById("tombol2");
                //alert(type)
              if(tombol1){
                if ( type == "sendiri"){
                  if (!tombol1.classList.contains("active")){
                    var tabelADMIN = document.getElementById("JAtabelADMIN");
                    var tabelSTAFF = document.getElementById("JAtabelSTAFF");
                    tabelADMIN.style.display = "block";
                    tabelSTAFF.style.display = "none";
                    tombol1.classList.add("active");
                    tombol2.classList.remove("active");
                  }
                } else {
                  if (!tombol2.classList.contains("active")){
                    var tabelADMIN = document.getElementById("JAtabelADMIN");
                    var tabelSTAFF = document.getElementById("JAtabelSTAFF");
                    tabelSTAFF.style.display = "block";
                    tabelADMIN.style.display = "none";
                    tombol2.classList.add("active");
                    tombol1.classList.remove("active");
                    $.ajax({
                      dataType: 'html',
                      url:'ajax/getListPegawai-detail_admin.php',
                      method:'post',
                      data : { 'idjabatan': j },
                      success:function(response){
                        $("#JAtabelSTAFF").html(response);
                        $("#actTableDA").tablesorter();
                      }
                    });
                  }
                }
              }
              
            }

            function JAfilter(fil) {
               btn = document.getElementById("filBtn");
               label = document.getElementById("PJAbtnLabel");
               if(btn){
                 var harian = document.getElementsByClassName("LJAfilter")[0];
                 var periode = document.getElementsByClassName("LJAfilter")[1];
                 document.getElementById("filContent").classList.toggle("show");
                 label.innerHTML = fil;

                 if ( typeof harian === 'undefined'){
                   } else {
                   if( fil == 'Harian'){
                      harian.style.display = "inline-block";
                      periode.style.display = "none";
                   } else {
                      periode.style.display = "inline-block";
                      harian.style.display = "none";
                   }
                    document.getElementById("LJAfilterType").value = fil;
                 }
                 eventFire(document.getElementById("LJAbtn"), 'click');
               }
            }

            function lihatJurnalAdmin(nip) {
              var filType = document.getElementById("LJAfilterType").value;
              var data = "kosong";
              var error = 0;
              var msg = "";
              console.log(filType);
              if ( filType == 'Harian'){
                var tanggal = document.getElementById("LJApilihHari").value;
                var split = tanggal.split("-");
                var tahun = split[0];
                var bulan = split[1];
                var hari = split[2];
                if ( tanggal != ""){
                  data = { 'nip': nip, 'tipeFilter': filType, 'tahun': tahun, 'bulan': bulan, 'hari': hari };
                } else {
                	msg = "Tanggal yang dipilih tidak valid";
                	error++;
                }
              } else {
                var awal = document.getElementById("LJApilihAwal").value;
                var akhir = document.getElementById("LJApilihAkhir").value;
                var tglAwal = new Date(awal);
                var tglAkhir = new Date(akhir);
                if(tglAkhir < tglAwal){
                	msg = "Tanggal sampai tidak bisa lebih awal daripada tanggal dari";
                	error++;
                } else {
	                if ( awal != ""){
	                  data = { 'nip': nip, 'tipeFilter': filType, 'awal': awal, 'akhir': akhir };
	                } else {
	                	msg = "Input tanggal tidak valid";
	                	error++;
	                }
                }
              }

              if ( error == 0 ){
                $.ajax({    //create an ajax request to load_page.php
                  type: "GET",
                  url: "ajax/tabelLJadmin.php",             
                  dataType: "html",   //expect html to be returned
                  data: data,               
                  success: function(response){                    
                      $("#JAtabelA").html(response);
                      var adaJurnal = document.getElementById('tabelAdmin-'+nip);
                      if(!adaJurnal){
                        var csv = document.getElementById("csvBtnADM");
                        var xls = document.getElementById("xlsBtnADM");
                        var pdf = document.getElementById("pdfBtnADM");
                        csv.addEventListener('click', function(e){
                          $('#tabelLJajaxADM').tableExport({
                            type:'csv',
                            fileName: 'Jurnal'+filType+'-'+nip,
                            escape:'false'
                          });
                        });
                        xls.addEventListener('click', function(e){
                          $('#tabelLJajaxADM').tableExport({
                            type:'xls',
                            fileName: 'Jurnal'+filType+'-'+nip,
                            escape:'false'
                          });
                        });
                        pdf.addEventListener('click', function(e){
                          $('#tabelLJajaxADM').tableExport({
                            type:'pdf',
                            jspdf: {
                              orientation: 'l'
                            },
                            fileName: 'Jurnal'+filType+'-'+nip,
                            escape:'false'
                          });
                        });
                      }
                  }
                });
              } else {
                alert(msg);
              }
            }

            function lihatJurnalStaff(nip) {
              var filType = document.getElementById("LJSfilterType").value;
              var data = "kosong";
              var msg = "";
              var error = 0;
              if (document.getElementById("LJSnip")){
                nip = document.getElementById("LJSnip").value;
              }
              if ( filType == 'Harian'){
                var tanggal = document.getElementById("LJSpilihHari").value;
                var split = tanggal.split("-");
                var tahun = split[0];
                var bulan = split[1];
                var hari = split[2];
                if ( tanggal != ""){
                  data = { 'nip': nip, 'tipeFilter': filType, 'tahun': tahun, 'bulan': bulan, 'hari': hari };
                } else {
                	msg = "Tanggal yang dipilih tidak valid";
                	error++;
                }
              } else {
                var awal = document.getElementById("LJSpilihAwal").value;
                var akhir = document.getElementById("LJSpilihAkhir").value;
                var tglAwal = new Date(awal);
                var tglAkhir = new Date(akhir);
                if(tglAkhir < tglAwal){
                	msg = "Tanggal sampai tidak bisa lebih awal daripada tanggal dari";
                	error++;
                } else {
	                if ( awal != ""){
	                  data = { 'nip': nip, 'tipeFilter': filType, 'awal': awal, 'akhir': akhir };
	                } else {
	                	msg = "Input tanggal tidak valid";
	                	error++;
	                }
	            }
              }

              if ( error == 0 ){
                $.ajax({    //create an ajax request to load_page.php
                  type: "GET",
                  url: "ajax/tabelLJstaff.php",             
                  dataType: "html",   //expect html to be returned
                  data: data,               
                  success: function(response){                    
                      $("#tabelLJstaffContainer").html(response);
                      var adaJurnal = document.getElementById('tabel-'+nip);
                      if(!adaJurnal){
                        var csv = document.getElementById("csvBtn");
                        var xls = document.getElementById("xlsBtn");
                        var pdf = document.getElementById("pdfBtn");
                        csv.addEventListener('click', function(e){
                          $('#tabelLJajax').tableExport({
                            type:'csv',
                            fileName: 'Jurnal'+filType+'-'+nip,
                            escape:'false'
                          });
                        });
                        xls.addEventListener('click', function(e){
                          $('#tabelLJajax').tableExport({
                            type:'xls',
                            fileName: 'Jurnal'+filType+'-'+nip,
                            escape:'false'
                          });
                        });
                        pdf.addEventListener('click', function(e){
                          $('#tabelLJajax').tableExport({
                            type:'pdf',
                            jspdf: {
                              orientation: 'l'
                            },
                            fileName: 'Jurnal'+filType+'-'+nip,
                            escape:'false'
                          });
                        });
                      }
                  }
                });
              } else {
                alert(msg);
              }
            }

            function lihatJurnalSemua(nip) {
              var filType = document.getElementById("LJSfilterType").value;
              var data = "kosong";

              if (document.getElementById("LJSnip")){
                nip = document.getElementById("LJSnip").value;
              }
              if ( filType == 'Harian'){
                var tanggal = document.getElementById("LJSpilihHari").value;
                var split = tanggal.split("-");
                var tahun = split[0];
                var bulan = split[1];
                var hari = split[2];
                if ( tanggal != ""){
                  data = { 'nip': nip, 'tipeFilter': filType, 'tahun': tahun, 'bulan': bulan, 'hari': hari };
                }
              } else if ( filType == 'Mingguan'){
                var tahunMinggu = document.getElementById("LJSpilihMinggu").value;
                var split = tahunMinggu.split("-");
                var tahun = split[0];
                var minggu = split[1];
                if ( tahunMinggu != ""){
                  data = { 'nip': nip, 'tipeFilter': filType, 'tahun': tahun, 'minggu': minggu };
                }
              } else {
                var tahun = document.getElementById("LJSpilihTahun").value;
                var bulan = document.getElementById("LJSpilihBulan").value;
                data = { 'nip': nip, 'tipeFilter': filType, 'tahun': tahun, 'bulan': bulan };
              }

              if ( data != 'kosong'){
                $.ajax({    //create an ajax request to load_page.php
                  type: "GET",
                  url: "ajax/tabelLJsemua.php",             
                  dataType: "html",   //expect html to be returned
                  data: data,               
                  success: function(response){                    
                      $("#tabelLJstaffContainer").html(response);
                      var adaJurnal = document.getElementById('tabelSemua-'+nip);
                      if(!adaJurnal){
                        var csv = document.getElementById("csvBtn");
                        var xls = document.getElementById("xlsBtn");
                        var pdf = document.getElementById("pdfBtn");
                        csv.addEventListener('click', function(e){
                          $('#tabelLJajax').tableExport({
                            type:'csv',
                            fileName: 'Jurnal'+filType+'-'+nip,
                            escape:'false'
                          });
                        });
                        xls.addEventListener('click', function(e){
                          $('#tabelLJajax').tableExport({
                            type:'xls',
                            fileName: 'Jurnal'+filType+'-'+nip,
                            escape:'false'
                          });
                        });
                        pdf.addEventListener('click', function(e){
                          $('#tabelLJajax').tableExport({
                            type:'pdf',
                            jspdf: {
                              orientation: 'l'
                            },
                            fileName: 'Jurnal'+filType+'-'+nip,
                            escape:'false'
                          });
                        });
                      }
                  }
                });
              } else {
                alert("Kolom filter kosong");
              }
            }

            function EIJpilihAct(id, nama, durasi, cat){
                var tabel = document.getElementById("tableEIJ");
                var tanggal = document.getElementById("EIJ2tanggalDipilih").innerHTML;
            	var date = new Date(tanggal);
            	var textDate = date.getDate()+" "+date.getMonthName()+" "+date.getFullYear();
                for(var i=0; i<tabel.rows.length;i++ ){tabel.rows[i].style.display = ""; vt = "";}
                document.getElementById("modalEIJ").style.display = "block";
                document.getElementById("EIJNamaAct").innerHTML = nama;
                document.getElementById("EIJDurasi").innerHTML = durasi;
                document.getElementById("EIJNamaCat").innerHTML = cat;
                document.getElementById("EIJ_idAct").value = id;
                if(cat == "izin harian"){
                  tabel.rows[2].style.display = "none";
                  tabel.rows[4].style.display = "none";
                  tabel.rows[5].style.display = "none";
                  tabel.rows[7].style.display = "none";
                  tabel.rows[10].style.display = "none";
                  tabel.rows[11].style.display = "none";
                  document.getElementById("EIJvolumeType").value = "-";
                  document.getElementById("EIJtanggal").style.width = "190px";
                  //document.getElementById("tglMulai").style.display = "";
                  //document.getElementById("tglSelesai").style.display = "";
                  document.getElementById("EIJjamMulai").value = "00:00";
                  document.getElementById("EIJjamSelesai").value = "23:59";
                  document.getElementById("EIJiconJamMulai").style.display = "none";
                  document.getElementById("EIJiconJamSelesai").style.display = "none";
                  document.getElementById("EIJwaktuMulai").style.display = "none";
                  document.getElementById("EIJwaktuSelesai").style.display = "none";
                  document.getElementById("EIJtanggalMulai").style.display = "";
                  document.getElementById("EIJtanggalSelesai").style.display = "";
                  document.getElementById("EIJjenisAktivitas").style.display = "none";
               	  document.getElementById("EIJtglMulai").value = document.getElementById("EIJ2tanggalDipilih").innerHTML;
               	  document.getElementById("EIJtextTglMulai").innerHTML = textDate;
               } else {
               	  document.getElementById("EIJtglJurnal").value = document.getElementById("EIJ2tanggalDipilih").innerHTML;
               	  document.getElementById("EIJtextTglJurnal").innerHTML = textDate;
                  document.getElementById("EIJjenisAktivitas").style.display = "";
                  document.getElementById("EIJtanggalMulai").style.display = "none";
                  document.getElementById("EIJtanggalSelesai").style.display = "none";
                  document.getElementById("EIJiconJamMulai").style.display = "";
                  document.getElementById("EIJiconJamSelesai").style.display = "";
                  document.getElementById("EIJtanggal").style.width = "3px";
                  document.getElementById("EIJjam").style.width = "88px";
                  //document.getElementById("tglMulai").style.display = "none";
                  //document.getElementById("tglSelesai").style.display = "none";
                  document.getElementById("EIJvolumeType").value = "";
               }
            }

            Date.prototype.getMonthName = function() {
			  	var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			  	return months[this.getMonth()];
			};

            function bukaEIJ2(tanggalInput){
            	var bulanTahun = document.getElementById("DJSpilihBulan").value;
              	var split = bulanTahun.split("-");
              	var tahun = split[0];
              	var bulan = split[1];
            	if(tanggalInput.toString().length == 1 ){
            		tanggalInput = "0" + tanggalInput;
            	}
            	var tanggal = tahun+"-"+bulan+"-"+tanggalInput;
            	document.getElementById("modalEIJ2").style.display = "block";
              	document.getElementsByTagName("body")[0].style.overflow = "hidden";
              	document.getElementById("EIJ2tanggalDipilih").innerHTML = tanggal;
            }

            function openTAform(id_jabatan,nama_jabatan){
              document.getElementById("ModalTA").style.display = "block";
              document.getElementById("nambah_nama_jabatan").innerHTML = nama_jabatan;
              document.getElementById("input_id_Jabatan").value = id_jabatan;
              document.getElementsByTagName("body")[0].style.overflow = "hidden";

            }
            function Actform(){
              document.getElementById("ModalAct").style.display = "block";
              document.getElementsByTagName("body")[0].style.overflow = "hidden";

            }

            function Actformajuan(){
              document.getElementById("ModalActajuan").style.display = "block";
              document.getElementsByTagName("body")[0].style.overflow = "hidden";

            }
             
            function lihatKalender(niep,namapeg){
              document.getElementById("ModalKal").style.display = "block";
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
              $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "ajax/getcalender.php",             
                dataType: "html",   //expect html to be returned
                data: { 'niep': niep,'namapeg':namapeg},               
                success: function(response){                    
                    $("#calendar_div").html(response);
                }
              });
            }

            function selectRating(idJurnal,btn){
              var x = document.getElementsByClassName("ratingDiv");
              var y = document.getElementsByClassName("ratingBtn");
              for (var i = 0; i < x.length; i++) {
                  x[i].style.display = "";
              }
              for (var i = 0; i < y.length; i++) {
                  y[i].style.display = "none";
              }
              var idElement = "rating-" + idJurnal;
              document.getElementById(idElement).style.display = "";
              btn.style.display = "none";
            }

            function giveRating(idJurnal, rating, tanggal, idPeg, namaPeg){
              console.log("give rating "+rating);
              $.ajax({    //create an ajax request to load_page.php
                type: "POST",
                url: "ajax/giveRating.php",             
                dataType: "html",   //expect html to be returned
                data: { 'rating': rating,'id':idJurnal},               
                success: function(response){                    
                  if(response == 'y'){
                    alert("Berhasil memberikan rating");
                    detail_selectActivity(tanggal, idPeg, namaPeg);
                  }
                }
              });
            }

            function giveRatingPrint(idJurnal, rating, idPeg){
              console.log("give rating "+rating);
              $.ajax({    //create an ajax request to load_page.php
                type: "POST",
                url: "ajax/giveRating.php",             
                dataType: "html",   //expect html to be returned
                data: { 'rating': rating,'id':idJurnal},               
                success: function(response){                    
                  if(response == 'y'){
                    alert("Berhasil memberikan rating");
                    lihatJurnalStaff(idPeg);
                  }
                }
              });
            }

            function bukaModalValidasi(type,data){
              document.getElementById("modalEVJ").style.display = "block";
              document.getElementsByTagName("body")[0].style.overflow = "hidden";
              if(type == 'edit'){
                document.getElementById("EVJidJ").value = data;
                document.getElementById("EVJeditBtn").style.display = "block";
                document.getElementById("EVJpesan").readOnly = false;
                document.getElementById("EVJpesan").innerHTML = ""
                document.getElementById("VJModalLabel").innerHTML = "Ganti status validasi jurnal";
              } else if(type == 'lihat'){
                document.getElementById("VJModalLabel").innerHTML = "Lihat pesan validasi jurnal";
                document.getElementById("EVJpesan").innerHTML = data;
                document.getElementById("EVJpesan").readOnly = true;
                document.getElementById("EVJeditBtn").style.display = "none";
              }
              
            }

            function gantiValidasi(type,idJ){
              var idJurnal = document.getElementById("EVJidJ").value;
              var pesan = "";
              pesan = document.getElementById("EVJpesan").value;
              if(type == "no"){
              	if(pesan == ""){
              		alert("Kolom pesan tidak boleh kosong, saran: masukkan alasan mengapa anda ingin mengubah validasi jurnal ini.");
              	} else {
	                $.ajax({
	                  type: "POST",
	                  url: "ajax/gantiValidasi.php",             
	                  dataType: "html",
	                  data: {'type':type,'pesan':pesan,'id':idJurnal},               
	                  success: function(response){              
	                    if(response == 'y'){
	                      alert("Berhasil mengganti status validasi");
	                      document.getElementById("modalEVJ").style.display = "none";
	                      var filter = document.getElementById("vjbtnLabel").innerHTML;
	                      console.log(filter);
	                      if(filter == ' Hari ini'){
	                        selectVJ('today',filter);
	                      } else if(filter == ' Bulan ini'){
	                        selectVJ('bulan',filter);
	                      } else {
	                        eventFire(document.getElementById("VJbtn"), 'click');
	                      }
	                    }
	                  }
	                });
            	}
              } else if(type == "ok") {
                pesan = "";
                if(confirm("Ubah status validasi jurnal ini menjadi OK?")){
                  $.ajax({
                    type: "POST",
                    url: "ajax/gantiValidasi.php",             
                    dataType: "html",
                    data: {'type':type,'pesan':pesan,'id':idJ},               
                    success: function(response){            
                      if(response == 'y'){
                        alert("Berhasil mengganti status validasi");
                        var filter = document.getElementById("vjbtnLabel").innerHTML;
                        if(filter == ' Hari ini'){
                          selectVJ('today',filter);
                        } else if(filter == ' Bulan ini'){
                          selectVJ('bulan',filter);
                        } else {
                          eventFire(document.getElementById("VJbtn"), 'click');
                        }
                      }
                    }
                  });
                }
              }
            }
             
            function eventFire(el, etype){
              if(el){
                if (el.fireEvent) {
                  el.fireEvent('on' + etype);
                } else {
                  var evObj = document.createEvent('Events');
                  evObj.initEvent(etype, true, false);
                  el.dispatchEvent(evObj);
                }
              }
            }
         </script>
         <script type="text/javascript">
           $(document).ready(function(){
             JAfilter('Periode');
             selectDJS('Bulanan');
             selectReport('Periode');
             if(document.getElementById("vjbtnLabel")){
              selectVJ('today', ' Hari ini');
             }
             getHLdata();

             var tanggal = new Date();

             var elem = document.createElement('input');
             elem.setAttribute('type', 'date');
              if(elem){
                $('#tglSelesai').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                $('#tglMulai').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                $('#EIJtglSelesai').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                $('#LJSpilihHari').datepicker({ dateFormat: 'yy-mm-dd' });
                $('#LJSpilihAwal').datepicker({ dateFormat: 'yy-mm-dd' });
                $('#LJSpilihAkhir').datepicker({ dateFormat: 'yy-mm-dd' });
                
                if( document.getElementById('HLstart')){
                  $('#HLstart').datepicker({ dateFormat: 'yy-mm-dd' });
                  $('#HLend').datepicker({ dateFormat: 'yy-mm-dd' });
                  $('#tglGantiJabatan').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                } else {
                  if(document.getElementById("LJApilihHari")){
                    $('#LJApilihHari').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                    $('#LJApilihAwal').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                    $('#LJApilihAkhir').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                  }
                  $('#LJSpilihHari').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                  $('#LJSpilihAwal').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                  $('#LJSpilihAkhir').combodate({ smartDays: true, minYear: tanggal.getFullYear()-1, maxYear: tanggal.getFullYear()});
                  if( document.getElementById('LJApilihAwal')){
                    $('#LJApilihAwal').datepicker({ dateFormat: 'yy-mm-dd' });
                    $('#LJApilihHari').datepicker({ dateFormat: 'yy-mm-dd' });
                    $('#LJApilihAkhir').datepicker({ dateFormat: 'yy-mm-dd' });
                  }
                }
              }

             if(document.getElementById("EJBTableWrapper")){
                toggleChild('n','1','EJBTableWrapper');
             }
             eventFire(document.getElementById("DJSbtn"), 'click');
             eventFire(document.getElementById("tombol2"), 'click');
             if(document.getElementById("LJSbtn")){
                eventFire(document.getElementById("LJSbtn"), 'click');
             }
             if(document.getElementById("LJAbtn")){
                eventFire(document.getElementById("LJAbtn"), 'click');
             }

             if (document.getElementById("pjBtn1")){
               document.getElementById("pjBtn1").classList.add("active");
             }
             if (document.getElementById("LJApilihMinggu")){
              convertToWeekPicker($("#LJApilihMinggu"));
             }
             if (document.getElementById("DJSpilihMinggu")){
              convertToWeekPicker($("#DJSpilihMinggu"));
             }
             convertToWeekPicker($("#LJSpilihMinggu"));
             $('.dropbtn').click(function(){
                document.getElementById("ddcContent").classList.toggle("show");
                if (document.getElementById("repContent")){
                  document.getElementById("repContent").classList.toggle("show");
                }
                if (document.getElementById("vjContent")){
                  document.getElementById("vjContent").classList.toggle("show");
                }
                if (document.getElementById("ajuContent")){
                  document.getElementById("ajuContent").classList.toggle("show");
                }
                if (document.getElementById("filContent")){
                  document.getElementById("filContent").classList.toggle("show");
                }
                if (document.getElementById("djsContent")){
                  document.getElementById("djsContent").classList.toggle("show");
                }
                if (document.getElementById("pacContent")){
                  document.getElementById("pacContent").classList.toggle("show");
                }
                if (document.getElementById("EJContent")){
                  document.getElementById("EJContent").classList.toggle("show");
                }
                if (document.getElementById("EIJ2Content")){
                  document.getElementById("EIJ2Content").classList.toggle("show");
                }
             })
             $('.clockpicker').clockpicker({
                autoclose: true,
                placement: 'top'
             });
             $("#FormDJS").submit(function(e) {
                e.preventDefault();
             });

             if(document.getElementById("actTable")){
                $("#actTable").tablesorter();
             }

             function onReady(callback) {
              var intervalID = window.setInterval(checkReady, 1000);

              function checkReady() {
                if (document.getElementsByTagName('body')[0] !== undefined) {
                  window.clearInterval(intervalID);
                  callback.call(this);
                }
              }
             }
             function show(id, value) {
               document.getElementById(id).style.display = value ? 'block' : 'none';
             }

             onReady(function () {
               show('loadingpage', false);
             });

             function HLedit(event){
              var startDate = event.startDate.getFullYear() + '-' + ('0' + (event.startDate.getMonth()+1)).slice(-2) + '-' + ('0' + (event.startDate.getDate())).slice(-2);
              var endDate = event.endDate.getFullYear() + '-' + ('0' + (event.endDate.getMonth()+1)).slice(-2) + '-' + ('0' + event.endDate.getDate()).slice(-2);
              $('#event-modal input[name="event-index"]').val(event ? event.id : '');
              $('#event-modal input[name="event-name"]').val(event ? event.name : '');
              $('#event-modal input[name="event-start-date"]').val(event ? startDate : '');
              $('#event-modal input[name="event-end-date"]').val(event ? endDate : '');
              $('#event-modal').modal();
             }
             function HLdelete(event){
                $.ajax({    //create an ajax request to load_page.php
                  type: "GET",
                  url: "ajax/deleteHL.php",             
                  dataType: "html",   //expect html to be returned
                  data: { 'id': event.id, 'name': event.name },               
                  success: function(response){
                      alert(response);
                      getHLdata();
                  }
                });
             }

             function saveEvent() {
                var event = {
                    id: $('#event-modal input[name="event-index"]').val(),
                    name: $('#event-modal input[name="event-name"]').val(),
                    startDate: $('#event-modal input[name="event-start-date"]').val(),
                    endDate: $('#event-modal input[name="event-end-date"]').val()
                }

                $.ajax({    //create an ajax request to load_page.php
                  type: "GET",
                  url: "ajax/updateHL.php",             
                  dataType: "html",   //expect html to be returned
                  data: { 'id': event.id,'name': event.name,'startDate': event.startDate,'endDate': event.endDate },               
                  success: function(response){
                      alert(response);
                      getHLdata();
                  }
                });

                $('#event-modal').modal('hide');
             }

             function getHLdata(){
              $.ajax({
                url:"ajax/getHL.php",
                type:"POST",
                dataType:"html",
                success:function(a){
                  var input = JSON.parse(a);
                  var data = new Array();
                  var i = 0;
                  while (i<input.length){
                    var start = new Date(input[i]['startDate'] * 1000);
                    start.setDate(start.getDate()-1);
                    data.push({
                      id: input[i]['id'],
                      name: input[i]['name'],
                      location: input[i]['location'],
                      startDate: start,
                      endDate: new Date(input[i]['endDate'] * 1000)
                    });
                    i++;
                  }
                  loadKalHL(data);
                }
              });
             }

             function loadKalHL(HLdata){
               $('#KalHariLibur').calendar({
                  enableContextMenu: true,
                  enableRangeSelection: true,
                  contextMenuItems:[
                    {
                      text: 'Update',
                      click: HLedit
                    },
                    {
                      text: 'Delete',
                      click: HLdelete
                    }
                  ],
                  selectRange: function(e){
                    HLedit({ startDate: e.startDate, endDate: e.endDate });
                  },
                  mouseOnDay: function(e) {
                    if(e.events.length > 0) {
                      var content = '';
                          
                      for(var i in e.events) {
                        content += '<div class="event-tooltip-content">'
                          + '<div class="event-name" style="color:' + e.events[i].color + '">' + e.events[i].name + '</div>'
                          + '<div class="event-location">' + e.events[i].location + '</div>'
                          + '</div>';
                      }
                      
                      $(e.element).popover({ 
                        trigger: 'manual',
                        container: 'body',
                        html:true,
                        content: content
                      });
                          
                      $(e.element).popover('show');
                    }
                  },
                  mouseOutDay: function(e) {
                    if(e.events.length > 0) {
                      $(e.element).popover('hide');
                    }
                  },
                  dayContextMenu: function(e) {
                    $(e.element).popover('hide');
                  },
                  dataSource: HLdata
               });
             }
             $('#save-event').click(function() {
                saveEvent();
             }); 
           });
         </script>
      </div>
   </body>
</html>
<?php
}else{
   header("Location:login.php");
}
?>

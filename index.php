<?php
   session_start();
   include("config.php");

   if (isset($_SESSION['nip'])){
      $nip = $_SESSION['nip'];
      $level = $_SESSION['level'];
      $nama = $_SESSION['nama'];
      $email = $_SESSION['email'];

      $ALsql = "SELECT a.id_aktivitas, a.nama_aktivitas, a.durasi, k.nama_kategori FROM aktivitas AS a LEFT JOIN kategori AS k ON a.id_kategori = k.id_kategori";
      $ALquery = mysqli_query($db,$ALsql);
      $Catsql = "SELECT * FROM kategori";
      $Catquery = mysqli_query($db,$Catsql);

      if(count($_POST)>0) {
      }
?>
<!DOCTYPE HTML>
<html>
   <head>
   <meta charset="utf-8">
   <title>E-Jurnal Setwapres</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- Latest compiled and minified CSS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="dist/bootstrap-clockpicker.min.css">
   <link rel="stylesheet" type="text/css" href="css/a.css">

   </head>
   <body class="background">
      <div class="page">
         <?php 
            if ($level == '2'){
               include_once "views/staf/home.php";
            } else {
               include_once "views/admin/home.php";
            }
         ?>
         <script type="text/javascript" src="assets/js/jquery.min.js"></script>
         <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
         <script type="text/javascript" src="dist/bootstrap-clockpicker.min.js"></script>
         <script type="text/javascript" src="js/scripts.js"></script>
         <script type="text/javascript">
            var modal = document.getElementById('tCModal');
            var namaAct = document.getElementById('tcmNamaAct');
            var durasiAct = document.getElementById('tcmDurasi');
            var namaCat = document.getElementById('tcmNamaCat');
            var idInput = document.getElementsByClassName('tcm_IDAct')[0];
            var span = document.getElementsByClassName("close")[0];
            var ddc = document.getElementById("ddcContent");


            var ubah = document.querySelectorAll('.tombol_ubah')
            var ubah_ubah = document.querySelectorAll('.ubah_ubah')
            var forEach = Array.prototype.forEach;
            setActive_ubah(0)
            forEach.call(ubah, ubah_addListener)
            
            function ubah_addListener (r, m) {
            console.log('ubah')
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
               console.log(id + nama + durasi + cat);
               modal.style.display = "block";
               namaAct.innerHTML = nama;
               durasiAct.innerHTML = durasi;
               namaCat.innerHTML = cat;
               idInput.value = i;
            }
            span.onclick = function() {
               modal.style.display = "none";
            }
            window.onclick = function(event){
               if(event.target == modal){
                  modal.style.display = "none";
               }else if (!event.target.matches('.dropbtn')){
                  var ddc = document.getElementById("ddcContent");
                  if ( ddc.classList.contains("show")){
                     ddc.classList.toggle("show");
                  }
               }
            }

            function searchAct() {
               var input, filter, catFilter, catBtn, table, tr, td, i, showCount = 0;
               catBtn = document.getElementById("ddcBtn");
               input = document.getElementById("actSearch");
               filter = input.value.toUpperCase();
               table = document.getElementById("actTable");
               tr = table.getElementsByTagName("tr");

               if(catBtn.classList.contains("selectd")){
                  catFilter = catBtn.innerHTML;
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

               document.getElementById("testLabel").innerHTML = showCount;
               if( showCount <= 0 ){
                  tr[1].style.display = "";
                  if( filter != '' || catFilter != ''){
                     document.getElementById("actTableMessage").innerHTML = "No Result";
                  } else {
                     document.getElementById("actTableMessage").innerHTML = "Mulai pencarian dengan mengetik pada kolom search atau pilih kategori";
                  }
               } else {
                  tr[1].style.display = "none";
               }
            }

            function selectCat(cat) {
               catBtn = document.getElementById("ddcBtn");
               if(cat != 'Semua'){
                  catBtn.classList.add("selectd");
               } else {
                  catBtn.classList.toggle("selectd");
               }
               document.getElementById("ddcContent").classList.toggle("show");
               catBtn.innerHTML = cat;
               searchAct();
            }

            function validateSJ() {
               var volumetype = document.forms["FormSJ"]["volumeType"].value;

            }
         </script>
         <script type="text/javascript">
         $('.dropbtn').click(function(){
            document.getElementById("ddcContent").classList.toggle("show");
         })
         $('.clockpicker').clockpicker({
            donetext: 'Done'
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

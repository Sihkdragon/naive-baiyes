<?php
    include_once "settings.php"
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quicksand">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
</head>

<body>
<nav class="navbar navbar-light navbar-expand bg-light border-primary border rounded-0 navigation-clean">

    <div class="container"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"></button>
        <div style="height: 30px" class="collapse navbar-collapse" id="navcol-1"><img src="assets/logo_unikom.png" style="width: 130px;margin: -20px;margin-left: 0;"></a>
            <ul class="nav navbar-nav" style="padding-left: 30px">
                <li class="nav-item" role="presentation"><a class="nav-link" href="index.php">Data Latih</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="uji.php">Data Uji+Perhitungan</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="features-icons bg-light" style="padding-top: 20px;padding-bottom: 20px;">
    <div class="container">
        <h1>Naive Bayes</h1>
        <h2>Perhitungan Probabilitas</h2>
        <p>
            <?php
            $dataClassProbablities = $db->select("datatraining",'kemungkinan_disewa');
            $kelasrendah = 0;
            $kelassedang = 0;
            $kelastinggi = 0;
            $jumlahdata = 0;
            foreach ($dataClassProbablities as $d){
                if($d == 'rendah'){
                    $kelasrendah++;
                }
                elseif ($d == 'sedang'){
                    $kelassedang++;
                }
                elseif ($d == 'tinggi'){
                    $kelastinggi++;
                }
                $jumlahdata++;
            }

            echo "<div class='card card-body'>";
            echo "<h2>Jumlah total nilai frekuensi kelas: </h2><br>";
            echo "Rendah :".$kelasrendah."<br>";
            echo "Sedang :".$kelassedang."<br>";
            echo "Tinggi :".$kelastinggi."<br>";
            echo "=========================<br>";
            echo "Jumlah data : ".$jumlahdata."<br>";
            echo "=========================<br>";
            echo "</div>";
            echo "<div class='card card-body'>";
            echo "<h2>Probabilitas Kelas </h2><br>";
            $probabilitasKelasRendah = $kelasrendah/$jumlahdata;
            $probabilitasKelasSedang = $kelassedang/$jumlahdata;
            $probabilitasKelasTinggi = $kelastinggi/$jumlahdata;
            echo "Rendah : ".$kelasrendah."/"."$jumlahdata"." = ".$probabilitasKelasRendah."<br>";
            echo "Sedang : ".$kelassedang."/"."$jumlahdata"." = ".$probabilitasKelasSedang."<br>";
            echo "Tinggi : ".$kelastinggi."/"."$jumlahdata"." = ".$probabilitasKelasTinggi."<br>";
            echo "</div>";
            echo "<div class='card card-body'>";
            echo "<h2>Probabilitas Kondisi</h2><br>";
            echo "<h5>Fitur:</h5><br>";
            echo "<div class='card card-body'>";
            echo "<h3>[1] Jumlah Pegawai :</h3><br>";
            echo "<br>";
            $jtk = $db->select("datatraining",['jumlah_pegawai','kemungkinan_disewa']);
            echo "<h4>[*]Kelas rendah :</h4>";
            $jtksedikitrendah = 0;
            $jtksedangrendah = 0;
            $jtkbanyakrendah = 0;
            foreach ($jtk as $j){
                if($j['jumlah_pegawai'] == 'sedikit' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $jtksedikitrendah++;
                }
                elseif ($j['jumlah_pegawai'] == 'sedang' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $jtksedangrendah++;
                }
                elseif($j['jumlah_pegawai'] == 'banyak' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $jtkbanyakrendah++;
                }
            }
            $tambahanLaplace = 0;
            a:
            echo "Jumlah Pegawai sedikit & kemungkinan disewa rendah = ".$jtksedikitrendah."<br>";
            echo "Jumlah Pegawai sedang & kemungkinan disewa rendah = ".$jtksedangrendah."<br>";
            echo "Jumlah Pegawai banyak & kemungkinan disewa rendah = ".$jtkbanyakrendah."<br>";
            $jumlahsetelahtambahanlaplace = $kelasrendah+$tambahanLaplace;
            $probabilitasjtksedikitrendah = $jtksedikitrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasjtksedangrendah = $jtksedangrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasjtkbanyakrendah = $jtkbanyakrendah/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($jtksedikitrendah == 0 or $jtksedangrendah == 0 or $jtkbanyakrendah == 0){
                $jtksedikitrendah++;
                $jtksedangrendah++;
                $jtkbanyakrendah++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto a;
            }
            echo "Probabilitas Jumlah Pegawai sedikit & kemungkinan disewa rendah = ".$jtksedikitrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasjtksedikitrendah."<br>";
            echo "Probabilitas Jumlah Pegawai sedang & kemungkinan disewa rendah = ".$jtksedangrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasjtksedangrendah."<br>";
            echo "Probabilitas Jumlah Pegawai banyak & kemungkinan disewa rendah = ".$jtkbanyakrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasjtkbanyakrendah."<br>";
            echo "<h4>[*]Kelas sedang : </h4>";
            $jtksedikitsedang = 0;
            $jtksedangsedang = 0;
            $jtkbanyaksedang = 0;
            foreach ($jtk as $j){
                if($j['jumlah_pegawai'] == 'sedikit' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $jtksedikitsedang++;
                }
                elseif ($j['jumlah_pegawai'] == 'sedang' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $jtksedangsedang++;
                }
                elseif($j['jumlah_pegawai'] == 'banyak' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $jtkbanyaksedang++;
                }
            }
            $tambahanLaplace = 0;
            b:
            echo "Jumlah Pegawai sedikit & kemungkinan disewa sedang = ".$jtksedikitsedang."<br>";
            echo "Jumlah Pegawai sedang & kemungkinan disewa sedang = ".$jtksedangsedang."<br>";
            echo "Jumlah Pegawai banyak & kemungkinan disewa sedang = ".$jtkbanyaksedang."<br>";
            $jumlahsetelahtambahanlaplace = $kelassedang+$tambahanLaplace;
            $probabilitasjtksedikitsedang = $jtksedikitsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasjtksedangsedang = $jtksedangsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasjtkbanyaksedang = $jtkbanyaksedang/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($jtksedikitsedang == 0 or $jtksedangsedang == 0 or $jtkbanyaksedang == 0){
                $jtksedikitsedang++;
                $jtksedangsedang++;
                $jtkbanyaksedang++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto b;
            }
            echo "Probabilitas Jumlah Pegawai sedikit & kemungkinan disewa sedang = ".$jtksedikitsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasjtksedikitsedang."<br>";
            echo "Probabilitas Jumlah Pegawai sedang & kemungkinan disewa sedang = ".$jtksedangsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasjtksedangsedang."<br>";
            echo "Probabilitas Jumlah Pegawai banyak & kemungkinan disewa sedang = ".$jtkbanyaksedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasjtkbanyaksedang."<br>";
            echo "<h4>[*]Kelas tinggi  : <br></h4>";
            $jtksedikittinggi = 0;
            $jtksedangtinggi= 0;
            $jtkbanyaktinggi = 0;
            foreach ($jtk as $j){
                if($j['jumlah_pegawai'] == 'sedikit' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $jtksedikittinggi++;
                }
                elseif ($j['jumlah_pegawai'] == 'sedang' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $jtksedangtinggi++;
                }
                elseif($j['jumlah_pegawai'] == 'banyak' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $jtkbanyaktinggi++;
                }
            }
            $tambahanLaplace = 0;
            c:
            echo "Jumlah Pegawai sedikit & kemungkinan disewa tinggi = ".$jtksedikittinggi."<br>";
            echo "Jumlah Pegawai sedang & kemungkinan disewa tinggi = ".$jtksedangtinggi."<br>";
            echo "Jumlah Pegawai banyak & kemungkinan disewa tinggi = ".$jtkbanyaktinggi."<br>";
            $jumlahsetelahtambahanlaplace = $kelastinggi+$tambahanLaplace;
            $probabilitasjtksedikittinggi = $jtksedikittinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasjtksedangtinggi = $jtksedangtinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasjtkbanyaktinggi = $jtkbanyaktinggi/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($jtksedikittinggi == 0 or $jtksedangtinggi == 0 or $jtkbanyaktinggi == 0){
                $jtksedikittinggi++;
                $jtksedangtinggi++;
                $jtkbanyaktinggi++;
                $tambahanLaplace = $tambahanLaplace +3;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto c;
            }
            echo "Probabilitas Jumlah Pegawai sedikit & kemungkinan disewa tinggi = ".$jtksedikittinggi."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasjtksedikittinggi."<br>";
            echo "Probabilitas Jumlah Pegawai sedang & kemungkinan disewa tinggi = ".$jtksedangtinggi."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasjtksedangtinggi."<br>";
            echo "Probabilitas Jumlah Pegawai banyak & kemungkinan disewa tinggi = ".$jtkbanyaktinggi."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasjtkbanyaktinggi."<br>";
            echo "============================<br>";
            echo "</div>";
            ///////////////////////////n
            echo "<div class='card card-body'>";
            echo "<h3>[2] Luas Gedung :</h3><br>";
            echo "<br>";
            $jtk = $db->select("datatraining",['luas_gedung','kemungkinan_disewa']);
            echo "<h4>[*]Kelas rendah :</h4>";
            $gedungkecilrendah = 0;
            $gedungstandarrendah = 0;
            $gedungbesarrendah = 0;
            foreach ($jtk as $j){
                if($j['luas_gedung'] == 'kecil' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $gedungkecilrendah++;
                }
                elseif ($j['luas_gedung'] == 'standar' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $gedungstandarrendah++;
                }
                elseif($j['luas_gedung'] == 'besar' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $gedungbesarrendah++;
                }
            }
            $tambahanLaplace = 0;
            d:
            echo "Jumlah Luas Gedung kecil & kemungkinan disewa rendah = ".$gedungkecilrendah."<br>";
            echo "Jumlah Luas Gedung sedang & kemungkinan disewa rendah = ".$gedungstandarrendah."<br>";
            echo "Jumlah Luas Gedung besar  & kemungkinan disewa rendah = ".$gedungbesarrendah."<br>";
            $jumlahsetelahtambahanlaplace = $kelasrendah+$tambahanLaplace;
            $probabilitasgedungkecilrendah = $gedungkecilrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasgedungstandarrendah = $gedungstandarrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasgedungbesarrendah = $gedungbesarrendah/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($gedungkecilrendah == 0 or $gedungstandarrendah == 0 or $gedungbesarrendah == 0){
                $gedungkecilrendah++;
                $gedungstandarrendah++;
                $gedungbesarrendah++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto d;
            }
            echo "Probabilitas Luas Gedung kecil & kemungkinan disewa rendah = ".$gedungkecilrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasgedungkecilrendah."<br>";
            echo "Probabilitas Luas Gedung standar & kemungkinan disewa rendah = ".$gedungstandarrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasgedungstandarrendah."<br>";
            echo "Probabilitas Luas Gedung besar & kemungkinan disewa rendah = ".$gedungbesarrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasgedungbesarrendah."<br>";
            echo "<h4>[*]Kelas sedang : </h4>";
            $gedungkecilsedang = 0;
            $gedungstandarsedang = 0;
            $gedungbesarsedang = 0;
            foreach ($jtk as $j){
                if($j['luas_gedung'] == 'kecil' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $gedungkecilsedang++;
                }
                elseif ($j['luas_gedung'] == 'standar' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $gedungstandarsedang++;
                }
                elseif($j['luas_gedung'] == 'besar' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $gedungbesarsedang++;
                }
            }
            $tambahanLaplace = 0;
            e:
            echo "Jumlah Luas Gedung kecil  & kemungkinan disewa sedang = ".$gedungkecilsedang."<br>";
            echo "Jumlah Luas Gedung standar & kemungkinan disewa sedang = ".$gedungstandarsedang."<br>";
            echo "Jumlah Luas Gedung besar & kemungkinan disewa sedang = ".$gedungbesarsedang."<br>";
            $jumlahsetelahtambahanlaplace = $kelassedang+$tambahanLaplace;
            $probabilitasgedungkecilsedang = $gedungkecilsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasgedungstandarsedang = $gedungstandarsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasgedungbesarsedang = $gedungbesarsedang/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($gedungkecilsedang == 0 or $gedungstandarsedang == 0 or $gedungbesarsedang == 0){
                $gedungkecilsedang++;
                $gedungstandarsedang++;
                $gedungbesarsedang++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto e;
            }
            echo "Probabilitas Luas Gedung kecil & kemungkinan disewa sedang = ".$gedungkecilsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasgedungkecilsedang."<br>";
            echo "Probabilitas Luas Gedung standar & kemungkinan disewa sedang = ".$gedungstandarsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasgedungstandarsedang."<br>";
            echo "Probabilitas Luas Gedung besar& kemungkinan disewa sedang = ".$gedungbesarsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasgedungbesarsedang."<br>";
            echo "<h4>[*]Kelas tinggi  : <br></h4>";
            $gedungkeciltinggi = 0;
            $gedungstandartinggi = 0;
            $gedungbesartinggi = 0;
            foreach ($jtk as $j){
                if($j['luas_gedung'] == 'kecil' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $gedungkeciltinggi++;
                }
                elseif ($j['luas_gedung'] == 'standar' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $gedungstandartinggi++;
                }
                elseif($j['luas_gedung'] == 'besar' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $gedungbesartinggi++;
                }
            }
            $tambahanLaplace = 0;
            f:
            echo "Jumlah Luas Gedung kecil & kemungkinan disewa tinggi = ".$gedungkeciltinggi."<br>";
            echo "Jumlah Luas Gedung standar & kemungkinan disewa tinggi = ".$gedungstandartinggi."<br>";
            echo "Jumlah Luas Gedung besar & kemungkinan disewa tinggi = ".$gedungbesartinggi."<br>";
            $jumlahsetelahtambahanlaplace = $kelastinggi+$tambahanLaplace;
            $probabilitasgedungkeciltinggi = $gedungkeciltinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasgedungstandartinggi = $gedungstandartinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasgedungbesartinggi = $gedungbesartinggi/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($gedungkeciltinggi == 0 or $gedungstandartinggi == 0 or $gedungbesartinggi == 0){
                $gedungkeciltinggi++;
                $gedungstandartinggi++;
                $gedungbesartinggi++;
                $tambahanLaplace = $tambahanLaplace +3;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto f;
            }
            echo "Probabilitas Luas Gedung kecil & kemungkinan disewa tinggi  = ".$gedungkeciltinggi."/".$kelastinggi." = ".$probabilitasgedungkeciltinggi."<br>";
            echo "Probabilitas Luas Gedung standar & kemungkinan disewa tinggi  = ".$gedungstandartinggi."/".$kelastinggi." = ".$probabilitasgedungstandartinggi."<br>";
            echo "Probabilitas Luas Gedung besar & kemungkinan disewa tinggi = ".$gedungbesartinggi."/".$kelastinggi." = ".$probabilitasgedungbesartinggi."<br>";
            echo "============================<br>";
            echo "</div>";
            ///////////////////////////n
            echo "<div class='card card-body'>";
            echo "<h3>[3] Luas Parkir :</h3><br>";
            echo "<br>";
            $jtk = $db->select("datatraining",['luas_parkir','kemungkinan_disewa']);
            echo "<h4>[*]Kelas rendah :</h4>";
            $parkirkecilrendah = 0;
            $parkirsedangrendah = 0;
            $parkirbesarrendah = 0;
            foreach ($jtk as $j){
                if($j['luas_parkir'] == 'kecil' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $parkirkecilrendah++;
                }
                elseif ($j['luas_parkir'] == 'sedang' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $parkirsedangrendah++;
                }
                elseif($j['luas_parkir'] == 'besar' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $parkirbesarrendah++;
                }
            }
            $tambahanLaplace = 0;
            g:
            echo "Jumlah Luas Parkir kecil & kemungkinan disewa rendah = ".$parkirkecilrendah."<br>";
            echo "Jumlah Luas Parkir sedang & kemungkinan disewa rendah = ".$parkirsedangrendah."<br>";
            echo "Jumlah Luas Parkir besar  & kemungkinan disewa rendah = ".$parkirbesarrendah."<br>";
            $jumlahsetelahtambahanlaplace = $kelasrendah+$tambahanLaplace;
            $probabilitasparkirkecilrendah = $parkirkecilrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasparkirsedangrendah = $parkirsedangrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasparkirbesarrendah = $parkirbesarrendah/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($parkirkecilrendah == 0 or $parkirsedangrendah == 0 or $parkirbesarrendah == 0){
                $parkirkecilrendah++;
                $parkirsedangrendah++;
                $parkirbesarrendah++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto g;
            }
            echo "Probabilitas Luas Parkir kecil & kemungkinan disewa rendah = ".$parkirkecilrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasparkirkecilrendah."<br>";
            echo "Probabilitas Luas Parkir sedang & kemungkinan disewa rendah = ".$parkirsedangrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasparkirsedangrendah."<br>";
            echo "Probabilitas Luas Parkir besar & kemungkinan disewa rendah = ".$parkirbesarrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasparkirbesarrendah."<br>";
            echo "<h4>[*]Kelas sedang : </h4>";
            $parkirkecilsedang = 0;
            $parkirsedangsedang = 0;
            $parkirbesarsedang = 0;
            foreach ($jtk as $j){
                if($j['luas_parkir'] == 'kecil' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $parkirkecilsedang++;
                }
                elseif ($j['luas_parkir'] == 'sedang' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $parkirsedangsedang++;
                }
                elseif($j['luas_parkir'] == 'besar' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $parkirbesarsedang++;
                }
            }
            $tambahanLaplace = 0;
            h:
            echo "Jumlah Luas Parkir kecil  & kemungkinan disewa sedang = ".$parkirkecilsedang."<br>";
            echo "Jumlah Luas Parkir sedang & kemungkinan disewa sedang = ".$parkirsedangsedang."<br>";
            echo "Jumlah Luas Parkir besar & kemungkinan disewa sedang = ".$parkirbesarsedang."<br>";
            $jumlahsetelahtambahanlaplace = $kelassedang+$tambahanLaplace;
            $probabilitasparkirkecilsedang = $parkirkecilsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasparkirsedangsedang = $parkirsedangsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasparkirbesarsedang = $parkirbesarsedang/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($parkirkecilsedang == 0 or $parkirsedangsedang == 0 or $parkirbesarsedang == 0){
                $parkirkecilsedang++;
                $parkirsedangsedang++;
                $parkirbesarsedang++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto h;
            }
            echo "Probabilitas Luas Parkir kecil & kemungkinan disewa sedang = ".$parkirkecilsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasparkirkecilsedang."<br>";
            echo "Probabilitas Luas Parkir sedang & kemungkinan disewa sedang = ".$parkirsedangsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasparkirsedangsedang."<br>";
            echo "Probabilitas Luas Parkir besar& kemungkinan disewa sedang = ".$parkirbesarsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasparkirbesarsedang."<br>";
            echo "<h4>[*]Kelas tinggi  : <br></h4>";
            $parkirkeciltinggi = 0;
            $parkirsedangtinggi = 0;
            $parkirbesartinggi = 0;
            foreach ($jtk as $j){
                if($j['luas_parkir'] == 'kecil' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $parkirkeciltinggi++;
                }
                elseif ($j['luas_parkir'] == 'sedang' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $parkirsedangtinggi++;
                }
                elseif($j['luas_parkir'] == 'besar' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $parkirbesartinggi++;
                }
            }
            $tambahanLaplace = 0;
            i:
            echo "Jumlah Luas Parkir kecil & kemungkinan disewa tinggi = ".$parkirkeciltinggi."<br>";
            echo "Jumlah Luas Parkir sedang & kemungkinan disewa tinggi = ".$parkirsedangtinggi."<br>";
            echo "Jumlah Luas Parkir besar & kemungkinan disewa tinggi = ".$parkirbesartinggi."<br>";
            $jumlahsetelahtambahanlaplace = $kelastinggi+$tambahanLaplace;
            $probabilitasparkirkeciltinggi = $parkirkeciltinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasparkirsedangtinggi = $parkirsedangtinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasparkirbesartinggi = $parkirbesartinggi/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($parkirkeciltinggi == 0 or $parkirsedangtinggi == 0 or $parkirbesartinggi == 0){
                $parkirkeciltinggi++;
                $parkirsedangtinggi++;
                $parkirbesartinggi++;
                $tambahanLaplace = $tambahanLaplace +3;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto i;
            }
            echo "Probabilitas Luas Parkir kecil & kemungkinan disewa tinggi  = ".$jtksedikittinggi."/".$kelastinggi." = ".$probabilitasparkirkeciltinggi."<br>";
            echo "Probabilitas Luas Parkir sedang & kemungkinan disewa tinggi = ".$jtksedangtinggi."/".$kelastinggi." = ".$probabilitasparkirsedangtinggi."<br>";
            echo "Probabilitas Luas Parkir besar & kemungkinan disewa tinggi = ".$jtkbanyaktinggi."/".$kelastinggi." = ".$probabilitasparkirbesartinggi."<br>";
            echo "</div>";
            ///////////////////////////n
            echo "<div class='card card-body'>";
            echo "<h3>[4] daya listrik :</h3><br>";
            echo "<br>";
            $jtk = $db->select("datatraining",['daya_listrik','kemungkinan_disewa']);
            echo "<h4>[*]Kelas rendah :</h4>";
            $dayalistrikrendahrendah = 0;
            $dayalistriksedangrendah = 0;
            $dayalistriktinggirendah = 0;
            foreach ($jtk as $j){
                if($j['daya_listrik'] == 'rendah' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $dayalistrikrendahrendah++;
                }
                elseif ($j['daya_listrik'] == 'sedang' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $dayalistriksedangrendah++;
                }
                elseif($j['daya_listrik'] == 'tinggi' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $dayalistriktinggirendah++;
                }
            }
            $tambahanLaplace = 0;
            j:
            echo "Jumlah daya listrik rendah & kemungkinan disewa rendah = ".$dayalistrikrendahrendah."<br>";
            echo "Jumlah daya listrik sedang & kemungkinan disewa rendah = ".$dayalistriksedangrendah."<br>";
            echo "Jumlah daya listrik tinggi  & kemungkinan disewa rendah = ".$dayalistriktinggirendah."<br>";
            $jumlahsetelahtambahanlaplace = $kelasrendah+$tambahanLaplace;
            $probabilitasdayalistrikrendahrendah = $dayalistrikrendahrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasdayalistriksedangrendah = $dayalistriksedangrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasdayalistriktinggirendah = $dayalistriktinggirendah/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($dayalistrikrendahrendah == 0 or $dayalistriksedangrendah == 0 or $dayalistriktinggirendah == 0){
                $dayalistrikrendahrendah++;
                $dayalistriksedangrendah++;
                $dayalistriktinggirendah++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto j;
            }
            echo "Probabilitas daya listrik rendah & kemungkinan disewa rendah = ".$dayalistrikrendahrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasdayalistrikrendahrendah."<br>";
            echo "Probabilitas daya listrik sedang & kemungkinan disewa rendah = ".$dayalistriksedangrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasdayalistriksedangrendah."<br>";
            echo "Probabilitas daya listrik tinggi & kemungkinan disewa rendah = ".$dayalistriktinggirendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasdayalistriktinggirendah."<br>";
            echo "<h4>[*]Kelas sedang : </h4>";
            $dayalistrikrendahsedang = 0;
            $dayalistriksedangsedang = 0;
            $dayalistriktinggisedang = 0;
            foreach ($jtk as $j){
                if($j['daya_listrik'] == 'rendah' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $dayalistrikrendahsedang++;
                }
                elseif ($j['daya_listrik'] == 'sedang' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $dayalistriksedangsedang++;
                }
                elseif($j['daya_listrik'] == 'tinggi' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $dayalistriktinggisedang++;
                }
            }
            $tambahanLaplace = 0;
            k:
            echo "Jumlah daya listrik rendah  & kemungkinan disewa sedang = ".$dayalistrikrendahsedang."<br>";
            echo "Jumlah daya listrik sedang & kemungkinan disewa sedang = ".$dayalistriksedangsedang."<br>";
            echo "Jumlah daya listrik tinggi & kemungkinan disewa sedang = ".$dayalistriktinggisedang."<br>";
            $jumlahsetelahtambahanlaplace = $kelassedang+$tambahanLaplace;
            $probabilitasdayalistrikrendahsedang = $dayalistrikrendahsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasdayalistriksedangsedang = $dayalistriksedangsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasdayalistriktinggisedang = $dayalistriktinggisedang/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($dayalistrikrendahsedang == 0 or $dayalistriksedangsedang == 0 or $dayalistriktinggisedang == 0){
                $dayalistrikrendahsedang++;
                $dayalistriksedangsedang++;
                $dayalistriktinggisedang++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto k;
            }
            echo "Probabilitas daya listrik rendah & kemungkinan disewa sedang = ".$dayalistrikrendahsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasdayalistrikrendahsedang."<br>";
            echo "Probabilitas daya listrik sedang & kemungkinan disewa sedang = ".$dayalistriksedangsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasdayalistriksedangsedang."<br>";
            echo "Probabilitas daya listrik tinggi& kemungkinan disewa sedang = ".$dayalistriktinggisedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasdayalistriktinggisedang."<br>";
            echo "<h4>[*]Kelas tinggi  : <br></h4>";
            $dayalistrikrendahtinggi = 0;
            $dayalistriksedangtinggi = 0;
            $dayalistriktinggitinggi = 0;
            foreach ($jtk as $j){
                if($j['daya_listrik'] == 'rendah' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $dayalistrikrendahtinggi++;
                }
                elseif ($j['daya_listrik'] == 'sedang' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $dayalistriksedangtinggi++;
                }
                elseif($j['daya_listrik'] == 'tinggi' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $dayalistriktinggitinggi++;
                }
            }
            $tambahanLaplace = 0;
            l:
            echo "Jumlah daya listrik rendah & kemungkinan disewa tinggi = ".$dayalistrikrendahtinggi."<br>";
            echo "Jumlah daya listrik sedang & kemungkinan disewa tinggi = ".$dayalistriksedangtinggi."<br>";
            echo "Jumlah daya listrik tinggi & kemungkinan disewa tinggi = ".$dayalistriktinggitinggi."<br>";
            $jumlahsetelahtambahanlaplace = $kelastinggi+$tambahanLaplace;
            $probabilitasdayalistrikrendahtinggi = $dayalistrikrendahtinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasdayalistriksedangtinggi = $dayalistriksedangtinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasdayalistriktinggitinggi = $dayalistriktinggitinggi/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($dayalistrikrendahtinggi == 0 or $dayalistriksedangtinggi == 0 or $dayalistriktinggitinggi == 0){
                $dayalistrikrendahtinggi++;
                $dayalistriksedangtinggi++;
                $dayalistriktinggitinggi++;
                $tambahanLaplace = $tambahanLaplace +3;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto l;
            }
            echo "Probabilitas daya listrik rendah & kemungkinan disewa tinggi  = ".$dayalistrikrendahtinggi."/".$kelastinggi." = ".$probabilitasdayalistrikrendahtinggi."<br>";
            echo "Probabilitas daya listrik sedang & kemungkinan disewa tinggi  = ".$dayalistriksedangtinggi."/".$kelastinggi." = ".$probabilitasdayalistriksedangtinggi."<br>";
            echo "Probabilitas daya listrik tinggi & kemungkinan disewa tinggi = ".$dayalistriktinggitinggi."/".$kelastinggi." = ".$probabilitasdayalistriktinggitinggi."<br>";
            echo "============================<br>";
            echo "</div>";
            ///////////////////////////n
            echo "<div class='card card-body'>";
            echo "<h3>[5] Perlengkapan audio yang dimiliki :</h3><br>";
            echo "<br>";
            $jtk = $db->select("datatraining",['perlengkapan_yang_dimiliki','kemungkinan_disewa']);
            echo "<h4>[*]Kelas rendah :</h4>";
            $perlengkapanrendahrendah = 0;
            $perlengkapansedangrendah = 0;
            $perlengkapantinggirendah = 0;
            foreach ($jtk as $j){
                if($j['perlengkapan_yang_dimiliki'] == 'rendah' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $perlengkapanrendahrendah++;
                }
                elseif ($j['perlengkapan_yang_dimiliki'] == 'sedang' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $perlengkapansedangrendah++;
                }
                elseif($j['perlengkapan_yang_dimiliki'] == 'tinggi' AND $j['kemungkinan_disewa'] == 'rendah'){
                    $perlengkapantinggirendah++;
                }
            }
            $tambahanLaplace = 0;
            m:
            echo "Jumlah Perlengkapan yang dimiliki rendah & kemungkinan disewa rendah = ".$perlengkapanrendahrendah."<br>";
            echo "Jumlah Perlengkapan yang dimiliki sedang & kemungkinan disewa rendah = ".$perlengkapansedangrendah."<br>";
            echo "Jumlah Perlengkapan yang dimiliki tinggi  & kemungkinan disewa rendah = ".$perlengkapantinggirendah."<br>";
            $jumlahsetelahtambahanlaplace = $kelasrendah+$tambahanLaplace;
            $probabilitasperlengkapanrendahrendah = $perlengkapanrendahrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasperlengkapansedangrendah = $perlengkapansedangrendah/$jumlahsetelahtambahanlaplace;
            $probabilitasperlengkapantinggirendah = $perlengkapantinggirendah/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($perlengkapanrendahrendah == 0 or $perlengkapansedangrendah == 0 or $perlengkapantinggirendah == 0){
                $perlengkapanrendahrendah++;
                $perlengkapansedangrendah++;
                $perlengkapantinggirendah++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto m;
            }
            echo "Probabilitas Perlengkapan yang dimiliki rendah & kemungkinan disewa rendah = ".$perlengkapanrendahrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasperlengkapanrendahrendah."<br>";
            echo "Probabilitas Perlengkapan yang dimiliki sedang & kemungkinan disewa rendah = ".$perlengkapansedangrendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasperlengkapansedangrendah."<br>";
            echo "Probabilitas Perlengkapan yang dimiliki tinggi & kemungkinan disewa rendah = ".$perlengkapantinggirendah."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasperlengkapantinggirendah."<br>";
            echo "<h4>[*]Kelas sedang : </h4>";
            $perlengkapanrendahsedang = 0;
            $perlengkapansedangsedang = 0;
            $perlengkapantinggisedang = 0;
            foreach ($jtk as $j){
                if($j['perlengkapan_yang_dimiliki'] == 'rendah' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $perlengkapanrendahsedang++;
                }
                elseif ($j['perlengkapan_yang_dimiliki'] == 'sedang' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $perlengkapansedangsedang++;
                }
                elseif($j['perlengkapan_yang_dimiliki'] == 'tinggi' AND $j['kemungkinan_disewa'] == 'sedang'){
                    $perlengkapantinggisedang++;
                }
            }
            $tambahanLaplace = 0;
            n:
            echo "Jumlah Perlengkapan yang dimiliki rendah  & kemungkinan disewa sedang = ".$perlengkapanrendahsedang."<br>";
            echo "Jumlah Perlengkapan yang dimiliki sedang & kemungkinan disewa sedang = ".$perlengkapansedangsedang."<br>";
            echo "Jumlah Perlengkapan yang dimiliki tinggi & kemungkinan disewa sedang = ".$perlengkapantinggisedang."<br>";
            $jumlahsetelahtambahanlaplace = $kelassedang+$tambahanLaplace;
            $probabilitasperlengkapanrendahsedang = $perlengkapanrendahsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasperlengkapansedangsedang = $perlengkapansedangsedang/$jumlahsetelahtambahanlaplace;
            $probabilitasperlengkapantinggisedang = $perlengkapantinggisedang/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($perlengkapanrendahsedang == 0 or $perlengkapansedangsedang == 0 or $perlengkapantinggisedang == 0){
                $perlengkapanrendahsedang++;
                $perlengkapansedangsedang++;
                $perlengkapantinggisedang++;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                $tambahanLaplace = $tambahanLaplace +3;
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto n;
            }
            echo "Probabilitas Perlengkapan yang dimiliki rendah & kemungkinan disewa sedang = ".$perlengkapanrendahsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasperlengkapanrendahsedang."<br>";
            echo "Probabilitas Perlengkapan yang dimiliki sedang & kemungkinan disewa sedang = ".$perlengkapansedangsedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasperlengkapansedangsedang."<br>";
            echo "Probabilitas Perlengkapan yang dimiliki tinggi& kemungkinan disewa sedang = ".$perlengkapantinggisedang."/".$jumlahsetelahtambahanlaplace." = ".$probabilitasperlengkapantinggisedang."<br>";
            echo "<h4>[*]Kelas tinggi  : <br></h4>";
            $perlengkapanrendahtinggi = 0;
            $perlengkapansedangtinggi = 0;
            $perlengkapantinggitinggi = 0;
            foreach ($jtk as $j){
                if($j['perlengkapan_yang_dimiliki'] == 'rendah' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $perlengkapanrendahtinggi++;
                }
                elseif ($j['perlengkapan_yang_dimiliki'] == 'sedang' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $perlengkapansedangtinggi++;
                }
                elseif($j['perlengkapan_yang_dimiliki'] == 'tinggi' AND $j['kemungkinan_disewa'] == 'tinggi'){
                    $perlengkapantinggitinggi++;
                }
            }
            $tambahanLaplace = 0;
            o:
            echo "Jumlah Perlengkapan yang dimiliki rendah & kemungkinan disewa tinggi = ".$perlengkapanrendahtinggi."<br>";
            echo "Jumlah Perlengkapan yang dimiliki sedang & kemungkinan disewa tinggi = ".$perlengkapansedangtinggi."<br>";
            echo "Jumlah Perlengkapan yang dimiliki tinggi & kemungkinan disewa tinggi = ".$perlengkapantinggitinggi."<br>";
            $jumlahsetelahtambahanlaplace = $kelastinggi+$tambahanLaplace;
            $probabilitasperlengkapanrendahtinggi = $perlengkapanrendahtinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasperlengkapansedangtinggi = $perlengkapansedangtinggi/$jumlahsetelahtambahanlaplace;
            $probabilitasperlengkapantinggitinggi = $perlengkapantinggitinggi/$jumlahsetelahtambahanlaplace;
            //laplace correction
            if($perlengkapanrendahtinggi == 0 or $perlengkapansedangtinggi == 0 or $perlengkapantinggitinggi == 0){
                $perlengkapanrendahtinggi++;
                $perlengkapansedangtinggi++;
                $perlengkapantinggitinggi++;
                $tambahanLaplace = $tambahanLaplace +3;
                //diasumsikan ada 1 lagi dataset untuk masing masing nilai
                echo "Data mengalami laplace correction. Data baru setelah laplace correction :<br>";
                goto o;
            }
            echo "Probabilitas Perlengkapan yang dimiliki rendah & kemungkinan disewa tinggi  = ".$perlengkapanrendahtinggi."/".$kelastinggi." = ".$probabilitasperlengkapanrendahtinggi."<br>";
            echo "Probabilitas Perlengkapan yang dimiliki sedang & kemungkinan disewa tinggi  = ".$perlengkapansedangtinggi."/".$kelastinggi." = ".$probabilitasperlengkapansedangtinggi."<br>";
            echo "Probabilitas Perlengkapan yang dimiliki tinggi & kemungkinan disewa tinggi = ".$perlengkapantinggitinggi."/".$kelastinggi." = ".$probabilitasperlengkapantinggitinggi."<br>";
            echo "</div>";
            echo "</div>";///////////////////////////
            ?>
        </p>
        <h2>Data Uji: </h2>
        <table class="table table-bordered" width="100%">
            <thead style="text-align: center; font-weight: bold">
            <tr>
                <td>No</td>
                <td>Jumlah Pegawai</td>
                <td>Luas Gedung</td>
                <td>Luas Parkir</td>
                <td>Daya Listrik</td>
                <td>Perlengkapan audio yang dimiliki</td>
            </tr>
            </thead>
            <tbody>
            <?php
            $data = $db->select("datauji","*");
            foreach ($data as $n => $d){
                ?>
                <tr>
                    <td style="text-align: center"><?php echo $n+1 ?></td>
                    <td><?php echo $d['jumlah_pegawai'] ?></td>
                    <td><?php echo $d['luas_gedung'] ?></td>
                    <td><?php echo $d['luas_parkir'] ?></td>
                    <td><?php echo $d['daya_listrik'] ?></td>
                    <td><?php echo $d['perlengkapan_yang_dimiliki'] ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <p>
            <?php
            foreach ($data as $n => $d){
                $a = $n+1;
                echo "Perhitungan untuk Data uji No :".$a."<br>";
                $pegawai = $d['jumlah_pegawai'];
                $gedung = $d['luas_gedung'];
                $parkir = $d['luas_parkir'];
                $daya = $d['daya_listrik'];
                $perlengkapan = $d['perlengkapan_yang_dimiliki'];
                echo "<br>";
//memilih probabilitas
                $rendah = [];
                $sedang = [];
                $tinggi = [];
//pegawai
                if($pegawai == 'sedikit'){
                    array_push($rendah,$probabilitasjtksedikitrendah);
                    array_push($sedang,$probabilitasjtksedikitsedang);
                    array_push($tinggi,$probabilitasjtksedikittinggi);
                }
                elseif ($pegawai =='sedang'){
                    array_push($rendah,$probabilitasjtksedangrendah);
                    array_push($sedang,$probabilitasjtksedangsedang);
                    array_push($tinggi,$probabilitasjtksedangtinggi);
                }
                elseif($pegawai=='banyak'){
                    array_push($rendah,$probabilitasjtkbanyakrendah);
                    array_push($sedang,$probabilitasjtkbanyaksedang);
                    array_push($tinggi,$probabilitasjtkbanyaktinggi);
                }
//Luas Gedung
                if($gedung == 'kecil'){
                    array_push($rendah,$probabilitasgedungkecilrendah);
                    array_push($sedang,$probabilitasgedungkecilsedang);
                    array_push($tinggi,$probabilitasgedungkeciltinggi);
                }
                elseif ($gedung =='standar'){
                    array_push($rendah,$probabilitasgedungstandarrendah);
                    array_push($sedang,$probabilitasgedungstandarsedang);
                    array_push($tinggi,$probabilitasgedungstandartinggi);
                }
                elseif($gedung=='besar'){
                    array_push($rendah,$probabilitasgedungbesarrendah);
                    array_push($sedang,$probabilitasgedungbesarsedang);
                    array_push($tinggi,$probabilitasgedungbesartinggi);
                }
//parkir
                if($parkir == 'kecil'){
                    array_push($rendah,$probabilitasparkirkecilrendah);
                    array_push($sedang,$probabilitasparkirkecilsedang);
                    array_push($tinggi,$probabilitasparkirkeciltinggi);
                }
                elseif ($parkir =='sedang'){
                    array_push($rendah,$probabilitasparkirsedangrendah);
                    array_push($sedang,$probabilitasparkirsedangsedang);
                    array_push($tinggi,$probabilitasparkirsedangtinggi);
                }
                elseif($parkir=='besar'){
                    array_push($rendah,$probabilitasparkirbesarrendah);
                    array_push($sedang,$probabilitasparkirbesarsedang);
                    array_push($tinggi,$probabilitasparkirbesartinggi);
                }
//daya
                if($daya == 'rendah'){
                    array_push($rendah,$probabilitasdayalistrikrendahrendah);
                    array_push($sedang,$probabilitasdayalistrikrendahsedang);
                    array_push($tinggi,$probabilitasdayalistrikrendahtinggi);
                }
                elseif ($daya =='sedang'){
                    array_push($rendah,$probabilitasdayalistriksedangrendah);
                    array_push($sedang,$probabilitasdayalistriksedangsedang);
                    array_push($tinggi,$probabilitasdayalistriksedangtinggi);
                }
                elseif($daya=='tinggi'){
                    array_push($rendah,$probabilitasdayalistriktinggirendah);
                    array_push($sedang,$probabilitasdayalistriktinggisedang);
                    array_push($tinggi,$probabilitasdayalistriktinggitinggi);
                }
//perlengkapan
                if($perlengkapan == 'rendah'){
                    array_push($rendah,$probabilitasperlengkapanrendahrendah);
                    array_push($sedang,$probabilitasperlengkapanrendahsedang);
                    array_push($tinggi,$probabilitasperlengkapanrendahtinggi);
                }
                elseif ($perlengkapan =='sedang'){
                    array_push($rendah,$probabilitasperlengkapansedangrendah);
                    array_push($sedang,$probabilitasperlengkapansedangsedang);
                    array_push($tinggi,$probabilitasperlengkapansedangtinggi);
                }
                elseif($perlengkapan=='tinggi'){
                    array_push($rendah,$probabilitasperlengkapantinggirendah);
                    array_push($sedang,$probabilitasperlengkapantinggisedang);
                    array_push($tinggi,$probabilitasperlengkapantinggitinggi);
                }
                $kalimat_rendah = "";
                $kalimat_sedang = "";
                $kalimat_tinggi = "";
                $jumlah_rendah = 0;
                $jumlah_sedang = 0;
                $jumlah_tinggi = 0;
                for ($a=0;$a<5;$a++){
                    if($a == 0){
                        $kalimat_rendah = $kalimat_rendah.$rendah[$a];
                        $kalimat_sedang = $kalimat_sedang.$sedang[$a];
                        $kalimat_tinggi = $kalimat_tinggi.$tinggi[$a];
                        $jumlah_rendah = $rendah[$a];
                        $jumlah_sedang = $sedang[$a];
                        $jumlah_tinggi = $tinggi[$a];
                    }
                    else{
                        $kalimat_rendah = $kalimat_rendah."*".$rendah[$a];
                        $kalimat_sedang= $kalimat_sedang."*".$sedang[$a];
                        $kalimat_tinggi = $kalimat_tinggi."*".$tinggi[$a];
                        $jumlah_rendah = $jumlah_rendah*$rendah[$a];
                        $jumlah_sedang = $jumlah_sedang*$sedang[$a];
                        $jumlah_tinggi = $jumlah_tinggi*$tinggi[$a];
                    }
                }
                $jumlah_rendah = $jumlah_rendah*$probabilitasKelasRendah;
                $jumlah_sedang = $jumlah_sedang*$probabilitasKelasSedang;
                $jumlah_tinggi = $jumlah_tinggi*$probabilitasKelasTinggi;
                echo "Perhitungan :<br>";
                echo "Rendah = (".$kalimat_rendah.")*".$probabilitasKelasRendah." = ".$jumlah_rendah."<br>";
                echo "Sedang = (".$kalimat_sedang.")*".$probabilitasKelasSedang." = ".$jumlah_sedang."<br>";
                echo "Tinggi = (".$kalimat_tinggi.")*".$probabilitasKelasTinggi." = ".$jumlah_tinggi."<br>";

                if($jumlah_rendah>$jumlah_sedang AND $jumlah_rendah>$jumlah_tinggi){
                    echo "Dari perhitungan tersebut bahwa ".$jumlah_rendah." yang memiliki probabilitas paling tinggi, sehingga prediksinya adalah <b>RENDAH</b>";
                }
                elseif($jumlah_sedang>$jumlah_rendah AND $jumlah_sedang>$jumlah_tinggi){
                    echo "Dari perhitungan tersebut bahwa ".$jumlah_sedang." yang memiliki probabilitas paling tinggi, sehingga prediksinya adalah <b>SEDANG</b>";
                }
                elseif($jumlah_tinggi>$jumlah_rendah AND $jumlah_tinggi>$jumlah_sedang){
                    echo "Dari perhitungan tersebut bahwa ".$jumlah_tinggi." probabilitas paling tinggi, sehingga prediksinya adalah <b>TINGGI</b>";
                }
                echo"<br><br>";
            }
            ?>
        </p>
    </div>
</section>
<section class="showcase"></section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="assets/script.min.js"></script>
</body>

</html>

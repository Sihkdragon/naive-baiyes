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
        <h2>Data Latih</h2>
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
            echo "Jumlah total nilai frekuensi kelas : <br>";
            echo "Rendah : ".$kelasrendah."<br>";
            echo "Sedang : ".$kelassedang."<br>";
            echo "Tinggi : ".$kelastinggi."<br>";
            echo "=========================<br>";
            echo "Jumlah data : ".$jumlahdata."<br>";
            ?>
        </p>
        <table class="table table-bordered" width="100%">
            <thead style="text-align: center; font-weight: bold">
                <tr>
                    <td rowspan="2">No</td>
                    <td colspan="5">Fitur</td>
                    <td>Kelas</td>
                </tr>
                <tr>
                    <td>Jumlah Pegawai</td>
                    <td>Luas Gedung</td>
                    <td>Luas Parkir</td>
                    <td>Daya Listrik</td>
                    <td>Perlengkapan audio yang dimiliki</td>
                    <td>Kemungkinan Disewa</td>
                </tr>
            </thead>
            <tbody>
            <?php
                $data = $db->select("datatraining","*");
                foreach ($data as $n => $d){
                    ?>
                    <tr>
                        <td style="text-align: center"><?php echo $n+1 ?></td>
                        <td><?php echo $d['jumlah_pegawai'] ?></td>
                        <td><?php echo $d['luas_gedung'] ?></td>
                        <td><?php echo $d['luas_parkir'] ?></td>
                        <td><?php echo $d['daya_listrik'] ?></td>
                        <td><?php echo $d['perlengkapan_yang_dimiliki'] ?></td>
                        <td><?php echo $d['kemungkinan_disewa'] ?></td>
                    </tr>
                    <?php
                }
            ?>
            </tbody>
        </table>
    </div>
</section>
<section class="showcase"></section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="assets/script.min.js"></script>
</body>

</html>

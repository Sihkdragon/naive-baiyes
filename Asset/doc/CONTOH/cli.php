<?php

use Medoo\Medoo;

require "vendor/autoload.php";

$nama_database = "naive-bayes";
$username_database = "root";
$password_database = "anyand32";
$tipe_database = "mysql";
$hostname_database = "localhost";

$db = new Medoo([
    'database_type' => $tipe_database,
    'database_name' => $nama_database,
    'server' => $hostname_database,
    'username' => $username_database,
    'password' => $password_database
]);
echo "===================================\n";
echo "Prediksi Penyewaan Gedung Serbaguna\n";
echo "     Menggunakan Naive Bayes       \n";
echo "===================================\n";
echo "Perhitungan probabilitias kelas\nKelas : 'rendah','sedang','tinggi'\n=========================\n";
$dataClassProbablities = $db->select("datatraining", 'kemungkinan_disewa');
$kelasrendah = 0;
$kelassedang = 0;
$kelastinggi = 0;
$jumlahdata = 0;
foreach ($dataClassProbablities as $d) {
    if ($d == 'rendah') {
        $kelasrendah++;
    } elseif ($d == 'sedang') {
        $kelassedang++;
    } elseif ($d == 'tinggi') {
        $kelastinggi++;
    }
    $jumlahdata++;
}
echo "Jumlah total nilai frekuensi : \n";
echo "Rendah :" . $kelasrendah . "\n";
echo "Sedang :" . $kelassedang . "\n";
echo "Tinggi :" . $kelastinggi . "\n";
echo "=========================\n";
echo "Jumlah data : " . $jumlahdata . "\n";
echo "=========================\n";
echo "Probabilitas Kelas \n";
$probabilitasKelasRendah = $kelasrendah / $jumlahdata;
$probabilitasKelasSedang = $kelassedang / $jumlahdata;
$probabilitasKelasTinggi = $kelastinggi / $jumlahdata;
echo "Rendah : " . $kelasrendah . "/" . "$jumlahdata" . " = " . $probabilitasKelasRendah . "\n";
echo "Sedang : " . $kelassedang . "/" . "$jumlahdata" . " = " . $probabilitasKelasSedang . "\n";
echo "Tinggi : " . $kelastinggi . "/" . "$jumlahdata" . " = " . $probabilitasKelasTinggi . "\n";
echo "=========================\n";
echo "Probabilitas Kondisi\n";
echo "=========================\n";
echo "Fitur:\n";
echo "[1] Jumlah Pegawai :\n";
echo "Jumlah Total Nilai Frekuensi :\n";
$jtk = $db->select("datatraining", ['jumlah_pegawai', 'kemungkinan_disewa']);
echo "[*]Kelas rendah :\n";
$jtksedikitrendah = 0;
$jtksedangrendah = 0;
$jtkbanyakrendah = 0;
foreach ($jtk as $j) {
    if ($j['jumlah_pegawai'] == 'sedikit' and $j['kemungkinan_disewa'] == 'rendah') {
        $jtksedikitrendah++;
    } elseif ($j['jumlah_pegawai'] == 'sedang' and $j['kemungkinan_disewa'] == 'rendah') {
        $jtksedangrendah++;
    } elseif ($j['jumlah_pegawai'] == 'banyak' and $j['kemungkinan_disewa'] == 'rendah') {
        $jtkbanyakrendah++;
    }
}
$tambahanLaplace = 0;
a:
echo "Jumlah Pegawai sedikit & kemungkinan disewa rendah = " . $jtksedikitrendah . "\n";
echo "Jumlah Pegawai sedang & kemungkinan disewa rendah = " . $jtksedangrendah . "\n";
echo "Jumlah Pegawai banyak & kemungkinan disewa rendah = " . $jtkbanyakrendah . "\n";
$jumlahsetelahtambahanlaplace = $kelasrendah + $tambahanLaplace;
$probabilitasjtksedikitrendah = $jtksedikitrendah / $jumlahsetelahtambahanlaplace;
$probabilitasjtksedangrendah = $jtksedangrendah / $jumlahsetelahtambahanlaplace;
$probabilitasjtkbanyakrendah = $jtkbanyakrendah / $jumlahsetelahtambahanlaplace;

//laplace correction
if ($jtksedikitrendah == 0 or $jtksedangrendah == 0 or $jtkbanyakrendah == 0) {
    $jtksedikitrendah++;
    $jtksedangrendah++;
    $jtkbanyakrendah++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto a;
}
echo "Probabilitas Jumlah Pegawai sedikit & kemungkinan disewa rendah = " . $jtksedikitrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasjtksedikitrendah . "\n";
echo "Probabilitas Jumlah Pegawai sedang & kemungkinan disewa rendah = " . $jtksedangrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasjtksedangrendah . "\n";
echo "Probabilitas Jumlah Pegawai banyak & kemungkinan disewa rendah = " . $jtkbanyakrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasjtkbanyakrendah . "\n";
echo "[*]Kelas sedang : \n";
$jtksedikitsedang = 0;
$jtksedangsedang = 0;
$jtkbanyaksedang = 0;
foreach ($jtk as $j) {
    if ($j['jumlah_pegawai'] == 'sedikit' and $j['kemungkinan_disewa'] == 'sedang') {
        $jtksedikitsedang++;
    } elseif ($j['jumlah_pegawai'] == 'sedang' and $j['kemungkinan_disewa'] == 'sedang') {
        $jtksedangsedang++;
    } elseif ($j['jumlah_pegawai'] == 'banyak' and $j['kemungkinan_disewa'] == 'sedang') {
        $jtkbanyaksedang++;
    }
}
$tambahanLaplace = 0;
b:
echo "Jumlah Pegawai sedikit & kemungkinan disewa sedang = " . $jtksedikitsedang . "\n";
echo "Jumlah Pegawai sedang & kemungkinan disewa sedang = " . $jtksedangsedang . "\n";
echo "Jumlah Pegawai banyak & kemungkinan disewa sedang = " . $jtkbanyaksedang . "\n";
$jumlahsetelahtambahanlaplace = $kelassedang + $tambahanLaplace;
$probabilitasjtksedikitsedang = $jtksedikitsedang / $jumlahsetelahtambahanlaplace;
$probabilitasjtksedangsedang = $jtksedangsedang / $jumlahsetelahtambahanlaplace;
$probabilitasjtkbanyaksedang = $jtkbanyaksedang / $jumlahsetelahtambahanlaplace;

//laplace correction
if ($jtksedikitsedang == 0 or $jtksedangsedang == 0 or $jtkbanyaksedang == 0) {
    $jtksedikitsedang++;
    $jtksedangsedang++;
    $jtkbanyaksedang++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto b;
}
echo "Probabilitas Jumlah Pegawai sedikit & kemungkinan disewa sedang = " . $jtksedikitsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasjtksedikitsedang . "\n";
echo "Probabilitas Jumlah Pegawai sedang & kemungkinan disewa sedang = " . $jtksedangsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasjtksedangsedang . "\n";
echo "Probabilitas Jumlah Pegawai banyak & kemungkinan disewa sedang = " . $jtkbanyaksedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasjtkbanyaksedang . "\n";
echo "[*]Kelas tinggi  : \n";
$jtksedikittinggi = 0;
$jtksedangtinggi = 0;
$jtkbanyaktinggi = 0;
foreach ($jtk as $j) {
    if ($j['jumlah_pegawai'] == 'sedikit' and $j['kemungkinan_disewa'] == 'tinggi') {
        $jtksedikittinggi++;
    } elseif ($j['jumlah_pegawai'] == 'sedang' and $j['kemungkinan_disewa'] == 'tinggi') {
        $jtksedangtinggi++;
    } elseif ($j['jumlah_pegawai'] == 'banyak' and $j['kemungkinan_disewa'] == 'tinggi') {
        $jtkbanyaktinggi++;
    }
}
$tambahanLaplace = 0;
c:
echo "Jumlah Pegawai sedikit & kemungkinan disewa tinggi = " . $jtksedikittinggi . "\n";
echo "Jumlah Pegawai sedang & kemungkinan disewa tinggi = " . $jtksedangtinggi . "\n";
echo "Jumlah Pegawai banyak & kemungkinan disewa tinggi = " . $jtkbanyaktinggi . "\n";
$jumlahsetelahtambahanlaplace = $kelastinggi + $tambahanLaplace;
$probabilitasjtksedikittinggi = $jtksedikittinggi / $jumlahsetelahtambahanlaplace;
$probabilitasjtksedangtinggi = $jtksedangtinggi / $jumlahsetelahtambahanlaplace;
$probabilitasjtkbanyaktinggi = $jtkbanyaktinggi / $jumlahsetelahtambahanlaplace;

//laplace correction
if ($jtksedikittinggi == 0 or $jtksedangtinggi == 0 or $jtkbanyaktinggi == 0) {
    $jtksedikittinggi++;
    $jtksedangtinggi++;
    $jtkbanyaktinggi++;
    $tambahanLaplace = $tambahanLaplace + 3;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto c;
}
echo "Probabilitas Jumlah Pegawai sedikit & kemungkinan disewa tinggi = " . $jtksedikittinggi . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasjtksedikittinggi . "\n";
echo "Probabilitas Jumlah Pegawai sedang & kemungkinan disewa tinggi = " . $jtksedangtinggi . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasjtksedangtinggi . "\n";
echo "Probabilitas Jumlah Pegawai banyak & kemungkinan disewa tinggi = " . $jtkbanyaktinggi . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasjtkbanyaktinggi . "\n";
echo "============================\n";
///////////////////////////n
echo "[2] Luas Gedung :\n";
echo "Jumlah Total Nilai Frekuensi :\n";
$jtk = $db->select("datatraining", ['luas_gedung', 'kemungkinan_disewa']);
echo "[*]Kelas kecil :\n";
$gedungkecilrendah = 0;
$gedungstandarrendah = 0;
$gedungbesarrendah = 0;
foreach ($jtk as $j) {
    if ($j['luas_gedung'] == 'kecil' and $j['kemungkinan_disewa'] == 'rendah') {
        $gedungkecilrendah++;
    } elseif ($j['luas_gedung'] == 'standar' and $j['kemungkinan_disewa'] == 'rendah') {
        $gedungstandarrendah++;
    } elseif ($j['luas_gedung'] == 'besar' and $j['kemungkinan_disewa'] == 'rendah') {
        $gedungbesarrendah++;
    }
}
$tambahanLaplace = 0;
d:
echo "Jumlah Luas Gedung kecil & kemungkinan disewa rendah = " . $gedungkecilrendah . "\n";
echo "Jumlah Luas Gedung sedang & kemungkinan disewa rendah = " . $gedungstandarrendah . "\n";
echo "Jumlah Luas Gedung besar  & kemungkinan disewa rendah = " . $gedungbesarrendah . "\n";
$jumlahsetelahtambahanlaplace = $kelasrendah + $tambahanLaplace;
$probabilitasgedungkecilrendah = $gedungkecilrendah / $jumlahsetelahtambahanlaplace;
$probabilitasgedungstandarrendah = $gedungstandarrendah / $jumlahsetelahtambahanlaplace;
$probabilitasgedungbesarrendah = $gedungbesarrendah / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($gedungkecilrendah == 0 or $gedungstandarrendah == 0 or $gedungbesarrendah == 0) {
    $gedungkecilrendah++;
    $gedungstandarrendah++;
    $gedungbesarrendah++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto d;
}
echo "Probabilitas Luas Gedung kecil & kemungkinan disewa rendah = " . $gedungkecilrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasgedungkecilrendah . "\n";
echo "Probabilitas Luas Gedung standar & kemungkinan disewa rendah = " . $gedungstandarrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasgedungstandarrendah . "\n";
echo "Probabilitas Luas Gedung besar & kemungkinan disewa rendah = " . $gedungbesarrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasgedungbesarrendah . "\n";
echo "[*]Kelas sedang : \n";
$gedungkecilsedang = 0;
$gedungstandarsedang = 0;
$gedungbesarsedang = 0;
foreach ($jtk as $j) {
    if ($j['luas_gedung'] == 'kecil' and $j['kemungkinan_disewa'] == 'sedang') {
        $gedungkecilsedang++;
    } elseif ($j['luas_gedung'] == 'standar' and $j['kemungkinan_disewa'] == 'sedang') {
        $gedungstandarsedang++;
    } elseif ($j['luas_gedung'] == 'besar' and $j['kemungkinan_disewa'] == 'sedang') {
        $gedungbesarsedang++;
    }
}
$tambahanLaplace = 0;
e:
echo "Jumlah Luas Gedung kecil  & kemungkinan disewa sedang = " . $gedungkecilsedang . "\n";
echo "Jumlah Luas Gedung standar & kemungkinan disewa sedang = " . $gedungstandarsedang . "\n";
echo "Jumlah Luas Gedung besar & kemungkinan disewa sedang = " . $gedungbesarsedang . "\n";
$jumlahsetelahtambahanlaplace = $kelassedang + $tambahanLaplace;
$probabilitasgedungkecilsedang = $gedungkecilsedang / $jumlahsetelahtambahanlaplace;
$probabilitasgedungstandarsedang = $gedungstandarsedang / $jumlahsetelahtambahanlaplace;
$probabilitasgedungbesarsedang = $gedungbesarsedang / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($gedungkecilsedang == 0 or $gedungstandarsedang == 0 or $gedungbesarsedang == 0) {
    $gedungkecilsedang++;
    $gedungstandarsedang++;
    $gedungbesarsedang++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto e;
}
echo "Probabilitas Luas Gedung kecil & kemungkinan disewa sedang = " . $gedungkecilsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasgedungkecilsedang . "\n";
echo "Probabilitas Luas Gedung standar & kemungkinan disewa sedang = " . $gedungstandarsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasgedungstandarsedang . "\n";
echo "Probabilitas Luas Gedung besar& kemungkinan disewa sedang = " . $gedungbesarsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasgedungbesarsedang . "\n";
echo "[*]Kelas tinggi  : \n";
$gedungkeciltinggi = 0;
$gedungstandartinggi = 0;
$gedungbesartinggi = 0;
foreach ($jtk as $j) {
    if ($j['luas_gedung'] == 'kecil' and $j['kemungkinan_disewa'] == 'tinggi') {
        $gedungkeciltinggi++;
    } elseif ($j['luas_gedung'] == 'standar' and $j['kemungkinan_disewa'] == 'tinggi') {
        $gedungstandartinggi++;
    } elseif ($j['luas_gedung'] == 'besar' and $j['kemungkinan_disewa'] == 'tinggi') {
        $gedungbesartinggi++;
    }
}
$tambahanLaplace = 0;
f:
echo "Jumlah Luas Gedung kecil & kemungkinan disewa tinggi = " . $gedungkeciltinggi . "\n";
echo "Jumlah Luas Gedung standar & kemungkinan disewa tinggi = " . $gedungstandartinggi . "\n";
echo "Jumlah Luas Gedung besar & kemungkinan disewa tinggi = " . $gedungbesartinggi . "\n";
$jumlahsetelahtambahanlaplace = $kelastinggi + $tambahanLaplace;
$probabilitasgedungkeciltinggi = $gedungkeciltinggi / $jumlahsetelahtambahanlaplace;
$probabilitasgedungstandartinggi = $gedungstandartinggi / $jumlahsetelahtambahanlaplace;
$probabilitasgedungbesartinggi = $gedungbesartinggi / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($gedungkeciltinggi == 0 or $gedungstandartinggi == 0 or $gedungbesartinggi == 0) {
    $gedungkeciltinggi++;
    $gedungstandartinggi++;
    $gedungbesartinggi++;
    $tambahanLaplace = $tambahanLaplace + 3;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto f;
}
echo "Probabilitas Luas Gedung kecil & kemungkinan disewa tinggi  = " . $gedungkeciltinggi . "/" . $kelastinggi . " = " . $probabilitasgedungkeciltinggi . "\n";
echo "Probabilitas Luas Gedung standar & kemungkinan disewa tinggi  = " . $gedungstandartinggi . "/" . $kelastinggi . " = " . $probabilitasgedungstandartinggi . "\n";
echo "Probabilitas Luas Gedung besar & kemungkinan disewa tinggi = " . $gedungbesartinggi . "/" . $kelastinggi . " = " . $probabilitasgedungbesartinggi . "\n";
echo "============================\n";
///////////////////////////n
echo "[3] Luas Parkir :\n";
echo "Jumlah Total Nilai Frekuensi :\n";
$jtk = $db->select("datatraining", ['luas_parkir', 'kemungkinan_disewa']);
echo "[*]Kelas kecil :\n";
$parkirkecilrendah = 0;
$parkirsedangrendah = 0;
$parkirbesarrendah = 0;
foreach ($jtk as $j) {
    if ($j['luas_parkir'] == 'kecil' and $j['kemungkinan_disewa'] == 'rendah') {
        $parkirkecilrendah++;
    } elseif ($j['luas_parkir'] == 'sedang' and $j['kemungkinan_disewa'] == 'rendah') {
        $parkirsedangrendah++;
    } elseif ($j['luas_parkir'] == 'besar' and $j['kemungkinan_disewa'] == 'rendah') {
        $parkirbesarrendah++;
    }
}
$tambahanLaplace = 0;
g:
echo "Jumlah Luas Parkir kecil & kemungkinan disewa rendah = " . $parkirkecilrendah . "\n";
echo "Jumlah Luas Parkir sedang & kemungkinan disewa rendah = " . $parkirsedangrendah . "\n";
echo "Jumlah Luas Parkir besar  & kemungkinan disewa rendah = " . $parkirbesarrendah . "\n";
$jumlahsetelahtambahanlaplace = $kelasrendah + $tambahanLaplace;
$probabilitasparkirkecilrendah = $parkirkecilrendah / $jumlahsetelahtambahanlaplace;
$probabilitasparkirsedangrendah = $parkirsedangrendah / $jumlahsetelahtambahanlaplace;
$probabilitasparkirbesarrendah = $parkirbesarrendah / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($parkirkecilrendah == 0 or $parkirsedangrendah == 0 or $parkirbesarrendah == 0) {
    $parkirkecilrendah++;
    $parkirsedangrendah++;
    $parkirbesarrendah++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto g;
}
echo "Probabilitas Luas Parkir kecil & kemungkinan disewa rendah = " . $parkirkecilrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasparkirkecilrendah . "\n";
echo "Probabilitas Luas Parkir sedang & kemungkinan disewa rendah = " . $parkirsedangrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasparkirsedangrendah . "\n";
echo "Probabilitas Luas Parkir besar & kemungkinan disewa rendah = " . $parkirbesarrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasparkirbesarrendah . "\n";
echo "[*]Kelas sedang : \n";
$parkirkecilsedang = 0;
$parkirsedangsedang = 0;
$parkirbesarsedang = 0;
foreach ($jtk as $j) {
    if ($j['luas_parkir'] == 'kecil' and $j['kemungkinan_disewa'] == 'sedang') {
        $parkirkecilsedang++;
    } elseif ($j['luas_parkir'] == 'sedang' and $j['kemungkinan_disewa'] == 'sedang') {
        $parkirsedangsedang++;
    } elseif ($j['luas_parkir'] == 'besar' and $j['kemungkinan_disewa'] == 'sedang') {
        $parkirbesarsedang++;
    }
}
$tambahanLaplace = 0;
h:
echo "Jumlah Luas Parkir kecil  & kemungkinan disewa sedang = " . $parkirkecilsedang . "\n";
echo "Jumlah Luas Parkir sedang & kemungkinan disewa sedang = " . $parkirsedangsedang . "\n";
echo "Jumlah Luas Parkir besar & kemungkinan disewa sedang = " . $parkirbesarsedang . "\n";
$jumlahsetelahtambahanlaplace = $kelassedang + $tambahanLaplace;
$probabilitasparkirkecilsedang = $parkirkecilsedang / $jumlahsetelahtambahanlaplace;
$probabilitasparkirsedangsedang = $parkirsedangsedang / $jumlahsetelahtambahanlaplace;
$probabilitasparkirbesarsedang = $parkirbesarsedang / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($parkirkecilsedang == 0 or $parkirsedangsedang == 0 or $parkirbesarsedang == 0) {
    $parkirkecilsedang++;
    $parkirsedangsedang++;
    $parkirbesarsedang++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto h;
}
echo "Probabilitas Luas Parkir kecil & kemungkinan disewa sedang = " . $parkirkecilsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasparkirkecilsedang . "\n";
echo "Probabilitas Luas Parkir sedang & kemungkinan disewa sedang = " . $parkirsedangsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasparkirsedangsedang . "\n";
echo "Probabilitas Luas Parkir besar& kemungkinan disewa sedang = " . $parkirbesarsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasparkirbesarsedang . "\n";
echo "[*]Kelas tinggi  : \n";
$parkirkeciltinggi = 0;
$parkirsedangtinggi = 0;
$parkirbesartinggi = 0;
foreach ($jtk as $j) {
    if ($j['luas_parkir'] == 'kecil' and $j['kemungkinan_disewa'] == 'tinggi') {
        $parkirkeciltinggi++;
    } elseif ($j['luas_parkir'] == 'sedang' and $j['kemungkinan_disewa'] == 'tinggi') {
        $parkirsedangtinggi++;
    } elseif ($j['luas_parkir'] == 'besar' and $j['kemungkinan_disewa'] == 'tinggi') {
        $parkirbesartinggi++;
    }
}
$tambahanLaplace = 0;
i:
echo "Jumlah Luas Parkir kecil & kemungkinan disewa tinggi = " . $parkirkeciltinggi . "\n";
echo "Jumlah Luas Parkir sedang & kemungkinan disewa tinggi = " . $parkirsedangtinggi . "\n";
echo "Jumlah Luas Parkir besar & kemungkinan disewa tinggi = " . $parkirbesartinggi . "\n";
$jumlahsetelahtambahanlaplace = $kelastinggi + $tambahanLaplace;
$probabilitasparkirkeciltinggi = $parkirkeciltinggi / $jumlahsetelahtambahanlaplace;
$probabilitasparkirsedangtinggi = $parkirsedangtinggi / $jumlahsetelahtambahanlaplace;
$probabilitasparkirbesartinggi = $parkirbesartinggi / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($parkirkeciltinggi == 0 or $parkirsedangtinggi == 0 or $parkirbesartinggi == 0) {
    $parkirkeciltinggi++;
    $parkirsedangtinggi++;
    $parkirbesartinggi++;
    $tambahanLaplace = $tambahanLaplace + 3;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto i;
}
echo "Probabilitas Luas Parkir kecil & kemungkinan disewa tinggi  = " . $jtksedikittinggi . "/" . $kelastinggi . " = " . $probabilitasparkirkeciltinggi . "\n";
echo "Probabilitas Luas Parkir sedang & kemungkinan disewa tinggi = " . $jtksedangtinggi . "/" . $kelastinggi . " = " . $probabilitasparkirsedangtinggi . "\n";
echo "Probabilitas Luas Parkir besar & kemungkinan disewa tinggi = " . $jtkbanyaktinggi . "/" . $kelastinggi . " = " . $probabilitasparkirbesartinggi . "\n";
echo "============================\n";
echo "[4] daya listrik :\n";
echo "Jumlah Total Nilai Frekuensi :\n";
$jtk = $db->select("datatraining", ['daya_listrik', 'kemungkinan_disewa']);
echo "[*]Kelas rendah :\n";
$dayalistrikrendahrendah = 0;
$dayalistriksedangrendah = 0;
$dayalistriktinggirendah = 0;
foreach ($jtk as $j) {
    if ($j['daya_listrik'] == 'rendah' and $j['kemungkinan_disewa'] == 'rendah') {
        $dayalistrikrendahrendah++;
    } elseif ($j['daya_listrik'] == 'sedang' and $j['kemungkinan_disewa'] == 'rendah') {
        $dayalistriksedangrendah++;
    } elseif ($j['daya_listrik'] == 'tinggi' and $j['kemungkinan_disewa'] == 'rendah') {
        $dayalistriktinggirendah++;
    }
}
$tambahanLaplace = 0;
j:
echo "Jumlah daya listrik rendah & kemungkinan disewa rendah = " . $dayalistrikrendahrendah . "\n";
echo "Jumlah daya listrik sedang & kemungkinan disewa rendah = " . $dayalistriksedangrendah . "\n";
echo "Jumlah daya listrik tinggi  & kemungkinan disewa rendah = " . $dayalistriktinggirendah . "\n";
$jumlahsetelahtambahanlaplace = $kelasrendah + $tambahanLaplace;
$probabilitasdayalistrikrendahrendah = $dayalistrikrendahrendah / $jumlahsetelahtambahanlaplace;
$probabilitasdayalistriksedangrendah = $dayalistriksedangrendah / $jumlahsetelahtambahanlaplace;
$probabilitasdayalistriktinggirendah = $dayalistriktinggirendah / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($dayalistrikrendahrendah == 0 or $dayalistriksedangrendah == 0 or $dayalistriktinggirendah == 0) {
    $dayalistrikrendahrendah++;
    $dayalistriksedangrendah++;
    $dayalistriktinggirendah++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto j;
}
echo "Probabilitas daya listrik rendah & kemungkinan disewa rendah = " . $dayalistrikrendahrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasdayalistrikrendahrendah . "\n";
echo "Probabilitas daya listrik sedang & kemungkinan disewa rendah = " . $dayalistriksedangrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasdayalistriksedangrendah . "\n";
echo "Probabilitas daya listrik tinggi & kemungkinan disewa rendah = " . $dayalistriktinggirendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasdayalistriktinggirendah . "\n";
echo "[*]Kelas sedang : \n";
$dayalistrikrendahsedang = 0;
$dayalistriksedangsedang = 0;
$dayalistriktinggisedang = 0;
foreach ($jtk as $j) {
    if ($j['daya_listrik'] == 'rendah' and $j['kemungkinan_disewa'] == 'sedang') {
        $dayalistrikrendahsedang++;
    } elseif ($j['daya_listrik'] == 'sedang' and $j['kemungkinan_disewa'] == 'sedang') {
        $dayalistriksedangsedang++;
    } elseif ($j['daya_listrik'] == 'tinggi' and $j['kemungkinan_disewa'] == 'sedang') {
        $dayalistriktinggisedang++;
    }
}
$tambahanLaplace = 0;
k:
echo "Jumlah daya listrik rendah  & kemungkinan disewa sedang = " . $dayalistrikrendahsedang . "\n";
echo "Jumlah daya listrik sedang & kemungkinan disewa sedang = " . $dayalistriksedangsedang . "\n";
echo "Jumlah daya listrik tinggi & kemungkinan disewa sedang = " . $dayalistriktinggisedang . "\n";
$jumlahsetelahtambahanlaplace = $kelassedang + $tambahanLaplace;
$probabilitasdayalistrikrendahsedang = $dayalistrikrendahsedang / $jumlahsetelahtambahanlaplace;
$probabilitasdayalistriksedangsedang = $dayalistriksedangsedang / $jumlahsetelahtambahanlaplace;
$probabilitasdayalistriktinggisedang = $dayalistriktinggisedang / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($dayalistrikrendahsedang == 0 or $dayalistriksedangsedang == 0 or $dayalistriktinggisedang == 0) {
    $dayalistrikrendahsedang++;
    $dayalistriksedangsedang++;
    $dayalistriktinggisedang++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto k;
}
echo "Probabilitas daya listrik rendah & kemungkinan disewa sedang = " . $dayalistrikrendahsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasdayalistrikrendahsedang . "\n";
echo "Probabilitas daya listrik sedang & kemungkinan disewa sedang = " . $dayalistriksedangsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasdayalistriksedangsedang . "\n";
echo "Probabilitas daya listrik tinggi& kemungkinan disewa sedang = " . $dayalistriktinggisedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasdayalistriktinggisedang . "\n";
echo "[*]Kelas tinggi  : \n";
$dayalistrikrendahtinggi = 0;
$dayalistriksedangtinggi = 0;
$dayalistriktinggitinggi = 0;
foreach ($jtk as $j) {
    if ($j['daya_listrik'] == 'rendah' and $j['kemungkinan_disewa'] == 'tinggi') {
        $dayalistrikrendahtinggi++;
    } elseif ($j['daya_listrik'] == 'sedang' and $j['kemungkinan_disewa'] == 'tinggi') {
        $dayalistriksedangtinggi++;
    } elseif ($j['daya_listrik'] == 'tinggi' and $j['kemungkinan_disewa'] == 'tinggi') {
        $dayalistriktinggitinggi++;
    }
}
$tambahanLaplace = 0;
l:
echo "Jumlah daya listrik rendah & kemungkinan disewa tinggi = " . $dayalistrikrendahtinggi . "\n";
echo "Jumlah daya listrik sedang & kemungkinan disewa tinggi = " . $dayalistriksedangtinggi . "\n";
echo "Jumlah daya listrik tinggi & kemungkinan disewa tinggi = " . $dayalistriktinggitinggi . "\n";
$jumlahsetelahtambahanlaplace = $kelastinggi + $tambahanLaplace;
$probabilitasdayalistrikrendahtinggi = $dayalistrikrendahtinggi / $jumlahsetelahtambahanlaplace;
$probabilitasdayalistriksedangtinggi = $dayalistriksedangtinggi / $jumlahsetelahtambahanlaplace;
$probabilitasdayalistriktinggitinggi = $dayalistriktinggitinggi / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($dayalistrikrendahtinggi == 0 or $dayalistriksedangtinggi == 0 or $dayalistriktinggitinggi == 0) {
    $dayalistrikrendahtinggi++;
    $dayalistriksedangtinggi++;
    $dayalistriktinggitinggi++;
    $tambahanLaplace = $tambahanLaplace + 3;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto l;
}
echo "Probabilitas daya listrik rendah & kemungkinan disewa tinggi  = " . $dayalistrikrendahtinggi . "/" . $kelastinggi . " = " . $probabilitasdayalistrikrendahtinggi . "\n";
echo "Probabilitas daya listrik sedang & kemungkinan disewa tinggi  = " . $dayalistriksedangtinggi . "/" . $kelastinggi . " = " . $probabilitasdayalistriksedangtinggi . "\n";
echo "Probabilitas daya listrik tinggi & kemungkinan disewa tinggi = " . $dayalistriktinggitinggi . "/" . $kelastinggi . " = " . $probabilitasdayalistriktinggitinggi . "\n";
echo "============================\n";
///////////////////////////n
echo "[5] Perlengkapan audio yang dimiliki :\n";
echo "Jumlah Total Nilai Frekuensi :\n";
$jtk = $db->select("datatraining", ['perlengkapan_yang_dimiliki', 'kemungkinan_disewa']);
echo "[*]Kelas rendah :\n";
$perlengkapanrendahrendah = 0;
$perlengkapansedangrendah = 0;
$perlengkapantinggirendah = 0;
foreach ($jtk as $j) {
    if ($j['perlengkapan_yang_dimiliki'] == 'rendah' and $j['kemungkinan_disewa'] == 'rendah') {
        $perlengkapanrendahrendah++;
    } elseif ($j['perlengkapan_yang_dimiliki'] == 'sedang' and $j['kemungkinan_disewa'] == 'rendah') {
        $perlengkapansedangrendah++;
    } elseif ($j['perlengkapan_yang_dimiliki'] == 'tinggi' and $j['kemungkinan_disewa'] == 'rendah') {
        $perlengkapantinggirendah++;
    }
}
$tambahanLaplace = 0;
m:
echo "Jumlah Perlengkapan yang dimiliki rendah & kemungkinan disewa rendah = " . $perlengkapanrendahrendah . "\n";
echo "Jumlah Perlengkapan yang dimiliki sedang & kemungkinan disewa rendah = " . $perlengkapansedangrendah . "\n";
echo "Jumlah Perlengkapan yang dimiliki tinggi  & kemungkinan disewa rendah = " . $perlengkapantinggirendah . "\n";
$jumlahsetelahtambahanlaplace = $kelasrendah + $tambahanLaplace;
$probabilitasperlengkapanrendahrendah = $perlengkapanrendahrendah / $jumlahsetelahtambahanlaplace;
$probabilitasperlengkapansedangrendah = $perlengkapansedangrendah / $jumlahsetelahtambahanlaplace;
$probabilitasperlengkapantinggirendah = $perlengkapantinggirendah / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($perlengkapanrendahrendah == 0 or $perlengkapansedangrendah == 0 or $perlengkapantinggirendah == 0) {
    $perlengkapanrendahrendah++;
    $perlengkapansedangrendah++;
    $perlengkapantinggirendah++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto m;
}
echo "Probabilitas Perlengkapan yang dimiliki rendah & kemungkinan disewa rendah = " . $perlengkapanrendahrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasperlengkapanrendahrendah . "\n";
echo "Probabilitas Perlengkapan yang dimiliki sedang & kemungkinan disewa rendah = " . $perlengkapansedangrendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasperlengkapansedangrendah . "\n";
echo "Probabilitas Perlengkapan yang dimiliki tinggi & kemungkinan disewa rendah = " . $perlengkapantinggirendah . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasperlengkapantinggirendah . "\n";
echo "[*]Kelas sedang : \n";
$perlengkapanrendahsedang = 0;
$perlengkapansedangsedang = 0;
$perlengkapantinggisedang = 0;
foreach ($jtk as $j) {
    if ($j['perlengkapan_yang_dimiliki'] == 'rendah' and $j['kemungkinan_disewa'] == 'sedang') {
        $perlengkapanrendahsedang++;
    } elseif ($j['perlengkapan_yang_dimiliki'] == 'sedang' and $j['kemungkinan_disewa'] == 'sedang') {
        $perlengkapansedangsedang++;
    } elseif ($j['perlengkapan_yang_dimiliki'] == 'tinggi' and $j['kemungkinan_disewa'] == 'sedang') {
        $perlengkapantinggisedang++;
    }
}
$tambahanLaplace = 0;
n:
echo "Jumlah Perlengkapan yang dimiliki rendah  & kemungkinan disewa sedang = " . $perlengkapanrendahsedang . "\n";
echo "Jumlah Perlengkapan yang dimiliki sedang & kemungkinan disewa sedang = " . $perlengkapansedangsedang . "\n";
echo "Jumlah Perlengkapan yang dimiliki tinggi & kemungkinan disewa sedang = " . $perlengkapantinggisedang . "\n";
$jumlahsetelahtambahanlaplace = $kelassedang + $tambahanLaplace;
$probabilitasperlengkapanrendahsedang = $perlengkapanrendahsedang / $jumlahsetelahtambahanlaplace;
$probabilitasperlengkapansedangsedang = $perlengkapansedangsedang / $jumlahsetelahtambahanlaplace;
$probabilitasperlengkapantinggisedang = $perlengkapantinggisedang / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($perlengkapanrendahsedang == 0 or $perlengkapansedangsedang == 0 or $perlengkapantinggisedang == 0) {
    $perlengkapanrendahsedang++;
    $perlengkapansedangsedang++;
    $perlengkapantinggisedang++;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    $tambahanLaplace = $tambahanLaplace + 3;
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto n;
}
echo "Probabilitas Perlengkapan yang dimiliki rendah & kemungkinan disewa sedang = " . $perlengkapanrendahsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasperlengkapanrendahsedang . "\n";
echo "Probabilitas Perlengkapan yang dimiliki sedang & kemungkinan disewa sedang = " . $perlengkapansedangsedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasperlengkapansedangsedang . "\n";
echo "Probabilitas Perlengkapan yang dimiliki tinggi& kemungkinan disewa sedang = " . $perlengkapantinggisedang . "/" . $jumlahsetelahtambahanlaplace . " = " . $probabilitasperlengkapantinggisedang . "\n";
echo "[*]Kelas tinggi  : \n";
$perlengkapanrendahtinggi = 0;
$perlengkapansedangtinggi = 0;
$perlengkapantinggitinggi = 0;
foreach ($jtk as $j) {
    if ($j['perlengkapan_yang_dimiliki'] == 'rendah' and $j['kemungkinan_disewa'] == 'tinggi') {
        $perlengkapanrendahtinggi++;
    } elseif ($j['perlengkapan_yang_dimiliki'] == 'sedang' and $j['kemungkinan_disewa'] == 'tinggi') {
        $perlengkapansedangtinggi++;
    } elseif ($j['perlengkapan_yang_dimiliki'] == 'tinggi' and $j['kemungkinan_disewa'] == 'tinggi') {
        $perlengkapantinggitinggi++;
    }
}
$tambahanLaplace = 0;
o:
echo "Jumlah Perlengkapan yang dimiliki rendah & kemungkinan disewa tinggi = " . $perlengkapanrendahtinggi . "\n";
echo "Jumlah Perlengkapan yang dimiliki sedang & kemungkinan disewa tinggi = " . $perlengkapansedangtinggi . "\n";
echo "Jumlah Perlengkapan yang dimiliki tinggi & kemungkinan disewa tinggi = " . $perlengkapantinggitinggi . "\n";
$jumlahsetelahtambahanlaplace = $kelastinggi + $tambahanLaplace;
$probabilitasperlengkapanrendahtinggi = $perlengkapanrendahtinggi / $jumlahsetelahtambahanlaplace;
$probabilitasperlengkapansedangtinggi = $perlengkapansedangtinggi / $jumlahsetelahtambahanlaplace;
$probabilitasperlengkapantinggitinggi = $perlengkapantinggitinggi / $jumlahsetelahtambahanlaplace;
//laplace correction
if ($perlengkapanrendahtinggi == 0 or $perlengkapansedangtinggi == 0 or $perlengkapantinggitinggi == 0) {
    $perlengkapanrendahtinggi++;
    $perlengkapansedangtinggi++;
    $perlengkapantinggitinggi++;
    $tambahanLaplace = $tambahanLaplace + 3;
    //diasumsikan ada 1 lagi dataset untuk masing masing nilai
    echo "Data mengalami laplace correction. Data baru setelah laplace correction :\n";
    goto o;
}
echo "Probabilitas Perlengkapan yang dimiliki rendah & kemungkinan disewa tinggi  = " . $perlengkapanrendahtinggi . "/" . $kelastinggi . " = " . $probabilitasperlengkapanrendahtinggi . "\n";
echo "Probabilitas Perlengkapan yang dimiliki sedang & kemungkinan disewa tinggi  = " . $perlengkapansedangtinggi . "/" . $kelastinggi . " = " . $probabilitasperlengkapansedangtinggi . "\n";
echo "Probabilitas Perlengkapan yang dimiliki tinggi & kemungkinan disewa tinggi = " . $perlengkapantinggitinggi . "/" . $kelastinggi . " = " . $probabilitasperlengkapantinggitinggi . "\n";
echo "=============================================================================================================================================================================\n";
///////////////////////////
echo "\n\n\n";
echo "============================\n";
echo "          PENGUJIAN         \n ";
echo "============================\n";
echo "Masukkan data yang ingin anda uji : \n";
echo "Jumlah Pegawai (sedikit,sedang,banyak) : ";
$input_pegawai = fopen("php://stdin", "r");
$pegawai = trim(fgets($input_pegawai));
echo "\nLuas Gedung (kecil,standar,besar) : ";
$input_gedung = fopen("php://stdin", "r");
$gedung = trim(fgets($input_gedung));
echo "\nLuas Parkir (kecil,sedang,besar) : ";
$input_parkir = fopen("php://stdin", "r");
$parkir = trim(fgets($input_parkir));
echo "\nDaya Listrik (rendah,sedang,tinggi) : ";
$input_daya = fopen("php://stdin", "r");
$daya = trim(fgets($input_daya));
echo "\nPerlengkapan yang dimiliki (rendah,sedang,tinggi) : ";
$input_perlengkapan = fopen("php://stdin", "r");
$perlengkapan = trim(fgets($input_perlengkapan));
echo "\n";
//memilih probabilitas
$rendah = [];
$sedang = [];
$tinggi = [];
//pegawai
if ($pegawai == 'sedikit') {
    array_push($rendah, $probabilitasjtksedikitrendah);
    array_push($sedang, $probabilitasjtksedikitsedang);
    array_push($tinggi, $probabilitasjtksedikittinggi);
} elseif ($pegawai == 'sedang') {
    array_push($rendah, $probabilitasjtksedangrendah);
    array_push($sedang, $probabilitasjtksedangsedang);
    array_push($tinggi, $probabilitasjtksedangtinggi);
} elseif ($pegawai == 'banyak') {
    array_push($rendah, $probabilitasjtkbanyakrendah);
    array_push($sedang, $probabilitasjtkbanyaksedang);
    array_push($tinggi, $probabilitasjtkbanyaktinggi);
}
//Luas Gedung
if ($gedung == 'kecil') {
    array_push($rendah, $probabilitasgedungkecilrendah);
    array_push($sedang, $probabilitasgedungkecilsedang);
    array_push($tinggi, $probabilitasgedungkeciltinggi);
} elseif ($gedung == 'standar') {
    array_push($rendah, $probabilitasgedungstandarrendah);
    array_push($sedang, $probabilitasgedungstandarsedang);
    array_push($tinggi, $probabilitasgedungstandartinggi);
} elseif ($gedung == 'besar') {
    array_push($rendah, $probabilitasgedungbesarrendah);
    array_push($sedang, $probabilitasgedungbesarsedang);
    array_push($tinggi, $probabilitasgedungbesartinggi);
}
//parkir
if ($parkir == 'kecil') {
    array_push($rendah, $probabilitasparkirkecilrendah);
    array_push($sedang, $probabilitasparkirkecilsedang);
    array_push($tinggi, $probabilitasparkirkeciltinggi);
} elseif ($parkir == 'sedang') {
    array_push($rendah, $probabilitasparkirsedangrendah);
    array_push($sedang, $probabilitasparkirsedangsedang);
    array_push($tinggi, $probabilitasparkirsedangtinggi);
} elseif ($parkir == 'besar') {
    array_push($rendah, $probabilitasparkirbesarrendah);
    array_push($sedang, $probabilitasparkirbesarsedang);
    array_push($tinggi, $probabilitasparkirbesartinggi);
}
//daya
if ($daya == 'rendah') {
    array_push($rendah, $probabilitasdayalistrikrendahrendah);
    array_push($sedang, $probabilitasdayalistrikrendahsedang);
    array_push($tinggi, $probabilitasdayalistrikrendahtinggi);
} elseif ($daya == 'sedang') {
    array_push($rendah, $probabilitasdayalistriksedangrendah);
    array_push($sedang, $probabilitasdayalistriksedangsedang);
    array_push($tinggi, $probabilitasdayalistriksedangtinggi);
} elseif ($daya == 'tinggi') {
    array_push($rendah, $probabilitasdayalistriktinggirendah);
    array_push($sedang, $probabilitasdayalistriktinggisedang);
    array_push($tinggi, $probabilitasdayalistriktinggitinggi);
}
//perlengkapan
if ($perlengkapan == 'rendah') {
    array_push($rendah, $probabilitasperlengkapanrendahrendah);
    array_push($sedang, $probabilitasperlengkapanrendahsedang);
    array_push($tinggi, $probabilitasperlengkapanrendahtinggi);
} elseif ($perlengkapan == 'sedang') {
    array_push($rendah, $probabilitasperlengkapansedangrendah);
    array_push($sedang, $probabilitasperlengkapansedangsedang);
    array_push($tinggi, $probabilitasperlengkapansedangtinggi);
} elseif ($perlengkapan == 'tinggi') {
    array_push($rendah, $probabilitasperlengkapantinggirendah);
    array_push($sedang, $probabilitasperlengkapantinggisedang);
    array_push($tinggi, $probabilitasperlengkapantinggitinggi);
}
$kalimat_rendah = "";
$kalimat_sedang = "";
$kalimat_tinggi = "";
$jumlah_rendah = 0;
$jumlah_sedang = 0;
$jumlah_tinggi = 0;
for ($a = 0; $a < 5; $a++) {
    if ($a == 0) {
        $kalimat_rendah = $kalimat_rendah . $rendah[$a];
        $kalimat_sedang = $kalimat_sedang . $sedang[$a];
        $kalimat_tinggi = $kalimat_tinggi . $tinggi[$a];
        $jumlah_rendah = $rendah[$a];
        $jumlah_sedang = $sedang[$a];
        $jumlah_tinggi = $tinggi[$a];
    } else {
        $kalimat_rendah = $kalimat_rendah . "*" . $rendah[$a];
        $kalimat_sedang = $kalimat_sedang . "*" . $sedang[$a];
        $kalimat_tinggi = $kalimat_tinggi . "*" . $tinggi[$a];
        $jumlah_rendah = $jumlah_rendah * $rendah[$a];
        $jumlah_sedang = $jumlah_sedang * $sedang[$a];
        $jumlah_tinggi = $jumlah_tinggi * $tinggi[$a];
    }
}
$jumlah_rendah = $jumlah_rendah * $probabilitasKelasRendah;
$jumlah_sedang = $jumlah_sedang * $probabilitasKelasSedang;
$jumlah_tinggi = $jumlah_tinggi * $probabilitasKelasTinggi;
echo "Perhitungan :\n";
echo "Rendah = (" . $kalimat_rendah . ")*" . $probabilitasKelasRendah . " = " . $jumlah_rendah . "\n";
echo "Sedang = (" . $kalimat_sedang . ")*" . $probabilitasKelasSedang . " = " . $jumlah_sedang . "\n";
echo "Tinggi = (" . $kalimat_tinggi . ")*" . $probabilitasKelasTinggi . " = " . $jumlah_tinggi . "\n";

if ($jumlah_rendah > $jumlah_sedang and $jumlah_rendah > $jumlah_tinggi) {
    echo "Dari perhitungan tersebut bahwa " . $jumlah_rendah . " yang memiliki probabilitas paling tinggi, sehingga prediksinya adalah RENDAH";
} elseif ($jumlah_sedang > $jumlah_rendah and $jumlah_sedang > $jumlah_tinggi) {
    echo "Dari perhitungan tersebut bahwa " . $jumlah_sedang . " yang memiliki probabilitas paling tinggi, sehingga prediksinya adalah SEDANG";
} elseif ($jumlah_tinggi > $jumlah_rendah and $jumlah_tinggi > $jumlah_sedang) {
    echo "Dari perhitungan tersebut bahwa " . $jumlah_tinggi . " probabilitas paling tinggi, sehingga prediksinya adalah TINGGI";
}

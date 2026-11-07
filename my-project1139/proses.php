<?php
function hitungNilaiRataRata($nilai) {
    $total = array_sum($nilai);
    $jumlah = count($nilai);
    return $jumlah > 0 ? $total / $jumlah : 0;
}

function tentukanNilaiGrade($rata) {
    if($rata >= 85) return "A";
    elseif ($rata >= 75) return "B";
    elseif ($rata >= 65) return "C";
    elseif ($rata >= 60) return "D";
    else return "E";
}

function tampikanHasilHitung($nama, $nilai, $email, $password, $gender, $hobi) {
    $rata2 = hitungNilaiRataRata($nilai);
    $grade = tentukanNilaiGrade($rata2);
    echo "
    <div class='alert alert-success mt-4'>
    <h5>Hasil Perhitungan</h5>
    <p><strong>Nama: </strong>$nama</p>
    <p><strong>Nilai: </strong>".implode(",", $nilai)."</p>
    <p><strong>Nilai Rata-Rata: </strong>". number_format($rata2, 2). "</p>
    <p><strong>Grade: </strong><span class='badge bg-primary'>$grade</span></p>
    <p><strong>Email: </strong>$email</p>
    <p><strong>Password: </strong>$password</p>
    <p><strong>Jenis Kelamin: </strong>$gender</p>
    <p><strong>Hobi: </strong>".implode(",", $hobi)."</p>
    </div>";
}

if(isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $nilai = $_POST['nilai'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $gender = $_POST['gender'];
    $hobi = $_POST['hobi'];
    tampikanHasilHitung($nama, $nilai, $email, $password, $gender, $hobi);
}
?>
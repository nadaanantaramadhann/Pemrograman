<?php
class Karyawan {
    private $nama;
    private $jabatan;

    // Constructor
    public function __construct($nama, $jabatan) {
        $this->nama = $nama;
        $this->jabatan = $jabatan;
    }

    // Method menampilkan data
    public function tampilkanData($departemen) {
        echo "<h3>Data Karyawan</h3>";
        echo "Nama Karyawan : " . $this->nama . "<br>";
        echo "Jabatan       : " . $this->jabatan . "<br>";
        echo "Departemen    : " . $departemen . "<br>";
    }
}

// Membuat objek
$pegawai = new Karyawan("Leonardo", "Supervisor");

// Memanggil method
$pegawai->tampilkanData("Operasional");
?>
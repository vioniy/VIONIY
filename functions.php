<?php
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "phpdasar"); 


function query($query) {
    global $conn;
    $result =mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows [] = $row;
    }
    return $rows;
   }


   function tambah($data) {
    global $conn;

  
    $nama = htmlspecialchars($data["nama"]);
    $nrp = htmlspecialchars($data["nrp"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);

    // Upload gambar
    $gambar = upload();
    if ( !$gambar ) {
       return false;
    }

      $query = "INSERT INTO mahasiswa 
      VALUES
    ('', '$nrp', '$nama', '$email', '$jurusan', '$gambar') " ;
 mysqli_query($conn, $query);

   return mysqli_affected_rows($conn);
}


function upload() {

 $namaFile =$_FILES['gambar']['nama'];
 $ukuranFile =$_FILES['gambar']['size'];
 $error =$_FILES['gambar']['error'];
 $tmpName =$_FILES['gambar']['tmp_name'];

// cek apakah tidak ada gambar yang diupload
if( $error === 4) {
  echo "<script>
            alert('pilih gambar terlebih dahulu!');
        </script>";
    return false;
}

// cek apakah yang diupload adalah gambar 
$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
$ekstensiGambar =explode('.', $namaFile);
$ekstensiGambar =strtolower(end($ekstensiGambar)) ;
if( !in_array($ekstensiGambar,$ekstensiGambarValid)) {
  echo "<script>;
  elert('YANG ANDA UPLOAD BUKAN GAMBAR!');
  </script>";
  return false;
}

// cek jika ukurannya terlalu besar
if( $ukuranFile > 1000000) {
  echo "<script>
  elert('ukuran gambar terlalu besar!');
  </script>";
  return false;
}

// lolos pengecekan, gambar siap di upload
// generate nama gambar baru
$namaFileBaru = uniqid();
$namaFileBaru .= '.';
$namaFileBaru .= $ekstensiGambar;


move_uploaded_file($tmpname, 'img/' . $namaFileBaru);

return $namaFileBaru;


}




   function hapus($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM mahasiswa where id = $id");

    return mysqli_affected_rows($conn);
   }

function ubah($data) {
  global $conn;
  $id = $data["id"];
  $nrp = htmlspecialchars($data["nrp"]);
  $nama =htmlspecialchars($data["nama"]);
  $email =htmlspecialchars($data["email"]);
  $jurusan =htmlspecialchars($data["jurusan"]);
  $gambarLama =htmlspecialchars($data["gambarLama"]);

  // cek apakah user pilih gambar baru atau tidak
  if($_files['gambar']['error'] === 4 ){
    $gambar = $gambarLama;
  } else{
    $gambar = upload();
  }
  
  $query = "UPDATE mahasiswa SET
                 nrp = '$nrp',
                 nama = '$nama',
                 email = '$email',
                 jurusan = '$jurusan',
                 gambar = '$gambar'
                WHERE id = $id
              ";
mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}




function cari($keyword) {
  $query ="SELECT * FROM mahasiswa
            WHERE
        nama LIKE '%$keyword%' OR
        nrp LIKE '%$keyword%' OR
        email LIKE '%$keyword%' OR
        jurusan LIKE '%$keyword%' 
            ";
    return query($query);
}



  ?>
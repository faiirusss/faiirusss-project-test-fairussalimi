<?php

$conn = mysqli_connect('localhost', 'root', '', 'suitmedia');
function query($query)
{
    global $conn;
    $rows = array();

    $result = mysqli_query($conn, $query);
    $row = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}


function add($data)
{
    global $conn;
    date_default_timezone_set('Asia/Jakarta');

    $title = $data['title'];
    $published_at = $data['published_at'];
    $image = image();

    mysqli_query(
        $conn,
        "INSERT INTO artikel 
        VALUES
            ('NULL', 
            '$title',
            '$published_at',
            '$image'
            )"
    );

    return mysqli_affected_rows($conn);
}

function image()
{
    $namaFile = $_FILES['image']['name']; // RAKAWIBOWO.png
    $ukuranFile = $_FILES['image']['size'];
    $error = $_FILES['image']['error'];
    $tmpName = $_FILES['image']['tmp_name'];

    if ($error === 4) {
        echo  "<script>
                    alert('Pilih gambar terlebih dahulu');
                </script>
            ";
        return false;
    }
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar)); // raka.png

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo  "<script>
                    alert('yang anda upload bukan gambar!');
               </script>
        ";
        return false;
    }
    if ($ukuranFile > 1000000) {
        echo  "<script>
                    alert('ukuran gambar terlalu besar');
               </script>
        ";
        return false;
    }
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;
    move_uploaded_file($tmpName, 'images/' . $namaFileBaru);
    return $namaFileBaru;
}

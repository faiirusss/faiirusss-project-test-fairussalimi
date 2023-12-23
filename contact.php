<?php

require 'connection.php';

if (isset($_POST["submit"])) {
    if (add($_POST) > 0) {
        echo "
            <script>
                alert('Data berhasil ditambahkan');
                document.location.href = 'index.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('data gagal ditambahkan');
                document.location.href = 'index.php';
            </script>
        ";
    }
}

$jumlahDataPerHalaman = 8;
$jumlahData = count(query("SELECT * FROM artikel"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

if (isset($_GET["halaman"])) {
    $halamanAktif = $_GET["halaman"];
} else {
    $halamanAktif = 1;
}

$awal_data = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
$artikel = query("SELECT * FROM artikel LIMIT $awal_data, $jumlahDataPerHalaman");

$jumlahLink =  3;
if ($halamanAktif > $jumlahLink) {
    $startNumber = $halamanAktif - $jumlahLink;
} else {
    $startNumber = 1;
}

if ($halamanAktif < ($jumlahHalaman - $jumlahLink)) {
    $endNumber  = $halamanAktif + $jumlahLink;
} else {
    $endNumber  = $jumlahHalaman;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suitmedia</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>

<body>

    <!-- navbar -->
    <header>
        <nav class="navbar" id="navbar">
            <div class="container">
                <a href="#"><img src="assets/img/logo.png" alt=""></a>
                <div class="nav-link">
                    <li class="nav-list">
                        <ul><a href="work.php" class="nav-item">Work</a></ul>
                        <ul><a href="about.php" class="nav-item">About</a></ul>
                        <ul><a href="services.php" class="nav-item">Services</a></ul>
                        <ul><a href="index.php" class="nav-item ">Ideas</a></ul>
                        <ul><a href="careers.php" class="nav-item">Careers</a></ul>
                        <ul><a href="contact.php" class="nav-item active">Contact</a></ul>
                    </li>
                </div>
            </div>
        </nav>
    </header>

    <!-- home -->
    <section>
        <img src="assets/img/profile.png" alt="">
    </section>

    <!-- main section -->
    <main>
        <section class="mb-5">
            <div class="container text-center">
                <div class="row gap-5 box-content gx-0 mb-5">
                    <div class="button-main">
                        <p>Showing 1 - 10 of <?php echo $jumlahData ?></p>
                        <button class="button-add" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">tambah data</button>
                        <p>Show per page:
                            <select id="showPerPage" onchange="updatePage()">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </p>
                        <p>Short by:
                            <select id="sortBy" onchange="updatePage()">
                                <option value="latest">Latest</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </p>
                    </div>

                    <?php foreach ($artikel as $value) : ?>
                        <div class="containers card mb-2 shadow bg-body-tertiary rounded" style="width: 18rem; border:none">
                            <img src="images/<?php echo $value['image'] ?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <p class="card-publish"><?php echo $value['published_at'] ?></p>
                                <p class="card-text"><?php echo $value['title'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- pagination -->
                <?php if ($halamanAktif > 1) : ?>
                    <a href="?halaman=<?php echo $halamanAktif - 1 ?>" style="text-decoration: none;">
                        &laquo;
                    </a>
                <?php endif; ?>

                <?php for ($i = $startNumber; $i <= $endNumber; $i++) : ?>
                    <?php if ($halamanAktif == $i) : ?>
                        <a href="?halaman=<?php echo $i; ?>" style="background-color: #FF6600; color: #fff; text-decoration: none; border-radius: 3px; padding: 5px; margin:5px;">
                            <?php echo $i; ?>
                        </a>
                    <?php else : ?>
                        <a href="?halaman=<?php echo $i; ?>" style="text-decoration: none; padding: 5px; margin:5px;">
                            <?php echo $i; ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($halamanAktif < $jumlahHalaman) : ?>
                    <a href="?halaman=<?php echo $halamanAktif + 1 ?>" style="text-decoration: none;">
                        &raquo;
                    </a>
                <?php endif; ?>
                <!-- end pagination -->
            </div>
        </section>
    </main>

    <!-- modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">New Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="published_at" class="col-form-label">Date:</label>
                            <input type="date" class="form-control" id="published_at" name="published_at"></input>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="col-form-label">Image:</label>
                            <input type="file" class="form-control" id="image" name="image"></input>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button style="background-color: #FF6600; color: #fff;" type="submit" class="btn" name="submit">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let lastScrollTop = 0;
        navbar = document.getElementById("navbar");
        window.addEventListener("scroll", function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop) {
                // Scroll ke bawah - sembunyikan navbar
                navbar.style.top = "-80px";
            } else {
                // Scroll ke atas - tampilkan navbar
                navbar.style.top = "0";

                // Jika sudah di bagian paling atas, kembalikan warna latar belakang
                if (scrollTop === 0) {
                    navbar.style.backgroundColor = "#FF6600";
                } else {
                    // Jika belum di bagian paling atas, rubah warna latar belakang
                    navbar.style.backgroundColor = "#F98F46";
                }
            }
            lastScrollTop = scrollTop;
        });
    </script>
</body>

</html>
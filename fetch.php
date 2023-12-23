<?php
require 'connection.php';

if (isset($_POST['request'])) {
    $request = $_POST['request'];

    $jumlahDataPerHalaman = $request;
    $jumlahData = count(query("SELECT * FROM artikel"));

    $sortBy = isset($_POST['sort']) ? $_POST['sort'] : 'latest'; // Default to 'latest'
    $halaman = isset($_POST['halaman']) ? $_POST['halaman'] : 1;

    // Calculate the total number of pages based on the selected number of items per page
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

    // Calculate the offset for pagination
    $offset = ($halaman - 1) * $request;

    // Modify your SQL query to include sorting and pagination
    $artikel = query("SELECT * FROM artikel ORDER BY published_at " . ($sortBy == 'latest' ? 'DESC' : 'ASC') . " LIMIT $offset, $request");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="row gap-5 box-content gx-0 mb-5">
        <div class="button-main">
            <p>Showing 1 - <?php echo $request ?> of <?php echo $jumlahData ?></p>
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

</body>

</html>
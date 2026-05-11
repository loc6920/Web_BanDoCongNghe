<?php

include("db.php");

/* LẤY DỮ LIỆU */

$product_name = $_POST['product_name'];

$category_id = $_POST['category_id'];

$price = $_POST['price'];

$stock = $_POST['stock'];

$description = $_POST['description'];

/* ẢNH */

$imageName = $_FILES['image']['name'];

$tmpName = $_FILES['image']['tmp_name'];

/* ĐƯỜNG DẪN */

$folder = "uploads/" . $imageName;

/* UPLOAD */

move_uploaded_file(
    $tmpName,
    $folder
);

/* INSERT */

$sql = "
INSERT INTO products(
    product_name,
    category_id,
    price,
    stock,
    description,
    image
)
VALUES(
    '$product_name',
    '$category_id',
    '$price',
    '$stock',
    '$description',
    '$imageName'
)
";

/* QUERY */

mysqli_query($conn, $sql);

/* QUAY LẠI */

header("Location: admin.php#product_section");

?>
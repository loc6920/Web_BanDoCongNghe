<?php

include("db.php");

/* LẤY ID */

$id = $_GET['id'];

/* SQL */

$sql = "
DELETE FROM products
WHERE product_id = '$id'
";

/* QUERY */

mysqli_query($conn, $sql);

/* QUAY LẠI */
header("Location: admin.php#product_section");
?>
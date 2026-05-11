<?php

include("db.php");

/* LẤY ID */

$id = $_GET['id'];

/* QUERY */

$sql = "
SELECT * FROM products
WHERE product_id = '$id'
";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>

<html>

<head>

    <title>Sửa sản phẩm</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body class="body_staff">

<form
    action="update_product.php"
    method="POST"
    enctype="multipart/form-data"
    class="product-form"
    style="
        display:block;
    "
>

    <h2>Sửa sản phẩm</h2>

    <!-- ID -->

    <input
        type="hidden"
        name="product_id"
        value="<?= $row['product_id'] ?>"
    >

    <!-- TÊN -->

    <div class="form-group">

        <label>Tên sản phẩm</label>

        <input
            type="text"
            name="product_name"
            value="<?= $row['product_name'] ?>"
        >

    </div>

    <!-- GIÁ -->

    <div class="form-group">

        <label>Giá</label>

        <input
            type="number"
            name="price"
            value="<?= $row['price'] ?>"
        >

    </div>

    <!-- SỐ LƯỢNG -->

    <div class="form-group">

        <label>Số lượng</label>

        <input
            type="number"
            name="stock"
            value="<?= $row['stock'] ?>"
        >

    </div>

    <!-- MÔ TẢ -->

    <div class="form-group">

        <label>Mô tả</label>

        <textarea
            name="description"
        ><?= $row['description'] ?></textarea>

    </div>

    <!-- ẢNH -->

    <div class="form-group">

        <label>Ảnh mới</label>

        <input
            type="file"
            name="image"
        >
    </div>
    <button type="submit">
        Cập nhật
    </button>

</form>

</body>

</html>
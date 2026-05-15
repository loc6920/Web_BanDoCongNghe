<?php
include("db.php");
/* DATA */
$id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$category_id = $_POST['category_id'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$description = $_POST['description'];


/* IMAGE */
$imageName = $_FILES['image']['name'];
$tmpName = $_FILES['image']['tmp_name'];

/* CÓ ẢNH */

if($imageName != ""){

    move_uploaded_file(
        $tmpName,
        "uploads/" . $imageName
    );

    $sql = "
    UPDATE products
    SET
        product_name = '$product_name',
        category_id = '$category_id',
        price = '$price',
        stock = '$stock',
        description = '$description',
        image = '$imageName'
    WHERE product_id = '$id'
    ";

}else{

    $sql = "
    UPDATE products
    SET
        product_name = '$product_name',
        category_id = '$category_id',
        price = '$price',
        stock = '$stock',
        description = '$description'
    WHERE product_id = '$id'
    ";
}

/* QUERY */

mysqli_query($conn, $sql);

/* REDIRECT */
header("Location: admin.php#product_section");

?>
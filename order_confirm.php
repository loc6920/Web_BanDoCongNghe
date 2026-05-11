<?php

include("db.php");
session_start();

if(!isset($_SESSION['users'])){
    header("Location: dangnhap.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['users']['user_id'];

$totalPrice = 0;

/* tính tổng tiền */
foreach($cart as $product_id => $quantity){

    $sql = "SELECT price FROM products
    WHERE product_id='$product_id'";

    $result = mysqli_query($conn,$sql);

    $product = mysqli_fetch_assoc($result);

    if($product){

        $totalPrice += $product['price'] * $quantity;

    }
}

/* tạo order */
$sql = "INSERT INTO orders(total_price,status,created_at,user_id)
VALUES('$totalPrice','pending',NOW(),'$user_id')";

mysqli_query($conn,$sql);

/* lấy order id mới */
$order_id = mysqli_insert_id($conn);

/* thêm order item */
foreach($cart as $product_id => $quantity){

    $sql = "SELECT price FROM products
    WHERE product_id='$product_id'";

    $result = mysqli_query($conn,$sql);

    $product = mysqli_fetch_assoc($result);

    if($product){

        $price = $product['price'];

        $sql_item = "INSERT INTO order_items(quantity,price,product_id,order_id)
        VALUES('$quantity','$price','$product_id','$order_id')";

        mysqli_query($conn,$sql_item);

    }
}

/* xóa cart */
unset($_SESSION['cart']);

header("Location: cart.php");

?>
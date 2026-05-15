<?php

include("db.php");

session_start();

$action=$_GET['action'];

$id=$_GET['id'];

if(!isset($_SESSION['cart'])){
    $_SESSION['cart']=[];
}

/* PLUS */
if($action=="plus"){

    $sql="
    SELECT stock 
    FROM products
    WHERE product_id='$id'
    ";

    $result=mysqli_query($conn,$sql);

    $product=mysqli_fetch_assoc($result);

    $stock=$product['stock'];

    if($_SESSION['cart'][$id] < $stock){

        $_SESSION['cart'][$id]++;

    }

}

/* MINUS */
if($action=="minus"){

    $_SESSION['cart'][$id]--;

    if($_SESSION['cart'][$id] <= 0){

        unset($_SESSION['cart'][$id]);

    }

}

/* REMOVE */
if($action=="remove"){

    unset($_SESSION['cart'][$id]);

}

header("location:cart.php");

?>
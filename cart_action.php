<?php

session_start();

$action = $_GET['action'];
$id = $_GET['id'];

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

/* TĂNG */
if($action == 'plus'){
    $_SESSION['cart'][$id]++;
}

/* GIẢM */
if($action == 'minus'){
    $_SESSION['cart'][$id]--;
    if($_SESSION['cart'][$id] <= 0){
        unset($_SESSION['cart'][$id]);
    }
}

/* XÓA */
if($action == 'remove'){
    unset($_SESSION['cart'][$id]);
}
header("Location: cart.php");

?>
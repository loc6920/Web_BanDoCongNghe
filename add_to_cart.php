<?php

include("db.php");

session_start();

$id=$_POST['product_id'];

if(!isset($_SESSION['cart'])){
    $_SESSION['cart']=[];
}

/* GET STOCK */
$sql="
SELECT stock 
FROM products
WHERE product_id='$id'
";

$result=mysqli_query($conn,$sql);

$product=mysqli_fetch_assoc($result);

$stock=$product['stock'];

/* IF EXISTS */
if(isset($_SESSION['cart'][$id])){

    if($_SESSION['cart'][$id] < $stock){

        $_SESSION['cart'][$id]++;

    }

}else{

    if($stock > 0){

        $_SESSION['cart'][$id]=1;

    }

}

header("location:home.php");

?>
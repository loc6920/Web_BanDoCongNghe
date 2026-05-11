<?php

include("db.php");

if(isset($_GET['id']) && isset($_GET['status'])){

    $id = $_GET['id'];

    $status = $_GET['status'];

    $sql = "UPDATE orders
    SET status='$status'
    WHERE order_id='$id'";

    mysqli_query($conn,$sql);

    header("Location: staff.php?filter=$status");
    exit;
}

?>
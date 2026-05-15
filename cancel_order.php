<?php

include("db.php");

session_start();

/* CHECK LOGIN */

if(!isset($_SESSION['users'])){

    header(
        "Location: dangnhap.php"
    );

    exit;
}

/* USER */

$user_id =
$_SESSION['users']['user_id'];

/* ORDER */

$order_id =
$_GET['id'];

/* FROM */

$from =
$_GET['from']
?? 'profile';

/* GET ORDER ITEMS */

$sql = "
SELECT *
FROM order_items
WHERE order_id='$order_id'
";

$result = mysqli_query(
    $conn,
    $sql
);

/* RESTORE STOCK */

while(
    $item =
    mysqli_fetch_assoc(
        $result
    )
){

    $product_id =
    $item['product_id'];

    $quantity =
    $item['quantity'];

    /* GET PRODUCT */

    $sql_product = "
    SELECT stock
    FROM products
    WHERE product_id='$product_id'
    ";

    $result_product =
    mysqli_query(
        $conn,
        $sql_product
    );

    $product =
    mysqli_fetch_assoc(
        $result_product
    );

    /* NEW STOCK */

    $new_stock =
    $product['stock']
    +
    $quantity;

    /* UPDATE STOCK */

    $sql_update = "
    UPDATE products
    SET stock='$new_stock'
    WHERE product_id='$product_id'
    ";

    mysqli_query(
        $conn,
        $sql_update
    );
}

/* DELETE ORDER ITEMS */

$sql_delete_items = "
DELETE FROM order_items
WHERE order_id='$order_id'
";

mysqli_query(
    $conn,
    $sql_delete_items
);

/* DELETE ORDER */

if($from == "staff"){

    $sql_delete_order = "
    DELETE FROM orders
    WHERE order_id='$order_id'
    ";

}else{

    $sql_delete_order = "
    DELETE FROM orders
    WHERE order_id='$order_id'
    AND user_id='$user_id'
    ";
}

mysqli_query(
    $conn,
    $sql_delete_order
);

/* BACK */

if($from == "staff"){

    header(
        "Location: staff.php?filter=pending"
    );

}else{

    header(
        "Location: profile.php#orderTab"
    );
}

exit;

?>
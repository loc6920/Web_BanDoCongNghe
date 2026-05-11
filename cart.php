<?php
include("db.php");
session_start();
$cart = $_SESSION['cart'] ?? [];
$items = [];
$totalPrice = 0;
$totalQuantity = 0;

if($cart){
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $sql = "SELECT * FROM products WHERE product_id IN ($ids)";
    $result = mysqli_query($conn,$sql);

    while($row = mysqli_fetch_assoc($result)){

        $id = $row['product_id'];
        $quantity = intval($cart[$id]);

        if($quantity <= 0) continue;

        $row['quantity'] = $quantity;
        $row['subtotal'] = $quantity * $row['price'];
        $totalPrice += $row['subtotal'];

        $totalQuantity += $quantity;
        $items[] = $row;
    }
}


$user_id = $_SESSION['users']['user_id'];

$sql_address = "SELECT * FROM addresses
WHERE user_id='$user_id'";

$result_address = mysqli_query($conn,$sql_address);

$address = mysqli_fetch_assoc($result_address);

$isAddress = false;

if($address &&
$address['phone'] != '' &&
$address['detail'] != ''){

    $isAddress = true;
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - 84 STORE</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body class="body_staff">
    <!-- NAVBAR -->
    <div class="navbar">
        <div class="nav-left">
            <a href="home.php" class="logo">84 STORE</a>
        </div>
        <div class="nav-right">
            <a href="home.php">
                <button class="button-search">Trang chủ</button>
            </a>
        </div>
    </div>

    <!-- MAIN -->
    <main>
        <div class="dashboard">
            <!-- HEADER -->
            <div class="admin-header">
                <h1>Giỏ hàng của bạn</h1>
                <a href="home.php" class="back-home-btn">Quay lại mua sắm</a>
            </div>

            <?php if(!$items){ ?>
            <!-- EMPTY -->
            <div class="card">

                <p style="font-size:16px;">
                    Giỏ hàng đang trống
                </p>

                <a href="home.php">
                    <button class="button-search" style="margin-top:20px;">Mua sắm ngay</button>
                </a>
            </div>
            <?php } else { ?>

            <!-- SUMMARY -->
            <div class="cards">
                <div class="card">
                    <p>Tổng sản phẩm</p>
                    <h2><?= $totalQuantity ?></h2>
                </div>

                <div class="card">
                    <p>Tổng thanh toán</p>
                    <h2><?= number_format($totalPrice) ?>đ</h2>
                </div>
            </div>

             <!-- THÔNG TIN GIAO HÀNG -->
                <div class="card" style="margin-bottom:20px;">
                    <h2 style="margin-bottom:20px;">
                        Thông tin giao hàng
                    </h2>
                    <p style="margin-bottom:10px;">
                        <strong>Họ tên:</strong>
                        <?= $_SESSION['users']['name'] ?>
                    </p>
                    <p style="margin-bottom:10px;">
                        <strong>Email:</strong>
                        <?= $_SESSION['users']['email'] ?>
                    </p>
                    <p style="margin-bottom:10px;">
                        <strong>Số điện thoại:</strong>
                        <?= $address['phone'] ?? 'Chưa cập nhật' ?>
                    </p>
                    <p>
                        <strong>Địa chỉ:</strong>
                        <?= $address['detail'] ?? 'Chưa cập nhật' ?>
                    </p>

                </div>
            <!-- TABLE -->
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2>Danh sách sản phẩm</h2>
                    <form action="order_confirm.php" method="post">
                        <button type="submit" class="create_btn">Xác nhận đặt hàng</button>
                    </form>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($items as $item){ ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= number_format($item['price']) ?>đ</td>
                            <td>

                                <div style="display:flex; align-items:center; gap:10px;">

                                    <!-- GIẢM -->
                                    <a href="cart_action.php?action=minus&id=<?= $item['product_id'] ?>">
                                        <button class="delete-btn">
                                            -
                                        </button>
                                    </a>

                                    <!-- SỐ LƯỢNG -->
                                    <span style="font-weight:600;">
                                        <?= $item['quantity'] ?>
                                    </span>

                                    <!-- TĂNG -->
                                    <a href="cart_action.php?action=plus&id=<?= $item['product_id'] ?>">
                                        <button class="create_btn">
                                            +
                                        </button>
                                    </a>
                                </div>

                            </td>
                            <td><?= number_format($item['subtotal']) ?>đ</td>
                            <td>
                                <a href="cart_action.php?action=remove&id=<?= $item['product_id'] ?>">
                                    <button class="delete-btn">Xóa</button>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">

            <div class="footer-box">
                <h2>84 STORE</h2>
                <p>Hệ thống bán điện thoại, laptop và phụ kiện công nghệ hiện đại.</p>
            </div>

            <div class="footer-box">
                <h3>Liên kết</h3>
                <a href="home.php">Trang chủ</a>
                <a href="#">Sản phẩm</a>
                <a href="#">Liên hệ</a>
            </div>

            <div class="footer-box">
                <h3>Liên hệ</h3>
                <p>Email: 84store@gmail.com</p>
                <p>Hotline: 038 4555 236</p>
                <p>Địa chỉ: Trà Vinh</p>
            </div>
        </div>
        <div class="footer-bottom">
            © 2025 84 STORE. All rights reserved.
        </div>
    </footer>

</body>

</html>
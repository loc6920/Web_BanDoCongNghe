<?php

include("db.php");

$order_id=$_GET['id'];
$from=$_GET['from'] ?? '';

$sql_user="SELECT u.name,u.email,a.phone,a.detail,a.ward,a.district,a.province
FROM orders o
JOIN users u ON o.user_id=u.user_id
LEFT JOIN addresses a ON u.user_id=a.user_id
WHERE o.order_id='$order_id'";

$result_user=mysqli_query($conn,$sql_user);
$user=mysqli_fetch_assoc($result_user);

$sql="SELECT p.product_name,oi.quantity,oi.price,
(oi.quantity * oi.price) AS subtotal
FROM order_items oi
JOIN products p ON oi.product_id=p.product_id
WHERE oi.order_id='$order_id'";

$result=mysqli_query($conn,$sql);

$total=0;

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="./style.css">

</head>

<body class="body_staff">

    <!-- NAVBAR -->
    <div class="navbar">

        <div class="nav-left">
            <a href="home.php" class="logo">84 STORE</a>
        </div>

        <div class="nav-right">

            <?php if($from=="staff"){ ?>

            <a href="staff.php">
                <button class="button-search">Quay lại Nhân viên</button>
            </a>

            <?php }
            else if($from== "admin"){ ?> 

            <a href="admin.php">
                <button class="button-search">Quay lại Quản trị</button>
            </a>
            <?php } 
            else if ($from== "profile") { ?> 
                <a href="profile.php">
                <button class="button-search">Quay lại</button>
                </a>
            <?php } ?>

        </div>

    </div>

    <!-- MAIN -->
    <main>

        <div class="dashboard">

            <!-- HEADER -->
            <div class="admin-header">

                <div>
                    <h1>Chi tiết đơn hàng #<?= $order_id ?></h1>
                    <p style="color:#666; margin-top:8px;">
                        Thông tin khách hàng và sản phẩm đã mua
                    </p>
                </div>

            </div>

            <!-- USER INFO -->
            <div class="card" style="margin-bottom:20px;">

                <h2 style="margin-bottom:20px;">
                    Thông tin khách hàng
                </h2>

                <div class="profile-info">

                    <div class="info-item">
                        <span>Họ tên</span>
                        <strong><?= $user['name'] ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Email</span>
                        <strong><?= $user['email'] ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Số điện thoại</span>
                        <strong><?= $user['phone'] ?? 'Chưa cập nhật' ?></strong>
                    </div>

                    <div class="info-item">

                        <span>Địa chỉ</span>

                        <strong>
                            <?= $user['detail'] ?? '' ?>,
                            <?= $user['ward'] ?? '' ?>,
                            <?= $user['district'] ?? '' ?>,
                            <?= $user['province'] ?? '' ?>
                        </strong>

                    </div>

                </div>

            </div>

            <!-- ORDER DETAIL -->
            <div class="card">

                <h2 style="margin-bottom:20px;">
                    Sản phẩm đã mua
                </h2>

                <table>

                    <thead>

                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php while($row=mysqli_fetch_assoc($result)){ 

                        $total += $row['subtotal'];

                        ?>

                        <tr>

                            <td><?= $row['product_name'] ?></td>

                            <td><?= $row['quantity'] ?></td>

                            <td><?= number_format($row['price']) ?>đ</td>

                            <td><?= number_format($row['subtotal']) ?>đ</td>

                        </tr>

                        <?php } ?>

                        <tr>

                            <td colspan="3" style="text-align:right; font-weight:700;">
                                Tổng đơn hàng
                            </td>

                            <td style="font-weight:700; color:#111827;">
                                <?= number_format($total) ?>đ
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

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
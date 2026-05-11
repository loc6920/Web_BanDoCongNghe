<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>84 STORE - Staff</title>

    <link rel="stylesheet" href="./style.css">

    <?php
    include("db.php");
    session_start();
    // cập nhật role
        $user_id=$_SESSION["users"]["user_id"];
        $sql_user="SELECT * 
        FROM users 
        WHERE user_id='$user_id'";
        $result_user=mysqli_query($conn,$sql_user);
        if(mysqli_num_rows($result_user)>0){
            $_SESSION["users"]=mysqli_fetch_assoc($result_user);
        }

    /* CHECK LOGIN */
        if(!isset($_SESSION["users"])){
            header("Location: dangnhap.php");
            exit;
        }
        /* CHECK ROLE */
        if($_SESSION["users"]["role"] != 2){
            header("Location: home.php");
            exit;
        }
    ?>
</head>

<body class="body_staff">

    <!-- NAVBAR -->
    <div class="navbar">

        <div class="nav-left">
            <a href="home.php" class="logo">84 STORE</a>
        </div>

        <div class="nav-right">

            <?php if(isset($_SESSION["users"])) { ?>

            <div class="user-box">

                <h2 class="user-name">
                    <?= $_SESSION["users"]["name"] ?>
                </h2>

                <a href="./logout.php">
                    <button class="button-search">
                        Đăng xuất
                    </button>
                </a>

            </div>

            <?php } ?>

        </div>

    </div>

    <!-- MAIN -->
    <main>

        <div class="dashboard">

            <!-- HEADER -->
            <div class="admin-header">

                <h1>Bảng điều khiển nhân viên</h1>

                <a href="home.php" class="back-home-btn">
                    Quay lại trang chủ
                </a>

            </div>

            <!-- FILTER CARD -->
            <div class="cards">

                <!-- PENDING -->
                <a href="staff.php?filter=pending" style="flex:1;">
                    <div class="card">
                        <p>Chờ xử lý</p>
                        <?php
                        $sql = "SELECT COUNT(order_id) AS total_pending
                        FROM orders
                        WHERE status='pending'";
                        $result = mysqli_query($conn,$sql);
                        $value = mysqli_fetch_assoc($result);
                        ?>
                        <h2><?= $value['total_pending'] ?? 0 ?></h2>
                    </div>
                </a>

                <!-- SHIPPING -->
                <a href="staff.php?filter=shipping" style="flex:1;">

                    <div class="card">
                        <p>Đang vận chuyển</p>
                        <?php
                        $sql = "SELECT COUNT(order_id) AS total_shipping
                        FROM orders
                        WHERE status='shipping'";
                        $result = mysqli_query($conn,$sql);
                        $value = mysqli_fetch_assoc($result);
                        ?>
                        <h2><?= $value['total_shipping'] ?? 0 ?></h2>
                    </div>
                </a>

                <!-- COMPLETED -->
                <a href="staff.php?filter=completed" style="flex:1;">
                    <div class="card">
                        <p>Hoàn thành</p>
                        <?php
                        $sql = "SELECT COUNT(order_id) AS total_completed
                        FROM orders
                        WHERE status='completed'";
                        $result = mysqli_query($conn,$sql);
                        $value = mysqli_fetch_assoc($result);
                        ?>
                        <h2><?= $value['total_completed'] ?? 0 ?></h2>
                    </div>
                </a>
            </div>

            <!-- TABLE -->
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2>Quản lý đơn hàng</h2>
                    <a href="staff.php">
                        <button class="button-search">Tất cả</button>
                    </a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $filter = $_GET['filter'] ?? '';
                        $sql = "SELECT o.order_id,o.total_price,o.status,o.created_at,u.name
                        FROM orders o
                        JOIN users u
                        ON o.user_id = u.user_id";

                        if($filter != ''){
                            $sql .= " WHERE o.status='$filter'";
                        }
                        $sql .= " ORDER BY o.created_at DESC";
                        $result = mysqli_query($conn,$sql);
                        while($row = mysqli_fetch_assoc($result)){
                        ?>

                        <tr>
                            <!-- ORDER ID -->
                            <td>#<?= $row['order_id'] ?></td>

                            <!-- CUSTOMER -->
                            <td><?= $row['name'] ?></td>

                            <!-- DATE -->
                            <td><?= $row['created_at'] ?></td>

                            <!-- TOTAL -->
                            <td><?= number_format($row['total_price']) ?>đ</td>

                            <!-- STATUS -->
                            <td>
                                <?php if($row['status'] == 'pending'){ ?>
                                <span style="color:orange; font-weight:600;">
                                    Chờ xử lý
                                </span>
                                <?php } ?>
                                <?php if($row['status'] == 'shipping'){ ?>
                                <span style="color:blue; font-weight:600;">
                                    Đang vận chuyển
                                </span>
                                <?php } ?>
                                <?php if($row['status'] == 'completed'){ ?>
                                <span style="color:green; font-weight:600;">
                                    Hoàn thành
                                </span>
                                <?php } ?>
                            </td>

                            <!-- ACTION -->
                            <td>
                                <div style="display:flex; gap:10px; flex-wrap:wrap;">

                                    <!-- XEM CHI TIẾT -->
                                    <a href="order_detail.php?id=<?= $row['order_id'] ?>&from=staff">
                                        <button class="edit-btn">
                                            Xem chi tiết
                                        </button>
                                    </a>

                                    <!-- CHỜ XỬ LÝ -->
                                    <?php if($row['status'] == 'pending'){ ?>
                                    <a href="update_order.php?id=<?= $row['order_id'] ?>&status=shipping">
                                        <button class="button-search">
                                            Giao vận chuyển
                                        </button>
                                    </a>
                                    <?php } ?>

                                    <!-- ĐANG VẬN CHUYỂN -->
                                    <?php if($row['status'] == 'shipping'){ ?>
                                    <a href="update_order.php?id=<?= $row['order_id'] ?>&status=completed">
                                        <button class="create_btn">
                                            Hoàn thành
                                        </button>
                                    </a>
                                    <?php } ?>
                                    <!-- HOÀN THÀNH -->
                                    <?php if($row['status'] == 'completed'){ ?>
                                    <button class="delete-btn">
                                        Đã hoàn thành
                                    </button>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
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
                <p>
                    Hệ thống bán điện thoại, laptop và phụ kiện công nghệ hiện đại.
                </p>
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
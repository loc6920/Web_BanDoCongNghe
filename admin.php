<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>84 STORE</title>
    <link rel="stylesheet" href="./style.css">
    <?php
        include("db.php");
        session_start();
        $roles = [
            1 => "Quản trị",
            2 => "Nhân viên",
            3 => "Người Dùng"
        ];
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
        if($_SESSION["users"]["role"] != 1){
            header("Location: home.php");
            exit;
        }
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="body_staff">
    <!-- NAVBAR -->
    <div class="navbar">
        <!-- LEFT -->
        <div class="nav-left">
            <a href="home.php" class="logo">84 STORE</a>
        </div>
        <!-- RIGHT -->
        <div class="nav-right">
            <?php 
        if(isset($_SESSION["users"])) {
        ?>
            <div class="user-box">
                <h2 class="user-name">
                    <?= $_SESSION["users"]["name"] ?>
                </h2>
                <a href="./logout.php">
                    <button class="button-search logout-btn">
                        Đăng xuất
                    </button>
                </a>
            </div>
            <?php } else { 
                // header("Location: home.php");
                // exit;
             } ?>
        </div>
    </div>
    <!-- MAIN -->
    <main class="body_staff">
        <div class="dashboard">
            <div class="admin-header">
                <h1>Bảng điều khiển Admin</h1>
                <a href="home.php" class="back-home-btn">Quay lại trang chủ</a>
            </div>
            <!-- CARDS -->
            <div class="cards">
                <div class="card">
                    <p>Tổng doanh thu</p>
                    <?php 
                        $sql = "
                        select sum(total_price) as total_price
                        from orders
                        WHERE status = 'completed'
                        ";
                        $result = mysqli_query($conn, $sql);
                        $value = mysqli_fetch_assoc($result);
                     ?>
                    <h2>
                        <?= number_format($value['total_price']) ?? 0 ?>đ
                    </h2>
                </div>


                <div class="card">
                    <p>Đơn hàng</p>
                    <?php 
                        $sql = "
                        select count(order_id) as total_order
                        from orders
                        WHERE status = 'completed'
                        ";
                        $result = mysqli_query($conn, $sql);
                        $value = mysqli_fetch_assoc($result);
                     ?>
                    <h2>
                        <?= $value['total_order'] ?? 0 ?>
                    </h2>
                </div>


                <div class="card">
                    <p>Sản phẩm</p>
                    <?php 
                        $sql = "
                        select count(stock) as total_stock
                        from products
                        ";
                        $result = mysqli_query($conn, $sql);
                        $value = mysqli_fetch_assoc($result);
                     ?>
                    <h2>
                        <?= $value['total_stock'] ?? 0 ?>
                    </h2>

                </div>
                <div class="card">
                    <p>Người dùng</p>
                    <?php 
                        $sql = "
                        select count(name) as total_users
                        from users
                        ";
                        $result = mysqli_query($conn, $sql);
                        $value = mysqli_fetch_assoc($result);
                     ?>
                    <h2>
                        <?= $value['total_users'] ?? 0 ?>
                    </h2>
                </div>
            </div>

            <!-- TAB BUTTON -->
            <div class="cards">
                <button class="card button_category" onclick="ShowSection('order_section'); 
                window.location.hash='order_section';">
                    Đơn hàng</button>

                <button class="card button_category" onclick=" ShowSection('product_section');
                window.location.hash='product_section';">
                    Sản phẩm</button>

                <button class="card button_category" onclick=" ShowSection('user_section'); 
                window.location.hash='user_section';">
                    Người dùng</button>
            </div>
            <div class="cards">
                <!-- ---------------------  ĐƠN HÀNG -------------------- -->
                <div class="card section" id="order_section">
                    <h2>Quản lý đơn hàng</h2>

                    <table>
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Người đặt</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT o.order_id, o.created_at, o.total_price, o.status, u.name
                            FROM orders o JOIN users u
                            ON o.user_id = u.user_id
                            WHERE o.status = 'completed'
                            ORDER BY o.created_at DESC";
                            $result = mysqli_query($conn, $sql);

                            while($row = mysqli_fetch_assoc($result)){ ?>
                            <tr>
                                <!-- MÃ ĐƠN -->
                                <td>#<?= $row['order_id'] ?></td>

                                <!-- NGƯỜI ĐẶT -->
                                <td><?= $row['name'] ?></td>

                                <!-- NGÀY -->
                                <td><?= $row['created_at'] ?></td>

                                <!-- TỔNG GIÁ -->
                                <td><?= number_format($row['total_price']) ?>đ </td>

                                <!-- TRẠNG THÁI -->
                                <td><?= $row['status'] ?></td>
                                <!-- ACTION -->
                                <td>
                                    <a href="order_detail.php?id=<?= $row['order_id'] ?>">
                                        <button class="edit-btn"> Xem chi tiết</button>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- SẢN PHẨM -->
                <div class="card section" id="product_section">
                    <div style="display: flex; justify-content: space-between;">
                        <h2>Quản lý sản phẩm</h2>
                        <button class="create_btn" onclick="showForm()">Thêm sản phẩm</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thao tác</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                                $sql = " 
                                SELECT a.product_id, a.product_name, a.price, a.stock, a.image, b.name_category
                                FROM products as a, categories as b
                                WHERE a.category_id = b.category_id
                                ";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_assoc($result)){
                                ?>
                            <tr>
                                <!-- ẢNH -->
                                <td>
                                    <img src="uploads/<?= $row['image'] ?>" width="80" height="80"
                                        style="object-fit: cover;border-radius: 10px;">
                                </td>
                                <!-- TÊN -->
                                <td> <?= $row['product_name'] ?></td>

                                <!-- DANH MỤC -->
                                <td> <?= $row['name_category'] ?></td>

                                <!-- GIÁ -->
                                <td><?= number_format($row['price']) ?>đ</td>

                                <!-- SỐ LƯỢNG -->
                                <td> <?= $row['stock'] ?></td>
                                <!-- action -->
                                <td class="action-btn">
                                    <!-- SỬA -->
                                    <a href="edit_product.php?id=<?= $row['product_id'] ?>">
                                        <button class="edit-btn">Sửa</button>
                                    </a>
                                    <!-- XÓA -->
                                    <a href="delete_product.php?id=<?= $row['product_id'] ?>"
                                        onclick="return confirm('Xóa sản phẩm này?')">
                                        <button class="delete-btn">Xóa </button>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!--  NGƯỜI DÙNG  -->
                <div class="card section" id="user_section">
                    <h2>Quản lý người dùng</h2>

                    <table>
                        <thead>
                            <tr>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                                $sql = "select user_id, name, email, role from users";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?=$row["name"]?></td>
                                <td><?=$row["email"]?></td>
                                <td><?= $roles[$row["role"]] ?></td>
                                <td><button class="edit-btn"
                                        onclick="openRoleForm('<?= $row['user_id'] ?>','<?= $row['name'] ?>','<?= $row['role'] ?>')">Sửa</button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </main>

      <footer class="footer">
        <div class="footer-container">
            <!-- LEFT -->
            <div class="footer-box">
                <h2>84 STORE</h2>
                <p>Hệ thống bán điện thoại, laptop và phụ kiện công nghệ hiện đại.</p>
                <div class="socials">
                    <a href="#">Facebook</a>
                    <a href="#">Instagram</a>
                    <a href="#">TikTok</a>
                </div>
            </div>

            <!-- CENTER -->
            <div class="footer-box">
                <h3>Liên kết</h3>
                <a href="home.php">Trang chủ</a>
                <a href="#">Sản phẩm</a>
                <a href="#">Giới thiệu</a>
                <a href="#">Liên hệ</a>
            </div>

            <!-- RIGHT -->
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
    <!-- =============== form thêm sản phẩm ============= -->
    <form id="productForm" action="store_product.php" method="POST" enctype="multipart/form-data" class="product-form">

        <h2>Thêm sản phẩm</h2>

        <!-- ROW 1 -->
        <div class="form-row">
            <!-- TÊN -->
            <div class="form-group">
                <label>Tên sản phẩm</label>
                <input type="text" name="product_name" placeholder="Tên sản phẩm" required>
            </div>

            <!-- DANH MỤC -->
            <div class="form-group">
                <label>Danh mục</label>
                <select name="category_id">
                    <option value="1">
                        Điện thoại
                    </option>
                    <option value="2">
                        Laptop
                    </option>
                    <option value="3">
                        Phụ kiện
                    </option>
                </select>
            </div>
        </div>

        <!-- ROW 2 -->
        <div class="form-row">

            <!-- GIÁ -->
            <div class="form-group">
                <label>Giá</label>
                <input type="number" name="price" placeholder="Giá" required>
            </div>

            <!-- SỐ LƯỢNG -->
            <div class="form-group">

                <label>Số lượng</label>
                <input type="number" name="stock" placeholder="Số lượng" required>
            </div>
        </div>

        <!-- ROW 3 -->
        <div class="form-row">
            <!-- MÔ TẢ -->
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" placeholder="Mô tả sản phẩm"></textarea>
            </div>
        </div>

        <!-- ROW 4 -->
        <div class="form-row">
            <!-- ẢNH -->
            <div class="form-group">
                <label>Ảnh sản phẩm</label>
                <input type="file" name="image" required>
            </div>
        </div>
        <!-- BUTTON -->
        <div class="modal-buttons">
            <button type="submit">
                Lưu sản phẩm
            </button>
            <button type="button" onclick="closeForm()">
                Đóng
            </button>

        </div>
    </form>
    <!-- =========================== -->
    <form id="roleForm" method="POST" action="edit_role.php" class="product-form">
        <h2>Sửa quyền hạn</h2>
        <input type="hidden" name="user_id" id="role_user_id">
        <div class="form-group">
            <label>Tên người dùng</label>
            <input type="text" id="role_name" disabled>
        </div>
        <div class="form-group">
            <label>Vai trò</label>
            <select name="role" id="role_select">
                <option value="1">Quản trị</option>
                <option value="2">Nhân viên</option>
                <option value="3">Người dùng</option>
            </select>
        </div>
        <div class="modal-buttons">
            <button type="submit">Lưu</button>
            <button type="button" onclick="closeRoleForm()">Đóng</button>
        </div>
    </form>
    <!-- SCRIPT TAB -->
    <script src="js.js">
    </script>

</body>

</html>
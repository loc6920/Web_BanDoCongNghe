<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>84 STORE</title>
    <link rel="stylesheet" href="./style.css">
    <?php
        include("db.php");
        session_start();
        // cập nhật role
        if(isset($_SESSION["users"])){
            $user_id=$_SESSION["users"]["user_id"];
            $sql_user="SELECT * 
            FROM users 
            WHERE user_id='$user_id'";
            $result_user=mysqli_query($conn,$sql_user);
            if(mysqli_num_rows($result_user)>0){
                $_SESSION["users"]=mysqli_fetch_assoc($result_user);
            }

        }
        /* SEARCH */
        if(isset($_GET['keyword']) && $_GET['keyword'] != ""){
            $keyword = $_GET['keyword'];
            $sql = " SELECT * FROM products WHERE product_name LIKE '%$keyword%'";
            $isSearch = true;
        }else{ $sql = "SELECT * FROM products ";
        $isSearch = false;
        }
        /* CATEGORY */

        if(
            isset($_GET['category'])
        ){
            $category = $_GET['category'];
             $sql .= "WHERE category_id = '$category'";
        }
        $result = mysqli_query($conn, $sql);
    ?>
</head>

<body class="body_staff">
    <!-- NAVBAR -->
    <div class="navbar">
        <!-- LEFT -->
        <div class="nav-left">
            <a href="home.php" class="logo">84 STORE</a>
        </div>
        <!-- CENTER -->
        <form method="GET" class="header-search">
            <input class="input-search" type="text" name="keyword" placeholder="Tìm kiếm sản phẩm...">
            <button class="button-search" type="submit">
                Tìm kiếm
            </button>
        </form>
        <!-- RIGHT -->
        <div class="nav-right">
            <?php 
        if(isset($_SESSION["users"])) {
        ?>
            <div class="user-box">
                <a href="cart.php">
                    <button class="button-search">Giỏ hàng</button>
                </a>
                <?php 
                $user_id=$_SESSION["users"]["user_id"];
                 $sql_role="SELECT role 
                FROM users 
                WHERE user_id='$user_id'";

                $result_role=mysqli_query($conn,$sql_role);

                $user_role=mysqli_fetch_assoc($result_role);

                $role=$user_role["role"];
                
                if($role==1){ ?>
                <a href="admin.php">
                    <button class="button-search">
                        Quản trị
                    </button>
                </a>
                <?php } ?>

                <?php if($role==2){ ?>
                <a href="staff.php">
                    <button class="button-search">
                        Nhân viên
                    </button>
                </a>
                <?php } ?>
                <!-- =============== -->
                <a href="profile.php">
                    <button class="button-search">
                        <?= $_SESSION["users"]["name"] ?>
                    </button>
                </a>
                <!-- =============== -->
                <a href="./logout.php">
                    <button class="button-search logout-btn">
                        Đăng xuất
                    </button>
                </a>
            </div>
            <?php } else { ?>
            <a href="./dangnhap.php">
                <button class="button-search">
                    Đăng nhập
                </button>
            </a>
            <?php } ?>
        </div>
    </div>

    <!-- MAIN -->
    <main class="dashboard">
        <!-- BANNER -->
        <?php if(!$isSearch){ ?>
        <div class="card">
            <img src="./image/anh_back1.webp" style="width:100%; border-radius:10px;">
        </div>
        <?php } ?>

        <!-- DANH MỤC -->
        <div class="card body_product_category">
            <!-- danh mục sản phẩm -->
            <div>
                <h2>Danh mục sản phẩm</h2>
                <div style="display:flex;">
                    <!-- TẤT CẢ -->
                    <a href="home.php">
                        <button class="button_product_category">
                            Tất cả
                        </button>
                    </a>
                    <?php
                        $sql_category =
                        "SELECT * FROM categories";
                        $result_category =mysqli_query( $conn,$sql_category);
                        while($cat = mysqli_fetch_assoc($result_category)){?>
                    <a href="?category= <?= $cat['category_id'] ?>">
                        <button class="button_product_category">
                            <?= $cat['name_category'] ?>
                        </button>
                    </a>
                    <?php } 
                    ?>
                </div>
            </div>
            <div>
                <div>
                    <!-- sản phẩm -->
                    <div class="product-list">
                        <?php
                        while(
                            $row = mysqli_fetch_assoc($result)
                        ){
                        ?>
                        <div class="product-card">
                            <img src="uploads/<?= $row['image'] ?>">
                            <h3 class="product-name">
                                <?= strtolower($row['product_name']) ?>
                            </h3>
                            <p class="product-price"><?= number_format($row['price']) ?> đ</p>
                            <p class="product-stock"> Còn <?php if($row['stock'] < 0){ echo 0;} else echo $row['stock'];  ?> sản phẩm</p>
                            <?php if($row['stock'] > 0){ ?>
                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <button type="submit" class="cart-btn">
                                    Thêm vào giỏ hàng
                                </button>
                            </form>

                            <?php }else{ ?>
                            <button class="cart-btn" style="background-color:red;" disabled>
                                Hết hàng
                            </button>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
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

</body>

</html>
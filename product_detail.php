<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <?php

    include("db.php");

    session_start();

    $id=$_GET['id'];

    $sql="
    SELECT p.*,c.name_category AS category_name
    FROM products p
    LEFT JOIN categories c
    ON p.category_id=c.category_id
    WHERE p.product_id='$id'
    ";

    $result=mysqli_query($conn,$sql);

    if(!$result){

        die(mysqli_error($conn));

    }

    $product=mysqli_fetch_assoc($result);

    if(!$product){

        header("location:home.php");

        exit;

    }

    ?>

    <title>
        <?= $product['product_name'] ?> - 84 STORE
    </title>

    <link rel="stylesheet" href="style.css">

</head>

<body class="body_staff">

    <!-- NAVBAR -->
    <div class="navbar">

        <div class="nav-left">

            <a href="home.php" class="logo">
                84 STORE
            </a>

        </div>

        <div class="nav-right">

            <a href="home.php">

                <button class="button-search">
                    Trang chủ
                </button>

            </a>

            <a href="cart.php">

                <button class="button-search">
                    Giỏ hàng
                </button>

            </a>

        </div>

    </div>

    <!-- MAIN -->
    <main>

        <div class="dashboard">

            <div class="product-detail-wrap">

                <!-- LEFT -->
                <div class="product-gallery">

                    <div class="main-image">

                        <img src="uploads/<?= $product['image'] ?>">

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="product-info-box">

                    <!-- CATEGORY -->
                    <p class="product-category">

                        <?= $product['category_name'] ?>

                    </p>

                    <!-- NAME -->
                    <h1 class="product-title">

                        <?= $product['product_name'] ?>

                    </h1>

                    <!-- PRICE -->
                    <div class="product-price-box">

                        <span class="product-price">

                            <?= number_format($product['price']) ?>đ

                        </span>

                        <?php if($product['stock'] > 0){ ?>

                        <span class="stock-badge">
                            Còn hàng
                        </span>

                        <?php }else{ ?>

                        <span class="stock-badge out">
                            Hết hàng
                        </span>

                        <?php } ?>

                    </div>

                    <!-- META -->
                    <div class="product-meta">

                        <div class="meta-item">

                            <span class="meta-label">
                                Tồn kho
                            </span>

                            <strong>
                                <?= max(0,$product['stock']) ?> sản phẩm
                            </strong>

                        </div>

                        <div class="meta-item">

                            <span class="meta-label">
                                Danh mục
                            </span>

                            <strong>
                                <?= $product['category_name'] ?>
                            </strong>

                        </div>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="product-description">

                        <h3>
                            Mô tả sản phẩm
                        </h3>

                        <p>

                            <?= nl2br($product['description']) ?>

                        </p>

                    </div>

                    <!-- BUTTON -->
                    <?php if($product['stock'] > 0){ ?>

                    <form action="add_to_cart.php" method="post">

                        <input
                        type="hidden"
                        name="product_id"
                        value="<?= $product['product_id'] ?>">

                        <button type="submit" class="buy-btn">

                            Thêm vào giỏ hàng

                        </button>

                    </form>

                    <?php }else{ ?>

                    <button class="buy-btn out-btn" disabled>

                        Hết hàng

                    </button>

                    <?php } ?>

                </div>

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
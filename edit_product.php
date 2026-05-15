<!DOCTYPE html>
<html>
<head>
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="style.css">
    <?php
    include("db.php");
    /* LẤY ID */
    $id = $_GET['id'];
    /* QUERY */
    $sql = "SELECT * FROM products
    WHERE product_id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?>
</head>
<body class="body_staff">
    <form action="update_product.php" method="POST" enctype="multipart/form-data" class="product-form" style="display:block;">
        <h2>Sửa sản phẩm</h2>
        <!-- ID -->
        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">

        <!-- TÊN -->
        <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" name="product_name" value="<?= $row['product_name'] ?>">
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

        <!-- GIÁ -->
        <div class="form-group">
            <label>Giá</label>
            <input type="number" name="price" value="<?= $row['price'] ?>">
        </div>
        <!-- SỐ LƯỢNG -->
        <div class="form-group">
            <label>Số lượng</label>
            <input type="number" name="stock" value="<?= $row['stock'] ?>">
        </div>
        <!-- MÔ TẢ -->
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description"><?= $row['description'] ?></textarea>
        </div>
        <!-- ẢNH -->
        <div class="form-group">
            <label>Ảnh mới</label>
            <input type="file" name="image">
        </div>
        <div>
             <a class="back-home-btn" href="admin.php#product_section">Hủy</a>
            <button class="back-home-btn">
                Cập nhật
            </button>
        </div>
       
    </form>
</body>
</html>
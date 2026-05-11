<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng kí</title>
    <link rel="stylesheet" href="style.css">
   <?php 
    include("db.php");
    $name = "";
    $email = "";
    $message = "";
    $role = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $role = 3;

        // Check email tồn tại
        $check_sql = "SELECT email FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_sql);

        if (!$stmt) {
            die("Lỗi prepare check: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt,"s",$email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0){
            $message = "Email đã tồn tại";
        }
        else {
            // Hash password
            $hashpassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert dữ liệu vào data base
            $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_sql);

            if (!$stmt) {
                die("Lỗi prepare insert: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt,"ssss",$name, $email, $hashpassword, $role);

            if(mysqli_stmt_execute($stmt)){
                header("Location: dangnhap.php");
                exit();
            } else {
                echo "<script>alert('Đăng kí thất bại');</script>";
            }
        }
    }
    ?>
</head>

<body class="body_login">
    <div class="login-container">

        <h2>Đăng Kí</h2>

        <form method="POST">
            <!-- họ và tên -->
            <div class="form-group">

                <label>Họ và tên</label>

                <input type="text" name="name" placeholder="Nhập họ và tên của bạn" required value="<?php echo htmlspecialchars($name); ?>">

            </div>

            <!-- email -->
            <div class="form-group">

                <label>Email</label>

                <input type="email" name="email" placeholder="Nhập email của bạn" required value="<?php echo htmlspecialchars($email); ?>">

            </div>
            <!-- mật khẩu -->
            <div class="form-group">

                <label>Mật khẩu</label>

                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
            </div>
            <!-- xác thực mật khẩu -->
            <div class="form-group">

                <label>Nhập lại mật khẩu</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Nhập lại mật khẩu">
                <p id="err_pass" style="height: 10px;"></p>
                <?php if($message != "") echo "<p style='color:red;'>$message</p>"; ?>
            </div>

            <button type="submit" class="login-btn">Đăng Kí</button>

        </form>

        <div class="extra-links">
            <p>Đã có tài khoản? <a href="./dangnhap.php">Đăng Nhập</a></p>

        </div>
    </div>

    <script src="js.js"> </script>
</body>

</html>
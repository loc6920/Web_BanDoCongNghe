<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="style.css">
    <?php  
        include("db.php");
        session_start();
        $message = "";
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $email = $_POST["email"];
            $password = $_POST["password"];
            //tìm email
            $sql = "select * from users where email =?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt,"s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){ 
                $us = mysqli_fetch_assoc($result);
                
                //kiem tra pass 
                if(password_verify($password, $us["password"])){
                    $_SESSION["users"]= $us;
                    header("location:home.php");
                }
                else {
                $message = "Email hoặc mật khẩu sai";
                }
            }
            else {
                $message = "Email hoặc mật khẩu sai";
                }
        }
    ?>
</head>

<body class="body_login">
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <form method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Nhập email của bạn" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                <?php if($message != "") echo "<p style='color:red;'>$message</p>"; ?>
            </div>
            <button type="submit" class="login-btn">Đăng nhập</button>
        </form>
        <div class="extra-links">
            <p><a href="#">Quên mật khẩu?</a></p>
            <p>Chưa có tài khoản? <a href="./dangki.php">Đăng ký</a></p>
        </div>
    </div>
</body>
</html>
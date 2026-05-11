<?php
session_start();
/* chỉ xóa thông tin đăng nhập */
unset($_SESSION['users']);

header("Location: home.php");

?>
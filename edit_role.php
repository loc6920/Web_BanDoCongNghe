<?php

include("db.php");

session_start();

$id=$_POST['user_id'];
$role=$_POST['role'];

$sql="UPDATE users 
SET role='$role' 
WHERE user_id='$id'";

mysqli_query($conn,$sql);

if(isset($_SESSION["users"])){

    if($_SESSION["users"]["user_id"]==$id){

        $_SESSION["users"]["role"]=$role;

    }

}
header("location:admin.php#user_section");

?>
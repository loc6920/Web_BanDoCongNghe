<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hồ sơ cá nhân - 84 STORE</title>
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
if(!isset($_SESSION["users"])){
    header("location:dangnhap.php");
    exit();
}

$user=$_SESSION["users"];
$u_id=$user['user_id'];

/* UPDATE PROFILE */
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name=$_POST['name'];
    $phone=$_POST['phone'];
    $province=$_POST['province'];
    $district=$_POST['district'];
    $ward=$_POST['ward'];
    $detail=$_POST['address'];

    /* UPDATE USER */
    $sql_user="UPDATE users 
    SET name='$name' 
    WHERE user_id='$u_id'";

    mysqli_query($conn,$sql_user);

    /* CHECK ADDRESS */
    $check_sql="SELECT * 
    FROM addresses 
    WHERE user_id='$u_id'";
    $check_result=mysqli_query($conn,$check_sql);

    /* UPDATE */
    if(mysqli_num_rows($check_result)>0){
        $update_sql=" UPDATE addresses SET phone='$phone', province='$province', district='$district', ward='$ward', detail='$detail'
        WHERE user_id='$u_id'";
        mysqli_query($conn,$update_sql);

    }else{
        /* INSERT */
        $insert_sql="INSERT INTO addresses(user_id, phone, province, district, ward, detail ) VALUES ('$u_id','$phone','$province','$ward','$detail')";
        mysqli_query($conn,$insert_sql);
    }
    $_SESSION["users"]["name"]=$name;
    header("location:profile.php");
    exit();
}

/* GET ADDRESS */
$sql_address="SELECT * 
FROM addresses 
WHERE user_id='$u_id'";
$result_address=mysqli_query($conn,$sql_address);
$address=mysqli_fetch_assoc($result_address);

?>
</head>

<body class="body_staff">

    <!-- NAVBAR -->
    <div class="navbar">

        <div class="nav-left">
            <a href="home.php" class="logo">84 STORE</a>
        </div>

        <div class="nav-right">

            <a href="home.php">
                <button class="button-search">Trang chủ</button>
            </a>
        </div>

    </div>

    <!-- MAIN -->
    <main>

        <div class="dashboard">

            <!-- HEADER -->
            <div class="admin-header">

                <div>
                    <h1>Hồ sơ cá nhân</h1>
                    <p style="color:#666; margin-top:8px;">Quản lý thông tin tài khoản và đơn hàng của bạn</p>
                </div>

            </div>

            <!-- TAB -->
            <div class="profile-tabs cards">

                <button class="tab-btn active card" onclick="openTab('profileTab',this)">
                    Thông tin cá nhân
                </button>

                <button class="tab-btn card" onclick="openTab('orderTab',this)">
                    Đơn hàng
                </button>

            </div>

            <!-- PROFILE -->
            <div id="profileTab" class="tab-content" style="display:block;">

                <div class="card" style="margin-top:20px;">

                    <div class="profile-top">

                        <div>
                            <h2>Thông tin cá nhân</h2>
                            <p class="profile-desc">Quản lý thông tin giao hàng và tài khoản</p>
                        </div>

                        <button type="button" class="edit-btn" onclick="toggleEdit()" id="editBtn">
                            Chỉnh sửa
                        </button>

                    </div>

                    <!-- VIEW -->
                    <div id="profileView">

                        <div class="profile-info">

                            <div class="info-item">
                                <span>Họ và tên</span>
                                <strong><?= htmlspecialchars($user['name']) ?></strong>
                            </div>

                            <div class="info-item">
                                <span>Email</span>
                                <strong><?= htmlspecialchars($user['email']) ?></strong>
                            </div>

                            <div class="info-item">
                                <span>Số điện thoại</span>
                                <strong><?= $address['phone'] ?? 'Chưa cập nhật' ?></strong>
                            </div>

                            <div class="info-item">
                                <span>Địa chỉ</span>

                                <strong>
                                    <?= $address['detail'] ?? 'Chưa cập nhật' ?>,
                                    <?= $address['ward'] ?? '' ?>,
                                    <?= $address['district'] ?? '' ?>,
                                    <?= $address['province'] ?? '' ?>
                                </strong>

                            </div>

                        </div>

                    </div>

                    <!-- FORM -->
                    <form action="" method="POST" id="profileForm" style="display:none; margin-top:25px;">

                        <!-- NAME + PHONE -->
                        <div class="profile-grid">

                            <div>
                                <label>Họ và tên</label>

                                <input
                                type="text"
                                name="name"
                                value="<?= htmlspecialchars($user['name']) ?>"
                                class="input-search profile-input">
                            </div>

                            <div>
                                <label>Số điện thoại</label>

                                <input
                                type="text"
                                name="phone"
                                value="<?= $address['phone'] ?? '' ?>"
                                class="input-search profile-input">
                            </div>

                        </div>

                        <!-- PROVINCE + DISTRICT -->
                        <div class="profile-grid" style="margin-top:20px;">

                            <div>

                                <label>Tỉnh / Thành phố</label>

                                <select id="province" name="province" class="input-search profile-input">

                                    <option value="">
                                        Chọn tỉnh thành
                                    </option>

                                </select>

                            </div>

                            <div>

                                <label>Quận / Huyện</label>

                                <select id="district" name="district" class="input-search profile-input">

                                    <option value="">
                                        Chọn quận huyện
                                    </option>

                                </select>

                            </div>

                        </div>

                        <!-- WARD + DETAIL -->
                        <div class="profile-grid" style="margin-top:20px;">

                            <div>

                                <label>Phường / Xã</label>

                                <select id="ward" name="ward" class="input-search profile-input">

                                    <option value="">
                                        Chọn phường xã
                                    </option>

                                </select>

                            </div>

                            <div>

                                <label>Địa chỉ chi tiết</label>

                                <input
                                type="text"
                                name="address"
                                value="<?= $address['detail'] ?? '' ?>"
                                class="input-search profile-input"
                                placeholder="Số nhà, tên đường...">

                            </div>

                        </div>

                        <!-- ACTION -->
                        <div class="profile-action">

                            <button type="button" class="delete-btn" onclick="toggleEdit()">
                                Hủy
                            </button>

                            <button type="submit" class="create_btn">
                                Lưu thông tin
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- ORDER -->
            <div id="orderTab" class="tab-content" style="display:none;">

                <div class="card" style="margin-top:20px;">

                    <h2 style="margin-bottom:20px;">Đơn hàng đã mua</h2>

                    <table>

                        <thead>

                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $sql="SELECT * 
                            FROM orders 
                            WHERE user_id='$u_id' 
                            ORDER BY created_at DESC";

                            $result=mysqli_query($conn,$sql);

                            while($row=mysqli_fetch_assoc($result)){

                            ?>

                            <tr>

                                <td>#<?= $row['order_id'] ?></td>

                                <td><?= $row['created_at'] ?></td>

                                <td><?= number_format($row['total_price']) ?>đ</td>

                                <td>

                                    <?php if($row['status']=="pending"){ ?>
                                        <span style="color:orange;font-weight:600;">Chờ xử lý</span>
                                    <?php } ?>

                                    <?php if($row['status']=="shipping"){ ?>
                                        <span style="color:blue;font-weight:600;">Đang vận chuyển</span>
                                    <?php } ?>

                                    <?php if($row['status']=="completed"){ ?>
                                        <span style="color:green;font-weight:600;">Hoàn thành</span>
                                    <?php } ?>

                                </td>

                                <td>

                                    <a href="order_detail.php?id=<?= $row['order_id'] ?>">
                                        <button class="edit-btn">Xem chi tiết</button>
                                    </a>

                                </td>

                            </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

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

    <script>
    function toggleEdit(){

        const form=document.getElementById("profileForm");
        const view=document.getElementById("profileView");
        const btn=document.getElementById("editBtn");

        if(form.style.display==="none"){

            form.style.display="block";
            view.style.display="none";
            btn.innerText="Đang chỉnh sửa";

        }else{

            form.style.display="none";
            view.style.display="block";
            btn.innerText="Chỉnh sửa";

        }

    }

    function openTab(tabId,button){

        document.querySelectorAll(".tab-content").forEach(tab=>{
            tab.style.display="none";
        });

        document.querySelectorAll(".tab-btn").forEach(btn=>{
            btn.style.background="#e5e7eb";
            btn.style.color="#111";
        });

        document.getElementById(tabId).style.display="block";

        button.style.background="#111827";
        button.style.color="#fff";

    }

    const province=document.getElementById("province");
    const district=document.getElementById("district");
    const ward=document.getElementById("ward");

    /* LOAD TỈNH */
    fetch("https://provinces.open-api.vn/api/p/")
    .then(res=>res.json())
    .then(data=>{

        data.forEach(item=>{

            province.innerHTML+=`
            <option value="${item.name}" data-code="${item.code}">
                ${item.name}
            </option>
            `;

        });

    });

    /* LOAD HUYỆN */
    province.addEventListener("change",function(){

        district.innerHTML=`<option value="">Chọn quận huyện</option>`;
        ward.innerHTML=`<option value="">Chọn phường xã</option>`;

        const provinceCode=this.options[this.selectedIndex].dataset.code;

        fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
        .then(res=>res.json())
        .then(data=>{

            data.districts.forEach(item=>{

                district.innerHTML+=`
                <option value="${item.name}" data-code="${item.code}">
                    ${item.name}
                </option>
                `;

            });

        });

    });

    /* LOAD XÃ */
    district.addEventListener("change",function(){

        ward.innerHTML=`<option value="">Chọn phường xã</option>`;

        const districtCode=this.options[this.selectedIndex].dataset.code;

        fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
        .then(res=>res.json())
        .then(data=>{

            data.wards.forEach(item=>{

                ward.innerHTML+=`
                <option value="${item.name}">
                    ${item.name}
                </option>
                `;

            });

        });

    });

    </script>

</body>

</html>
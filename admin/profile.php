<?php
    include("connection.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['admin_session']))
    {
        header("Location: login.php");
        exit();
    }

    $id = intval($_SESSION['admin_session']);
    $query = "SELECT * FROM tbl_admin WHERE id=$id";
    $result = mysqli_query($connection,$query);
    $row = mysqli_fetch_assoc($result);

    if(!$row)
    {
        session_destroy();
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Vaccination System</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<style>
    .mainContent
    {
        padding: 30px;
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 30px;
    }
    .mainContent .title
    {
        font-size: 28px;
        margin-bottom: 20px;
    }
    .mainContent .leftSide
    {
        flex: 1.2;
        min-width: 320px;
    }
    .mainContent .leftSide input
    {
        padding: 12px 14px;
        border: 1px solid #ccc;
        outline: none;
        background-color: #f9f9f9;
        border-radius: 6px;
        margin-bottom: 15px;
        width: 100%;
        box-sizing: border-box;
    }
    .mainContent .leftSide input[type="submit"]
    {
        background-color: #178066;
        color: #fff;
        font-size: 16px;
        border: none;
        cursor: pointer;
        padding: 14px;
    }
    .mainContent .rightSide
    {
        flex: 1;
        min-width: 300px;
        text-align: center;
    }
    .mainContent .rightSide .image
    {
        width: 260px;
        height: 260px;
        margin: 0 auto 15px auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        background-color: #eee;
    }
    .mainContent .rightSide .image img
    {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .mainContent .rightSide input[type="submit"]
    {
        background-color: #178066;
        padding: 12px 20px;
        color: #fff;
        border-radius: 6px;
        margin-top: 10px;
        border: none;
        cursor: pointer;
        width: 100%;
    }
</style>
<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <?php
            include("navigation.php");
        ?>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="user">
                    <a href='profile.php'><img src="assets/imgs/customer01.jpg" alt="Profile"></a>
                </div>
            </div>
            <!-- ======================= Profile ================== -->
            <div class="mainContent">
                <div class="leftSide">
                    <h2 class="title">Admin Profile</h2>
                    <form method="post" autocomplete="off">
                        <label>Admin Name</label>
                        <input type="text" placeholder="Enter Full Name" name="name" required value="<?php echo htmlspecialchars($row['name']);?>">
                        
                        <label>Admin Email</label>
                        <input type="email" placeholder="Enter Email Address" required name="email" value="<?php echo htmlspecialchars($row['email']);?>">
                        
                        <label>Password (leave blank to keep current)</label>
                        <input type="password" placeholder="New Password" name="password">
                        
                        <input type="submit" value="Save Profile" name="btnupdate">
                    </form>
                    <?php
                        if(isset($_POST['btnupdate']))
                        {
                            $name = mysqli_real_escape_string($connection, trim($_POST['name']));
                            $email = mysqli_real_escape_string($connection, trim($_POST['email']));

                            if(!empty($_POST['password']))
                            {
                                $new_pass = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
                                $pass_clause = ", password='$new_pass'";
                            }
                            else
                            {
                                $pass_clause = "";
                            }

                            $query = "UPDATE tbl_admin SET name='$name', email='$email' $pass_clause WHERE id=$id";
                            $result = mysqli_query($connection,$query);
                            if($result)
                            {
                                echo 
                                "<script>
                                    alert('Admin profile updated successfully.');
                                    window.location.href='profile.php';
                                </script>";
                                exit();
                            }
                        }
                    ?>
                </div>
                <div class="rightSide">
                    <h2 class="title">Avatar Photo</h2>
                    <div class="image">
                        <?php
                            $adminImg = "../uploads/admin/user.jpg";
                            if(!empty($row['image']))
                            {
                                $cleanName = basename($row['image']);
                                if(file_exists(dirname(__DIR__) . "/uploads/admin/" . $cleanName)) {
                                    $adminImg = "../uploads/admin/" . $cleanName;
                                } elseif(file_exists(dirname(__DIR__) . "/" . $row['image'])) {
                                    $adminImg = "../" . $row['image'];
                                }
                            }
                        ?>
                        <img src="<?php echo htmlspecialchars($adminImg); ?>" alt="Admin Avatar">
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="image" accept="image/*" required><br>
                        <input type="submit" value="Upload Image" name="btnupload">
                    </form>
                    <?php
                        if(isset($_POST['btnupload']))
                        {
                            if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK)
                            {
                                $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES['image']['name']));
                                $tmpName = $_FILES['image']['tmp_name'];
                                $targetDir = dirname(__DIR__) . "/uploads/admin/";
                                if(!is_dir($targetDir))
                                {
                                    mkdir($targetDir, 0777, true);
                                }
                                $destPath = $targetDir . $imageName;
                                $dbPath = "uploads/admin/" . $imageName;
                                if(move_uploaded_file($tmpName, $destPath))
                                {
                                    $query = "UPDATE tbl_admin SET image='$dbPath' WHERE id=$id";
                                    $result = mysqli_query($connection,$query);
                                    if($result)
                                    {
                                        echo 
                                        "<script>
                                            alert('Profile avatar updated successfully.');
                                            window.location.href='profile.php';
                                        </script>";
                                        exit();
                                    }
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>

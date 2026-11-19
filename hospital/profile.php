<?php
    include("connection.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['hospital_session']))
    {
        header("Location: login.php");
        exit();
    }

    $hid = intval($_SESSION['hospital_session']);
    $query = "SELECT * FROM tbl_hospital WHERE id=$hid";
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
    <title>Hospital Profile - Vaccination System</title>
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
    .mainContent .leftSide input,
    .mainContent .leftSide select
    {
        padding: 12px 14px;
        border: 1px solid #ccc;
        outline: none;
        background-color: #f9f9f9;
        border-radius: 6px;
        margin-bottom: 12px;
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
        width: 280px;
        height: 280px;
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
            <!-- ======================= Profile Details ================== -->
            <div class="mainContent">
                <div class="leftSide">
                    <h2 class="title">Hospital Profile</h2>
                    <form method="post">
                        <label>Hospital Name</label>
                        <input type="text" placeholder="Enter Hospital Name" name="name" required value="<?php echo htmlspecialchars($row['name']);?>">
                        
                        <label>Email Address</label>
                        <input type="email" placeholder="Enter Email" required name="email" value="<?php echo htmlspecialchars($row['email']);?>">
                        
                        <label>Password</label>
                        <input type="password" placeholder="Leave empty to keep existing password" name="password">
                        
                        <label>Contact Number</label>
                        <input type="text" placeholder="Enter Contact Number" required name="contact" value="<?php echo htmlspecialchars($row['contact']);?>">
                        
                        <label>City</label>
                        <select name="city" required>
                            <option value="" disabled>Select City</option>
                            <?php 
                                $query = "SELECT * FROM tbl_city";
                                $result = mysqli_query($connection,$query);
                                if($result)
                                {
                                    foreach ($result as $row1) {
                                        $selected = ($row['cid'] == $row1['id']) ? " selected" : "";
                                        echo "<option value='".$row1['id']."'$selected>".htmlspecialchars($row1['name'])."</option>";
                                    }
                                }
                            ?>
                        </select>
                        
                        <label>Address</label>
                        <input type="text" placeholder="Enter Address" required name="address" value="<?php echo htmlspecialchars($row['address']);?>">
                        
                        <input type="submit" value="Update Profile" name="btnupdate">
                    </form>
                    <?php
                        if(isset($_POST['btnupdate']))
                        {
                            $name = mysqli_real_escape_string($connection, trim($_POST['name']));
                            $email = mysqli_real_escape_string($connection, trim($_POST['email']));
                            $city = intval($_POST['city']);
                            $contact = mysqli_real_escape_string($connection, trim($_POST['contact']));
                            $address = mysqli_real_escape_string($connection, trim($_POST['address']));

                            if(!empty($_POST['password']))
                            {
                                $new_pass = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
                                $pass_clause = ", password='$new_pass'";
                            }
                            else
                            {
                                $pass_clause = "";
                            }

                            $query = "UPDATE tbl_hospital SET name='$name', email='$email', cid=$city, address='$address', contact='$contact' $pass_clause WHERE id=$hid";
                            $result = mysqli_query($connection,$query);
                            if($result)
                            {
                                echo 
                                "<script>
                                    alert('Hospital profile updated successfully.');
                                    window.location.href='profile.php';
                                </script>";
                                exit();
                            }
                        }
                    ?>
                </div>
                <div class="rightSide">
                    <h2 class="title">Facility Image</h2>
                    <div class="image">
                        <?php
                            $hospImg = "../uploads/hospitals/abc.webp";
                            if(!empty($row['image']))
                            {
                                $cleanName = basename($row['image']);
                                if(file_exists(dirname(__DIR__) . "/uploads/hospitals/" . $cleanName)) {
                                    $hospImg = "../uploads/hospitals/" . $cleanName;
                                } elseif(file_exists(dirname(__DIR__) . "/" . $row['image'])) {
                                    $hospImg = "../" . $row['image'];
                                } elseif(file_exists($row['image'])) {
                                    $hospImg = $row['image'];
                                }
                            }
                        ?>
                        <img src="<?php echo htmlspecialchars($hospImg); ?>" alt="Hospital Image">
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
                                $targetDir = dirname(__DIR__) . "/uploads/hospitals/";
                                if(!is_dir($targetDir))
                                {
                                    mkdir($targetDir, 0777, true);
                                }
                                $destPath = $targetDir . $imageName;
                                $dbPath = "uploads/hospitals/" . $imageName;
                                if(move_uploaded_file($tmpName, $destPath))
                                {
                                    $query = "UPDATE tbl_hospital SET image='$dbPath' WHERE id=$hid";
                                    $result = mysqli_query($connection,$query);
                                    if($result)
                                    {
                                        echo 
                                        "<script>
                                            alert('Hospital picture updated successfully.');
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

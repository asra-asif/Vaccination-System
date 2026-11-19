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

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $query = "SELECT * FROM tbl_patient WHERE id=$id";
    $result = mysqli_query($connection,$query);
    $row = mysqli_fetch_assoc($result);

    if(!$row)
    {
        header("Location: patient.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient - Vaccination System</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<style>
    .mainContent
    {
        padding: 30px;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 30px;
    }
    .mainContent .leftSide
    {
        flex: 1.2;
        min-width: 320px;
    }
    .mainContent form input,
    .mainContent form select
    {
        width: 100%;
        border: 1px solid #ccc;
        outline: none;
        padding: 12px 16px;
        border-radius: 6px;
        background-color: #fafafa;
        margin: 10px 0px;
        font-size: 15px;
        box-sizing: border-box;
    }
    .mainContent form input[type="submit"]
    {
        background-color: #178066;
        color: #fff;
        border: none;
        cursor: pointer;
        padding: 14px;
        font-weight: 500;
    }
    .mainContent form input[type="submit"]:hover
    {
        background-color: #12634f;
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
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        margin: 20px auto;
        background-color: #eee;
    }
    .mainContent .rightSide .image img
    {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
            <!-- ======================= Edit Section ================== -->
            <div class="mainContent">
                <div class="leftSide">
                <h2>Edit Patient Information</h2>
                <form method="post" autocomplete="off">
                    <label>Full Name</label>
                    <input type="text" placeholder="Enter Patient Name" name="name" value="<?php echo htmlspecialchars($row['name']);?>" required><br>
                    <label>Phone Number</label>
                    <input type="text" placeholder="Enter Patient Number" name="phone" value="<?php echo htmlspecialchars($row['contact']);?>"><br>
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
                    </select><br>
                    <label>Email Address</label>
                    <input type="email" placeholder="Enter Patient Email" name="email" value="<?php echo htmlspecialchars($row['email']);?>" required><br>
                    <label>Password (leave blank to keep current)</label>
                    <input type="password" placeholder="New Password" name="password"><br>
                    <label>Address</label>
                    <input type="text" placeholder="Enter Patient Address" name="address" value="<?php echo htmlspecialchars($row['address']);?>"><br>
                    <label>Gender</label>
                    <select name="gender">
                        <option value="" disabled <?php echo empty($row['gender']) ? 'selected' : ''; ?>>Select Gender</option>
                        <option value="male" <?php echo (strtolower($row['gender']) === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo (strtolower($row['gender']) === 'female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                    <input type="submit" value="Update Patient" name="btnupdate">
                </form>
                <?php
                    if(isset($_POST['btnupdate']))
                    {
                        $name = mysqli_real_escape_string($connection, trim($_POST['name']));
                        $phone = mysqli_real_escape_string($connection, trim($_POST['phone']));
                        $city = intval($_POST['city']);
                        $email = mysqli_real_escape_string($connection, trim($_POST['email']));
                        $address = mysqli_real_escape_string($connection, trim($_POST['address']));
                        $gender = mysqli_real_escape_string($connection, trim($_POST['gender'] ?? ''));

                        if(!empty($_POST['password']))
                        {
                            $new_pass = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
                            $pass_clause = ", password='$new_pass'";
                        }
                        else
                        {
                            $pass_clause = "";
                        }

                        $query = "UPDATE tbl_patient SET name='$name', contact='$phone', cid=$city, email='$email', address='$address', gender='$gender' $pass_clause WHERE id=$id";
                        $result = mysqli_query($connection,$query);
                        if($result)
                        {
                            echo 
                            "<script>
                                alert('Patient updated successfully.');
                                window.location.href='patient.php';
                            </script>";
                            exit();
                        }
                    }
                ?>
                </div>
                <div class="rightSide">
                    <h2>Patient Photo</h2>
                    <div class="image">
                        <?php
                            $patImg = "../uploads/patients/user.jpg";
                            if(!empty($row['image']))
                            {
                                $cleanName = basename($row['image']);
                                if(file_exists(dirname(__DIR__) . "/uploads/patients/" . $cleanName)) {
                                    $patImg = "../uploads/patients/" . $cleanName;
                                } elseif(file_exists(dirname(__DIR__) . "/" . $row['image'])) {
                                    $patImg = "../" . $row['image'];
                                }
                            }
                        ?>
                        <img src="<?php echo htmlspecialchars($patImg);?>" alt="Patient Photo">
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
                                $targetDir = dirname(__DIR__) . "/uploads/patients/";
                                if(!is_dir($targetDir))
                                {
                                    mkdir($targetDir, 0777, true);
                                }
                                $destPath = $targetDir . $imageName;
                                $dbPath = "uploads/patients/" . $imageName;
                                if(move_uploaded_file($tmpName, $destPath))
                                {
                                    $query = "UPDATE tbl_patient SET image='$dbPath' WHERE id=$id";
                                    $result = mysqli_query($connection,$query);
                                    if($result)
                                    {
                                        echo 
                                        "<script>
                                            alert('Image updated successfully.');
                                            window.location.href='edit_patient.php?id=$id';
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

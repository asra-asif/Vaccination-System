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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Patient - Vaccination System</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<style>
    .mainContent
    {
        padding: 30px 80px;
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
            <!-- ======================= Form ================== -->
            <div class="mainContent">
                <h2>Register New Patient</h2>
                <form method="post" enctype="multipart/form-data" autocomplete="off">
                    <input type="text" placeholder="Patient Full Name" name="name" required><br>
                    <input type="text" placeholder="Contact Phone Number" name="phone"><br>
                    <select name="city" required>
                        <option value="" disabled selected>Select City</option>
                        <?php 
                            $query = "SELECT * FROM tbl_city";
                            $result = mysqli_query($connection,$query);
                            if($result)
                            {
                                foreach($result as $row)
                                {
                                    echo "<option value='$row[id]'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                        ?>
                    </select><br>
                    <input type="email" placeholder="Email Address" name="email" required><br>
                    <input type="password" placeholder="Password" name="password" required minlength="4"><br>
                    <input type="text" placeholder="Residential Address" name="address"><br>
                    <select name="gender">
                        <option value="" disabled selected>Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select><br>
                    <select name="status" required>
                        <option value="activate" selected>Activate</option>
                        <option value="deactivate">Deactivate</option>
                    </select><br>
                    <label style="font-size:14px; color:#555;">Patient Photo (optional)</label>
                    <input type="file" name="image" accept="image/*"><br>
                    <input type="submit" value="Add Patient" name="btnadd">
                </form>
                <?php
                    if(isset($_POST['btnadd']))
                    {
                        $name = mysqli_real_escape_string($connection, trim($_POST['name']));
                        $phone = mysqli_real_escape_string($connection, trim($_POST['phone']));
                        $city = intval($_POST['city']);
                        $email = mysqli_real_escape_string($connection, trim($_POST['email']));
                        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
                        $address = mysqli_real_escape_string($connection, trim($_POST['address']));
                        $gender = mysqli_real_escape_string($connection, trim($_POST['gender'] ?? ''));
                        $status = mysqli_real_escape_string($connection, trim($_POST['status']));

                        $check_email = mysqli_query($connection, "SELECT id FROM tbl_patient WHERE email='$email'");
                        if(mysqli_num_rows($check_email) > 0)
                        {
                            echo "<script>alert('A patient with this email already exists.');</script>";
                        }
                        else
                        {
                            $path = "uploads/patients/user.jpg";
                            if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK)
                            {
                                $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES['image']['name']));
                                $tmpName = $_FILES['image']['tmp_name'];
                                $targetDir = dirname(__DIR__) . "/uploads/patients/";
                                if(!is_dir($targetDir))
                                {
                                    mkdir($targetDir, 0777, true);
                                }
                                move_uploaded_file($tmpName, $targetDir . $imageName);
                                $path = "uploads/patients/" . $imageName;
                            }

                            $query = "INSERT INTO tbl_patient(name, contact, cid, email, password, address, gender, status, image) VALUES('$name', '$phone', $city, '$email', '$password', '$address', '$gender', '$status', '$path')";
                            $result = mysqli_query($connection,$query);
                            if($result)
                            {
                                echo 
                                "<script>
                                    alert('New patient added successfully.');
                                    window.location.href='patient.php';
                                </script>";
                                exit();
                            }
                        }
                    }
                ?>
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

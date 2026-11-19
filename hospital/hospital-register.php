<?php
    include("connection.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Registration - Vaccination System</title>
     <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" />
     <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
     <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
     <link href="assets/css/style.css" rel="stylesheet" />
     <link href="assets/css/responsive.css" rel="stylesheet" />
     <link rel="stylesheet" href="assets/css/formstyling.css">
</head>
<style>
    body
    {
        width: 100%;
        min-height: 100vh;
        background: url("assets/imgs/hero-bg.png");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .text
    {
        padding: 20px 0px;
        text-align: left;
        display: flex;
        justify-content: flex-start;
    }
</style>
<body>
    <section class="main">
        <div class="form">
            <h2>Hospital Registration</h2>
            <form method="post" autocomplete="off">
                <input type="text" placeholder="Enter Hospital Name" name="name" required><br><br>
                <input type="email" placeholder="Enter Official Email Address" name="email" required><br><br>
                <input type="password" placeholder="Create Password" name="password" required minlength="4"><br><br>
                <input type="submit" value="Register Hospital" name="btnregister">
            </form>
            <div class="text">
                <a href="login.php">Already registered? Log in</a>
            </div>
            <div class="text" style="padding-top: 0;">
                <a href="../index.php">Back to Main Website</a>
            </div>
            <?php
                if(isset($_POST['btnregister']))
                {
                    $name = mysqli_real_escape_string($connection, trim($_POST['name']));
                    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
                    $password = trim($_POST['password']);

                    $check_email = mysqli_query($connection, "SELECT id FROM tbl_hospital WHERE email='$email'");
                    if(mysqli_num_rows($check_email) > 0)
                    {
                        echo 
                        "<script>
                            alert('This email is already registered. Please log in.');
                            window.location.href='login.php';
                        </script>";
                    }
                    else
                    {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $default_img = "uploads/hospitals/abc.webp";
                        $query = "INSERT INTO tbl_hospital(name, contact, cid, email, password, image, address, status) VALUES('$name', '', 1, '$email', '$hashed_password', '$default_img', '', 'deactivate')";
                        $result = mysqli_query($connection, $query);
                        if($result)
                        {
                            echo 
                            "<script>
                                alert('Registration successful. Your account is pending administrator activation.');
                                window.location.href='login.php';
                            </script>";
                            exit();
                        }
                        else
                        {
                            echo 
                            "<script>
                                alert('Registration failed. Please try again.');
                            </script>";
                        }
                    }
                }
            ?>
        </div>
    </section>
</body>
</html>

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
    <title>Admin Login - Vaccination System</title>
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
            <h2>Admin Login</h2>
            <form method="post" autocomplete="off">
                <input type="email" placeholder="Enter Admin Email" name="email" required><br><br>
                <input type="password" placeholder="Enter Password" name="password" required><br><br>
                <input type="submit" value="Login" name="btnlogin">
            </form>
            <div class="text">
                <a href="../index.php">Back to Main Website</a>
            </div>
            <?php
                if(isset($_POST['btnlogin']))
                {
                    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
                    $password = trim($_POST['password']);
                    $query = "SELECT * FROM tbl_admin WHERE email='$email'";
                    $result = mysqli_query($connection,$query);
                    if($result && mysqli_num_rows($result) > 0)
                    {
                        $row = mysqli_fetch_assoc($result);
                        if(password_verify($password, $row['password']) || $password === $row['password'])
                        {
                            if($password === $row['password'] && !password_verify($password, $row['password']))
                            {
                                $newHash = password_hash($password, PASSWORD_DEFAULT);
                                $updateQ = "UPDATE tbl_admin SET password='" . mysqli_real_escape_string($connection, $newHash) . "' WHERE id=" . $row['id'];
                                mysqli_query($connection, $updateQ);
                            }
                            $_SESSION['admin_session'] = $row['id'];
                            echo 
                            "<script>
                                alert('Login Successful');
                                window.location.href='index.php';
                            </script>";
                            exit();
                        }
                        else
                        {
                            echo 
                            "<script>
                                alert('Incorrect Email or Password');
                            </script>";
                        }
                    }
                    else
                    {
                        echo 
                        "<script>
                            alert('Incorrect Email or Password');
                        </script>";
                    }
                }
            ?>
        </div>
    </section>
</body>
</html>

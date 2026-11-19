<?php
  require_once __DIR__ . '/includes/db.php';
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  if(!isset($_SESSION['patient_session']))
  {
      header("Location: login.php");
      exit();
  }

  $pid = intval($_SESSION['patient_session']);
  $query = "SELECT * FROM tbl_patient WHERE id=$pid";
  $result = mysqli_query($connection,$query);
  $patient = mysqli_fetch_assoc($result);

  if(!$patient)
  {
      session_destroy();
      header("Location: login.php");
      exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">

  <title>Patient Profile - Vaccination System</title>

  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/responsive.css" rel="stylesheet" />
</head>
<style>
  header
  {
    background-color: #178066;
  }
  .mainContent
  {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 50px 15px;
    flex-wrap: wrap;
    gap: 40px;
  }
  .mainContent .leftSide
  {
    flex: 1.2;
    min-width: 320px;
  }
  .mainContent .leftSide input,
  .mainContent .leftSide select
  {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    outline: none;
    background-color: #f9f9f9;
    border-radius: 6px;
    margin: 10px 0px;
    box-sizing: border-box;
  }
  .mainContent .leftSide input[type="submit"]
  {
    background-color: #178066;
    color: #fff;
    font-size: 18px;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
  }
  .mainContent .leftSide input[type="submit"]:hover
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
    margin: 0 auto 20px auto;
    height: 280px;
    width: 280px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #ddd;
    background-color: #eee;
  }
  .mainContent .rightSide .image img
  {
    object-fit: cover;
    width: 100%;
    height: 100%;
  }
  .mainContent .rightSide input[type="file"]
  {
    margin: 10px 0px;
    width: 100%;
  }
  .mainContent .rightSide input[type="submit"]
  {
    width: 100%;
    padding: 12px;
    border: none;
    outline: none;
    border-radius: 6px;
    margin: 10px 0px;
    background-color: #178066;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
  }
  .mainContent .rightSide input[type="submit"]:hover
  {
    background-color: #12634f;
  }
</style>
<body>
    <?php
        include("includes/header.php");
    ?>
    <div class="container mainContent">
      <div class="leftSide">
        <h2>My Profile Details</h2>
        <form method="post">
          <label>Full Name</label>
          <input type="text" placeholder="Enter Full Name" name="name" value="<?php echo htmlspecialchars($patient['name']);?>" required><br>
          <label>Contact Number</label>
          <input type="text" placeholder="Enter Phone Number" name="phone" value="<?php echo htmlspecialchars($patient['contact']);?>"><br>
          <label>Email Address</label>
          <input type="email" placeholder="Enter Email Address" name="email" value="<?php echo htmlspecialchars($patient['email']);?>" required><br>
          <label>Password</label>
          <input type="password" placeholder="Leave empty to keep existing password" name="password"><br>
          <label>City</label>
          <select name="city" required>
            <option value="" disabled>Select City</option>
            <?php 
                $query = "SELECT * FROM tbl_city";
                $result = mysqli_query($connection,$query);
                if($result)
                {
                  foreach ($result as $row1) {
                      $selected = ($patient['cid'] == $row1['id']) ? " selected" : "";
                      echo "<option value='".$row1['id']."'$selected>".htmlspecialchars($row1['name'])."</option>";
                  }
                }
            ?>
          </select><br>
          <label>Gender</label>
          <select name="gender">
            <option value="" disabled <?php echo empty($patient['gender']) ? 'selected' : ''; ?>>Select Gender</option>
            <option value="male" <?php echo (strtolower($patient['gender']) === 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo (strtolower($patient['gender']) === 'female') ? 'selected' : ''; ?>>Female</option>
          </select><br>
          <label>Residential Address</label>
          <input type="text" placeholder="Enter Residential Address" name="address" value="<?php echo htmlspecialchars($patient['address']);?>"><br>
          <input type="submit" value="Save Profile Changes" name="btnupdate">
        </form>
        <?php
          if(isset($_POST['btnupdate']))
          {
            $name = mysqli_real_escape_string($connection, trim($_POST['name']));
            $phone = mysqli_real_escape_string($connection, trim($_POST['phone']));
            $email = mysqli_real_escape_string($connection, trim($_POST['email']));
            $city = intval($_POST['city']);
            $gender = mysqli_real_escape_string($connection, trim($_POST['gender'] ?? ''));
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

            $query = "UPDATE tbl_patient SET name='$name', contact='$phone', email='$email', cid=$city, gender='$gender', address='$address' $pass_clause WHERE id=$pid";
            $result = mysqli_query($connection,$query);
            if($result)
            {
              $_SESSION['patient_name'] = $name;
              echo 
              "<script>
                  alert('Profile updated successfully.');
                  window.location.href='profile.php';
              </script>";
              exit();
            }
          }
        ?>
      </div>
      <div class="rightSide">
        <h2>Profile Photo</h2>
        <div class="image">
          <?php
            $photo = "uploads/patients/user.jpg";
            if(!empty($patient['image']))
            {
              $cleanName = basename($patient['image']);
              if(file_exists(__DIR__ . "/uploads/patients/" . $cleanName)) {
                $photo = "uploads/patients/" . $cleanName;
              } elseif(file_exists(__DIR__ . "/" . $patient['image'])) {
                $photo = $patient['image'];
              }
            }
          ?>
          <img src="<?php echo htmlspecialchars($photo);?>" alt="Profile Picture">
        </div>
        <form method="post" enctype="multipart/form-data">
          <input type="file" name="image" accept="image/*" required><br>
          <input type="submit" value="Upload New Photo" name="btnupload">
        </form>
        <?php
          if(isset($_POST['btnupload']))
          {
            if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK)
            {
              $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES['image']['name']));
              $tmpName = $_FILES['image']['tmp_name'];
              $targetDir = __DIR__ . "/uploads/patients/";
              if(!is_dir($targetDir))
              {
                mkdir($targetDir, 0777, true);
              }
              $destPath = $targetDir . $imageName;
              $dbPath = "uploads/patients/" . $imageName;
              if(move_uploaded_file($tmpName, $destPath))
              {
                $query = "UPDATE tbl_patient SET image='$dbPath' WHERE id=$pid";
                $result = mysqli_query($connection,$query);
                if($result)
                {
                  echo 
                  "<script>
                      alert('Profile photo updated successfully.');
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
</body>
</html>


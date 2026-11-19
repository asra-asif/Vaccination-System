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
    $test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Verify this test belongs to the logged-in hospital
    $query = "SELECT tbl_test.*, tbl_patient.name as 'pname' FROM tbl_test LEFT JOIN tbl_patient ON tbl_test.p_id = tbl_patient.id WHERE tbl_test.id = $test_id AND tbl_test.h_id = $hid";
    $result = mysqli_query($connection, $query);

    if(!$result || mysqli_num_rows($result) === 0)
    {
        echo "<script>alert('Test record not found.'); window.location.href='covid-test.php';</script>";
        exit();
    }

    $testRow = mysqli_fetch_assoc($result);
    $patient_name = $testRow['pname'] ?? 'Patient';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Covid Test Report - Vaccination System</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<style>
    body
    {
        background-color: #f4f6f8;
    }
    .mainContent
    {
        padding: 40px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        min-height: 80vh;
    }
    .reportFormCard
    {
        background: #fff;
        padding: 35px 40px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        width: 100%;
        max-width: 480px;
    }
    .reportFormCard h2
    {
        font-size: 24px;
        color: #178066;
        margin-top: 0;
        margin-bottom: 25px;
        text-align: center;
    }
    .reportFormCard form input,
    .reportFormCard form select
    {
        padding: 12px 14px;
        border: 1px solid #ccc;
        outline: none;
        background-color: #fafafa;
        border-radius: 6px;
        margin-bottom: 18px;
        width: 100%;
        box-sizing: border-box;
        font-size: 15px;
    }
    .reportFormCard input[type="submit"]
    {
        background-color: #178066;
        color: #fff;
        font-size: 16px;
        border: none;
        cursor: pointer;
        padding: 14px;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    .reportFormCard input[type="submit"]:hover
    {
        background-color: #12634f;
    }
    .reportFormCard a
    {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #666;
        text-decoration: none;
        font-size: 14px;
    }
    .reportFormCard a:hover
    {
        text-decoration: underline;
    }
</style>
<body>
    <div class="mainContent">
        <div class="reportFormCard">
            <h2>Record Covid Test Result</h2>
            <form method="post">
                <label style="font-weight: 500; margin-bottom: 5px; display: block;">Patient Name</label>
                <input type="text" readonly name="pname" value="<?php echo htmlspecialchars($patient_name);?>">
                
                <label style="font-weight: 500; margin-bottom: 5px; display: block;">Date of Test</label>
                <input type="date" required name="date" value="<?php echo !empty($testRow['date']) ? htmlspecialchars($testRow['date']) : date('Y-m-d'); ?>">
                
                <label style="font-weight: 500; margin-bottom: 5px; display: block;">Diagnostic Result</label>
                <select name="status" required>
                    <option value="" disabled>Select Result Status</option>
                    <option value="Negative" <?php echo ($testRow['result'] === 'Negative') ? 'selected' : ''; ?>>Negative</option>
                    <option value="Positive" <?php echo ($testRow['result'] === 'Positive') ? 'selected' : ''; ?>>Positive</option>
                    <option value="Process" <?php echo ($testRow['result'] === 'Process') ? 'selected' : ''; ?>>Under Process</option>
                </select>
                
                <input type="submit" value="Save Test Result" name="btncreate">
                <a href="covid-test.php">Return to Tests List</a>
            </form>
            <?php
                if(isset($_POST['btncreate']))
                {
                    $date = mysqli_real_escape_string($connection, $_POST['date']);
                    $status = mysqli_real_escape_string($connection, $_POST['status']);
                    $query = "UPDATE tbl_test SET date='$date', result='$status' WHERE id=$test_id AND h_id=$hid";
                    $result = mysqli_query($connection,$query);
                    if($result)
                    {
                        echo 
                        "<script>
                            alert('Test result updated successfully.');
                            window.location.href='covid-test.php';
                        </script>";
                        exit();
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>

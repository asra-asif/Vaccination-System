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
  if(!isset($_GET['id']))
  {
      header("Location: covid-test.php");
      exit();
  }

  $test_id = intval($_GET['id']);
  $pid = intval($_SESSION['patient_session']);
  $query = "SELECT tbl_patient.name as 'pname', tbl_hospital.name as 'hname', tbl_test.* FROM tbl_test LEFT JOIN tbl_hospital ON tbl_test.h_id = tbl_hospital.id LEFT JOIN tbl_patient ON tbl_test.p_id = tbl_patient.id WHERE tbl_test.id = $test_id AND tbl_test.p_id = $pid";
  $result = mysqli_query($connection,$query);

  if(!$result || mysqli_num_rows($result) === 0)
  {
      echo "<script>alert('Test report not found.'); window.location.href='covid-test.php';</script>";
      exit();
  }

  $test_details = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COVID Test Report - <?php echo htmlspecialchars($test_details['pname']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<style>
    * {
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }
    body
    {
        min-height: 100vh;
        margin: 0;
        background-color: #f4f6f8;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    .reportCard
    {
        width: 100%;
        max-width: 600px;
        background: #fff;
        border: 2px solid #178066;
        border-radius: 8px;
        padding: 30px 40px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .reportCard h2
    {
        text-align: center;
        color: #178066;
        font-size: 26px;
        margin-top: 0;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }
    .info-row
    {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #e5e5e5;
    }
    .info-label
    {
        font-weight: 500;
        color: #555;
    }
    .info-value
    {
        font-weight: 700;
        color: #222;
    }
    .status-negative
    {
        color: #28a745;
    }
    .status-positive
    {
        color: #dc3545;
    }
    .actions
    {
        margin-top: 25px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    .btn
    {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 15px;
        text-decoration: none;
    }
    .btn-print
    {
        background-color: #178066;
        color: #fff;
    }
    .btn-back
    {
        background-color: #6c757d;
        color: #fff;
    }
    @media print
    {
        body
        {
            background: #fff;
            padding: 0;
            justify-content: flex-start;
        }
        .reportCard
        {
            border: 2px solid #000;
            box-shadow: none;
            max-width: 100%;
        }
        .actions
        {
            display: none;
        }
    }
</style>
<body>
    <div class="reportCard">
        <h2>COVID-19 Laboratory Test Report</h2>
        <div class="info-row">
            <span class="info-label">Report ID</span>
            <span class="info-value">#<?php echo $test_details['id']; ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Patient Name</span>
            <span class="info-value"><?php echo htmlspecialchars($test_details['pname']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Testing Facility</span>
            <span class="info-value"><?php echo htmlspecialchars($test_details['hname'] ?? 'N/A'); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Date of Test</span>
            <span class="info-value"><?php echo !empty($test_details['date']) ? htmlspecialchars($test_details['date']) : 'Pending'; ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Diagnostic Result</span>
            <?php
                $resClass = 'info-value';
                if(strtolower($test_details['result']) === 'negative') { $resClass .= ' status-negative'; }
                elseif(strtolower($test_details['result']) === 'positive') { $resClass .= ' status-positive'; }
            ?>
            <span class="<?php echo $resClass; ?>"><?php echo htmlspecialchars(strtoupper($test_details['result'])); ?></span>
        </div>

        <div class="actions">
            <button onclick="window.print()" class="btn btn-print">Print Report</button>
            <a href="covid-test.php" class="btn btn-back">Back to Tests</a>
        </div>
    </div>
</body>
</html>


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

  <title>Covid Test - Vaccination System</title>

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
    padding: 50px 15px;
    flex-wrap: wrap;
    gap: 30px;
  }
  .mainContent .leftSide
  {
    flex: 1;
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
    flex: 1.2;
    min-width: 340px;
  }
  .mainContent .rightSide table
  {
    width: 100%;
    margin-top: 17px;
    text-align: center;
    border-collapse: collapse;
  }
  .mainContent .rightSide table, th, td
  {
    border: 1px solid #ddd;
    padding: 10px;
  }
  .mainContent .rightSide table thead
  {
    background-color: #178066;
    color: #fff;
  }
  .badge-status
  {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 500;
  }
  .badge-process { background-color: #fff3cd; color: #856404; }
  .badge-negative { background-color: #d4edda; color: #155724; }
  .badge-positive { background-color: #f8d7da; color: #721c24; }
  .btn-report
  {
    display: inline-block;
    padding: 5px 10px;
    background-color: #178066;
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
  }
  .btn-report:hover
  {
    background-color: #12634f;
    color: #fff;
    text-decoration: none;
  }
</style>
<body>
    <?php
        include("includes/header.php");
    ?>
    <div class="mainContent container">
      <div class="leftSide">
        <h2>Apply For Covid-19 Test</h2>
        <form method="post">
            <input type="hidden" value="<?php echo $patient['id'];?>" name="pid">
            <input type="text" readonly value="<?php echo htmlspecialchars($patient['name']);?>">
            <select name="hid" required>
                <option value="" disabled selected>Select Hospital</option>
                <?php 
                    $query = "SELECT * FROM tbl_hospital WHERE status='activate'";
                    $result = mysqli_query($connection,$query);
                    if($result)
                    {
                      foreach($result as $row)
                      {
                          echo "<option value='$row[id]'>" . htmlspecialchars($row['name']) . "</option>";
                      }
                    }
                ?>
            </select>
            <input type="submit" value="Submit Test Request" name="btnapply">
        </form>
        <?php
            if(isset($_POST['btnapply']))
            {
                $form_pid = intval($_POST['pid']);
                $hid = intval($_POST['hid']);
                $currentDate = date('Y-m-d');
                
                $query = "INSERT INTO tbl_test(p_id, h_id, date, result) VALUES($form_pid, $hid, '$currentDate', 'Process')";
                $result = mysqli_query($connection,$query);
                if($result)
                {
                    echo 
                    "<script>
                        alert('COVID test request submitted successfully.');
                        window.location.href='covid-test.php';
                    </script>";
                    exit();
                }
            }
        ?>
      </div>
      <div class="rightSide">
        <h2>Your Test Status & Reports</h2>
        <div class="table-responsive">
          <table>
            <thead>
                <tr>
                    <th>Hospital</th>
                    <th>Date</th>
                    <th>Result</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $query = "SELECT tbl_patient.name as 'pname', tbl_hospital.name as 'hname', tbl_test.* FROM tbl_test LEFT JOIN tbl_hospital ON tbl_test.h_id = tbl_hospital.id LEFT JOIN tbl_patient ON tbl_test.p_id = tbl_patient.id WHERE tbl_test.p_id = $pid ORDER BY tbl_test.id DESC";
                    $result = mysqli_query($connection,$query);
                    if($result && mysqli_num_rows($result) > 0)
                    {
                      foreach($result as $row)
                      {
                          $resLower = strtolower($row['result']);
                          $badgeClass = 'badge-process';
                          if($resLower === 'negative') { $badgeClass = 'badge-negative'; }
                          elseif($resLower === 'positive') { $badgeClass = 'badge-positive'; }

                          $dateDisplay = !empty($row['date']) ? htmlspecialchars($row['date']) : 'Pending';

                          echo 
                          "<tr>
                              <td>" . htmlspecialchars($row['hname'] ?? 'N/A') . "</td>
                              <td>$dateDisplay</td>
                              <td><span class='badge-status $badgeClass'>" . htmlspecialchars(ucfirst($row['result'])) . "</span></td>
                              <td>";
                              if($row['result'] !== "Process")
                              {
                                  echo "<a href='download-report.php?id=" . $row['id'] . "' target='_blank' class='btn-report'>View Report</a>";
                              }
                              else
                              {
                                  echo "<span class='text-muted' style='font-size:13px;'>Under Analysis</span>";
                              }
                          echo "</td>
                          </tr>";
                      }
                    }
                    else
                    {
                      echo "<tr><td colspan='4' class='text-muted'>No test records found.</td></tr>";
                    }
                ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
</body>
</html>


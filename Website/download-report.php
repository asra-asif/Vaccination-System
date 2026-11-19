<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
header("Location: ../download-report.php?id=$id", true, 301);
exit();

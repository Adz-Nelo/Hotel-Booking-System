<?php

include("../components/connect.php");

if(isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="../css/admin_style.css">     
</head>
<body>
    <!-- header section starts -->
    <?php include("../components/admin_header.php"); ?>
    <!-- header section ends -->

    <script src="../js/admin_script.js" type="text/javascript"></script>
</body>
</html>
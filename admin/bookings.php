<?php

include("../components/connect.php");

if(isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
    exit;
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $delete_id = htmlspecialchars($delete_id);

    $verify_delete = $conn -> prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
    $verify_delete -> execute([$delete_id]);

    if ($verify_delete -> rowCount() > 0) {
        $delete_bookings = $conn -> prepare("DELETE FROM `bookings` WHERE booking_id = ?");
        $delete_bookings -> execute([$delete_id]);

        $success_msg[] = 'Booking deleted successfully!';
    } else {
        $warning_msg[] = 'booking deleted already!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bookings</title>

    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">     
</head>
<body>
    <!-- header section starts -->
    <?php include("../components/admin_header.php"); ?>
    <!-- header section ends -->

    <!-- header section starts -->
    <section class="grid">
      <h1 class="heading">bookings</h1>

      <div class="box-container">
        <?php
          $select_bookings = $conn -> prepare("SELECT * FROM `bookings`");
          $select_bookings -> execute();

          if ($select_bookings -> rowCount() > 0) {
            while ($fetch_bookings = $select_bookings -> fetch(PDO::FETCH_ASSOC)) {
        ?>

        <div class="box">
            <p>booking ID : <span><?= $fetch_bookings['booking_id']; ?></span></p>
            <p>name : <span><?= $fetch_bookings['name']; ?></span></p>
            <p>email : <span><?= $fetch_bookings['email']; ?></span></p>
            <p>number : <span><?= $fetch_bookings['number']; ?></span></p>
            <p>check in : <span><?= $fetch_bookings['check_in']; ?></span></p>
            <p>check out : <span><?= $fetch_bookings['check_out']; ?></span></p>
            <p>adults : <span><?= $fetch_bookings['adults']; ?></span></p>
            <p>children : <span><?= $fetch_bookings['children']; ?></span></p>

           <form action="" method="POST">
                <input type="hidden" name="delete_id" value="<?= $fetch_bookings['booking_id']; ?>">
                <input type="submit" value="delete booking" name="delete" onclick="return confirm('delete this booking?')" class="btn">
           </form> 
        </div>

        <?php
            }
          } else {
        ?>

        <div class="box" style="text-align: center;">
          <p style="padding-bottom: .5rem;">no bookings found!</p>
          <a href="dashboard.php" class="btn">go to home</a>
        </div>

        <?php
          }
        ?>

      </div>
    </section> 
    <!-- header section ends -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js" type="text/javascript"></script>

    <?php include("../components/message.php"); ?> 
</body>
</html>
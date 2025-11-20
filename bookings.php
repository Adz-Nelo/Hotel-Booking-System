<?php 
include("components/connect.php"); 

if(isset($_COOKIE['user_email'])) {
  $user_email = $_COOKIE['user_email'];
  $select_bookings = $conn -> prepare("SELECT * FROM `bookings` WHERE email = ?");
  $select_bookings -> execute([$user_email]);
} else {
  echo '<p class="empty">Please make a booking first!</p>';
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bookings</title>
    <link rel="icon" href="img/MyLogo.webp" type="image/webp" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
  </head>
  <body>

    <?php include("components/user_header.php"); ?>

    <!-- booking section starts -->
    <section class="bookings">
        <h1 class="heading">my bookings</h1>
        <div class="box-container">
            
        <?php
            // REMOVED the duplicate query preparation here
            if($select_bookings -> rowCount() > 0) {  // FIXED: Check rowCount, not execute
                while($fetch_bookings = $select_bookings -> fetch(PDO::FETCH_ASSOC)) {
        ?>

        <div class="box">
            <p>name : <span><?= $fetch_bookings['name'] ?></span></p>
            <p>email : <span><?= $fetch_bookings['email'] ?></span></p>
            <p>check in : <span><?= $fetch_bookings['check_in'] ?></span></p>
            <p>check out : <span><?= $fetch_bookings['check_out'] ?></span></p>
            <p>rooms : <span><?= $fetch_bookings['rooms'] ?></span></p>
            <p>adults : <span><?=  $fetch_bookings['adults'] ?></span></p>
            <p>children : <span><?=  $fetch_bookings['children'] ?></span></p>
            <p>booking id : <span><?= $fetch_bookings['booking_id'] ?></span></p>
        </div>

        <?php 
                }
            } else {
                echo '<p class="empty">no bookings found!</p>';  // FIXED: Added message
            }
        ?>

        </div>
    </section>
    
    <!-- booking section ends -->

    <?php include("components/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/script.js" type="text/javascript"></script>

    <?php include("components/message.php"); ?>

  </body>
</html>
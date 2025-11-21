<?php 

// include("components/connect.php"); 

if(isset($_COOKIE['user_id'])) {
  $user_id = $_COOKIE['user_id'];
} else {
  $user_id = create_user_id($conn); // Use the new clean function
  setcookie('user_id', $user_id, time() + 60*60*24*30, '/');
  header('location:index.php');
  exit; // Add this to stop script execution
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
      <h1 class="headings">my bookings</h1>

      <div class="box-container">
        <?php
          $select_bookings = $conn -> prepare("SELECT * FROM `bookings` WHERE user_id = ?");
          $select_bookings -> execute([$user_id]);

          if($select_bookings -> rowCount() > 0) {
            while($fetch_booking = $select_bookings -> fetch(PDO::FETCH_ASSOC)) {
        ?>
        
        <div class="box">
          <p>name : <span><?=  $fetch_booking['name']; ?></span></p>
          <p>email : <span><?=  $fetch_booking['email']; ?></span></p>
          <p>number : <span><?=  $fetch_booking['number']; ?></span></p>
          <p>check in : <span><?=  $fetch_booking['check_in']; ?></span></p>
          <p>check out : <span><?=  $fetch_booking['check_out']; ?></span></p>
          <p>rooms : <span><?=  $fetch_booking['rooms']; ?></span></p>
          <p>adults : <span><?=  $fetch_booking['adults']; ?></span></p>
          <p>children : <span><?=  $fetch_booking['children']; ?></span></p> 
          <p>booking iD : <span><?=  $fetch_booking['booking_id']; ?></span></p>
        </div>

        <?php
          }
        } else {
        ?>
        <div class="box" style="text-align: center;">
          <p>no bookings found!</p>
          <a href="index.php#reservation">book new</a>
        </div>
        <?php
        } ?>
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
<?php 
include_once("components/connect.php"); 

// Handle cancellation when form is submitted
if(isset($_POST['cancel'])) {
  $booking_id = isset($_POST['booking_id']) ? htmlspecialchars($_POST['booking_id']) : '';
  
  if(!empty($booking_id)) {
    $verify_booking = $conn -> prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
    $verify_booking -> execute([$booking_id]);

    if($verify_booking -> rowCount() > 0) {
      $delete_booking = $conn -> prepare("DELETE FROM `bookings` WHERE booking_id = ?");
      $delete_booking -> execute([$booking_id]);
      
      // Set a session flag to show success message after redirect
      $_SESSION['show_success'] = true;
    } else {
      $_SESSION['show_warning'] = true;
    }
    
    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  }
}

// Check if we should show success message after redirect
$show_success = isset($_SESSION['show_success']) ? $_SESSION['show_success'] : false;
$show_warning = isset($_SESSION['show_warning']) ? $_SESSION['show_warning'] : false;

// Clear the flags
unset($_SESSION['show_success'], $_SESSION['show_warning']);

// Show all bookings
$select_bookings = $conn -> prepare("SELECT * FROM `bookings` ORDER BY check_in DESC");
$select_bookings -> execute();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bookings</title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
      .bookings .heading {
        text-align: center;
        margin-bottom: 2rem;
        color: var(--sub-color);
        font-size: 2.5rem;
        text-transform: capitalize;
      }

      .bookings .box-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, 35rem);
        gap: 1.5rem;
        justify-content: center;
        align-items: flex-start;
      }

      .bookings .box-container .box {
        border-radius: .5rem;
        padding: 2rem;
        padding-top: 1rem;
        border: var(--border);
      }

      .bookings .box-container .box p {
        line-height: 1.5;
        padding-top: .5rem;
        font-size: 1.8rem;
        color: var(--sub-color);
      }

      .bookings .box-container .box p span {
        color: var(--white);
      }
    </style>
  </head>
  <body>

    <?php include("components/user_header.php"); ?>

    <!-- booking section starts -->
    <section class="bookings">
      <h1 class="heading">my bookings</h1>

      <div class="box-container">
        <?php
          if($select_bookings -> rowCount() > 0) {
            while($fetch_booking = $select_bookings -> fetch(PDO::FETCH_ASSOC)) {
        ?>
        
        <div class="box">
          <p>booking ID : <span><?= $fetch_booking['booking_id']; ?></span></p>
          <p>name : <span><?= $fetch_booking['name']; ?></span></p>
          <p>email : <span><?= $fetch_booking['email']; ?></span></p>
          <p>number : <span><?= $fetch_booking['number']; ?></span></p>
          <p>check in : <span><?= $fetch_booking['check_in']; ?></span></p>
          <p>check out : <span><?= $fetch_booking['check_out']; ?></span></p>
          <p>rooms : <span><?= $fetch_booking['rooms']; ?></span></p>
          <p>adults : <span><?= $fetch_booking['adults']; ?></span></p>
          <p>children : <span><?= $fetch_booking['children']; ?></span></p>
          <form action="" method="POST">
            <input type="hidden" name="booking_id" value="<?= $fetch_booking['booking_id']; ?>">
            <input type="submit" value="cancel booking" name="cancel" class="btn" onclick="return confirm('cancel this booking?');">
          </form>
        </div>

        <?php
            }
          } else {
        ?>
        <div class="box" style="text-align: center;">
          <p style="padding-bottom: .5rem; text-transform: capitalize;">no bookings found!</p>
          <a href="index.php#reservation" class="btn">book new</a>
        </div>
        <?php
        } ?>
      </div>
    </section>

    <!-- Show SweetAlert messages using JavaScript -->
    <?php if($show_success): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        swal("Success", "Booking cancelled successfully!", "success");
    });
    </script>
    <?php endif; ?>

    <?php if($show_warning): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        swal("Warning", "Booking not found!", "warning");
    });
    </script>
    <?php endif; ?>

    <!-- Remove or comment out the message.php include since we're handling messages directly -->
    <!-- <?php //include("components/message.php"); ?> -->
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/script.js" type="text/javascript"></script>
  </body>
</html>
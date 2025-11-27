<?php
include("../components/connect.php");

if(isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
    exit;  // ← ADD THIS
}

$select_profile = $conn -> prepare("SELECT * FROM `admins` WHERE id = ? LIMIT 1");
$select_profile -> execute([$admin_id]);
$fetch_profile = $select_profile -> fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    
    if (!empty($name)) {
        $verify_name = $conn -> prepare("SELECT * FROM `admins` WHERE name = ?");
        $verify_name -> execute([$name]);

        if ($verify_name -> rowCount() > 0) {
            $warning_msg[] = 'Username already taken!';
        } else {
            $update_name = $conn -> prepare("UPDATE `admins` SET name = ? WHERE id = ?");
            $update_name -> execute([$name, $admin_id]);
            $success_msg[] = 'Username updated successfully!';
        }
    }
    
    $empty_password = '';
    $prev_password = $fetch_profile['password'];
    $old_password = isset($_POST['old_password']) ? htmlspecialchars($_POST['old_password']) : '';  // ← Fixed: was 'password'
    $new_password = htmlspecialchars($_POST['new_password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    if (!empty($old_password)) {
        if ($old_password != $prev_password) {
            $warning_msg[] = 'Old password not matched!';
        } elseif ($confirm_password != $new_password) {
            $warning_msg[] = 'New password not matched!';
        } else {
            if (!empty($new_password)) {
                $update_password = $conn -> prepare("UPDATE `admins` SET password = ? WHERE id = ?");
                $update_password -> execute([$new_password, $admin_id]);  // ← Fixed: was $password
    
                $success_msg[] = 'Password updated successfully!';
            } else {
                $warning_msg[] = 'Please enter new password!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>

    <link rel="stylesheet" href="../css/admin_style.css">
    
</head>
<body>
    <!-- header section starts -->
    <?php include("../components/admin_header.php"); ?>
    <!-- header section ends -->

    <!-- update section starts  -->
    <section class="form-container">
        <form action="" method="POST">
            <h3>update profile</h3>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="20"
            class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="old_password" placeholder="enter old password" maxlength="20" 
            class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_password" placeholder="enter new password" maxlength="20" 
            class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="confirm_password" placeholder="confirm new password" maxlength="20" 
            class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="update now" name="submit" class="btn">
        </form>
    </section>
    <!-- update section ends -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js" type="text/javascript"></script>

    <?php include("../components/message.php"); ?>
</body>
</html>
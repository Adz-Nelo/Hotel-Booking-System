<?php

include("../components/connect.php");

if(isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
}

if(isset($_POST['submit'])) {
    $id = create_user_id($conn);

    $name = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    $select_admins = $conn -> prepare("SELECT * FROM `admins` WHERE name = ?");
    $select_admins -> execute([$name]);

    if($select_admins -> rowCount() > 0) {
        $warning_msg[] = 'Username already taken!';
    } else {
        if($password != $confirm_password) {
            $warning_msg[] = 'Password not matched!';
        } else {
            $insert_admin = $conn -> prepare("INSERT INTO `admins` (id, name, password) VALUES(?,?,?)");
            $insert_admin -> execute([$id, $name, $confirm_password]);
            $success_msg[] = 'Registered successfully!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="icon" href="../img/MyLogo.webp" type="image/webp">     
</head>
<body>
    <!-- header section starts -->
    <?php include("../components/admin_header.php"); ?>
    <!-- header section ends -->

    <!-- register section starts  -->
    <section class="form-container">
        <form action="" method="POST">
            <h3>register new</h3>
            <input type="text" name="name" placeholder="enter username" maxlength="20" 
            class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="password" placeholder="enter password" maxlength="20" 
            class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="confirm_password" placeholder="confirm password" maxlength="20" 
            class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="register now" name="submit" class="btn">
        </form>
    </section>
    <!-- register section ends -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js" type="text/javascript"></script>

    <?php include("../components/message.php"); ?>
</body>
</html>
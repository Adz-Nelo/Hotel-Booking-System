<?php

include("../components/connect.php");

if(isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['password']);

    $select_admins = $conn -> prepare("SELECT * FROM `admins` WHERE name = ? AND password = ? LIMIT 1");
    $select_admins -> execute([$name, $password]);

    $row = $select_admins -> fetch(PDO::FETCH_ASSOC);

    if($select_admins -> rowCount() > 0) {
        setcookie('admin_id', $row['id'], time() + 60*60*24*30, '/');
        header('location:dashboard.php');
    } else {
        $warning_msg[] = 'Incorrect username or password!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="icon" href="../img/MyLogo.webp" type="image/webp">     
</head>
<body>
    <!-- login section starts  -->
    <section class="form-container" style="min-height: 100vh;">
        <form action="" method="POST">
            <h3>welcome back!</h3>
            <p>default username = <span>admin</span> and password = <span>123</span></p>
            <input type="text" name="name" placeholder="enter username" maxlength="20" 
            class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="password" placeholder="enter password" maxlength="20" 
            class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="login now" name="submit" class="btn">
        </form>
    </section>
    <!-- login section ends -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js" type="text/javascript"></script>

    <?php include("../components/message.php"); ?>
</body>
</html>
<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit;
}

$message = [];

if(isset($_POST['submit'])) {
   $name = $_POST['name'];
   $pass = sha1($_POST['pass']);
   $cpass = sha1($_POST['cpass']);

   // تجنب SQL Injection بتنظيف البيانات قبل استخدامها
   $name = mysqli_real_escape_string($conn, $name);
   $pass = mysqli_real_escape_string($conn, $pass);
   $cpass = mysqli_real_escape_string($conn, $cpass);

   // التحقق مما إذا كان اسم المستخدم موجود بالفعل
   $select_admin = "SELECT * FROM `admins` WHERE name = '$name'";
   $result = $conn->query($select_admin);

   if($result->num_rows > 0) {
      $message[] = 'Username already exists!';
   } else {
      if($pass != $cpass) {
         $message[] = 'Confirm password not matched!';
      } else {
         $insert_admin = "INSERT INTO `admins` (name, password) VALUES ('$name', '$cpass')";
         if ($conn->query($insert_admin) === TRUE) {
            $message[] = 'New admin registered successfully!';
         } else {
            $message[] = 'Error: ' . $conn->error;
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Admin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Register Now</h3>
      <?php
      if (!empty($message)) {
         foreach ($message as $msg) {
            echo "<p>$msg</p>";
         }
      }
      ?>
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Register Now" class="btn" name="submit">
   </form>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>

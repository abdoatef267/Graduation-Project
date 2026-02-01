<?php

include 'components/connect.php';

session_start();

// Initialize message array
$message = [];

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){
   // Sanitize inputs
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, sha1($_POST['pass']));
   $cpass = mysqli_real_escape_string($conn, sha1($_POST['cpass']));

   // Check if email already exists
   $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'");
   if(mysqli_num_rows($select_user) > 0){
      $message[] = 'email already exists!';
   }else{
      // Validate and insert new user
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_user = mysqli_query($conn, "INSERT INTO `users`(name, email, password) VALUES('$name', '$email', '$cpass')");
         if($insert_user){
            $message[] = 'registered successfully, login now please!';
         }else{
            $message[] = 'registration failed!';
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
   <title>Register</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Register Now.</h3>
      <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="confirm your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" class="btn" name="submit">
      <p>Already have an account?</p>
      <a href="user_login.php" class="option-btn">Login Now.</a>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>

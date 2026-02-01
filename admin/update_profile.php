<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit; // تأكد من عدم استمرار التنفيذ بعد عملية التوجيه
}

if(isset($_POST['submit'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $old_pass = mysqli_real_escape_string($conn, $_POST['old_pass']);
   $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
   $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

   // التحقق من الرمز السري القديم
   $query = "SELECT password FROM admins WHERE id = '$admin_id'";
   $result = mysqli_query($conn, $query);
   if($result){
      $admin = mysqli_fetch_assoc($result);
      $prev_pass = $admin['password'];
   } else {
      die("Database query failed. " . mysqli_error($conn));
   }

   $message = array();

   if(empty($old_pass)){
      $message[] = 'Please enter old password!';
   } elseif($old_pass != $prev_pass){
      $message[] = 'Old password not matched!';
   } elseif($new_pass != $confirm_pass){
      $message[] = 'Confirm password not matched!';
   } else {
      // تحديث الاسم إذا تم إدخال اسم جديد
      if(!empty($name)){
         $query = "UPDATE admins SET name = '$name' WHERE id = '$admin_id'";
         $result = mysqli_query($conn, $query);
         if(!$result){
            $message[] = "Failed to update name. " . mysqli_error($conn);
         } else {
            $message[] = 'Name updated successfully!';
         }
      }

      // تحديث كلمة المرور إذا تم إدخال كلمة مرور جديدة
      if($new_pass != $empty_pass){
         $new_pass_hashed = sha1($new_pass);
         $query = "UPDATE admins SET password = '$new_pass_hashed' WHERE id = '$admin_id'";
         $result = mysqli_query($conn, $query);
         if(!$result){
            $message[] = "Failed to update password. " . mysqli_error($conn);
         } else {
            $message[] = 'Password updated successfully!';
         }
      } else {
         $message[] = 'Please enter a new password!';
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
   <title>Update Profile</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Update Profile</h3>
      <?php foreach ($message as $msg) : ?>
         <div class="message"><?php echo $msg; ?></div>
      <?php endforeach; ?>
      <input type="text" name="name" value="<?= isset($name) ? htmlspecialchars($name) : ''; ?>" required placeholder="Enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="old_pass" placeholder="Enter old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="Enter new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" placeholder="Confirm new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Update now" class="btn" name="submit">
   </form>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>

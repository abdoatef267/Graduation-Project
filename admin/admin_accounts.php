<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit; // تأكد من توقف تنفيذ السكربت بعد عملية التوجيه
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = mysqli_prepare($conn, "DELETE FROM `admins` WHERE id = ?");
   mysqli_stmt_bind_param($delete_admins, "i", $delete_id);
   mysqli_stmt_execute($delete_admins);
   mysqli_stmt_close($delete_admins);
   header('location:admin_accounts.php');
   exit; // تأكد من توقف تنفيذ السكربت بعد عملية التوجيه
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">Admin Accounts</h1>

   <div class="box-container">

   <div class="box">
      <p>Add New Admin</p>
      <a href="register_admin.php" class="option-btn">Register Admin</a>
   </div>

   <?php
      $select_accounts = mysqli_query($conn, "SELECT * FROM `admins`");
      if(mysqli_num_rows($select_accounts) > 0){
         while($fetch_accounts = mysqli_fetch_assoc($select_accounts)){   
   ?>
   <div class="box">
      <p> Admin Id : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Admin name : <span><?= $fetch_accounts['name']; ?></span> </p>
      <div class="flex-btn">
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('delete this account?')" class="delete-btn">delete</a>
         <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="update_profile.php" class="option-btn">update</a>';
            }
         ?>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no accounts available!</p>';
      }
   ?>

   </div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>

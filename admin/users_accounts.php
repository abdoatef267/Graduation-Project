<?php

include '../components/connect.php';

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: admin_login.php');
    exit(); // Ensure script stops after redirection
}

// Handle deletion of user and related data
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Delete user from 'users' table
    $delete_user_query = "DELETE FROM `users` WHERE id = '$delete_id'";
    if (mysqli_query($conn, $delete_user_query)) {
        // Delete user's orders from 'orders' table
        $delete_orders_query = "DELETE FROM `orders` WHERE user_id = '$delete_id'";
        mysqli_query($conn, $delete_orders_query);

        // Delete user's messages from 'messages' table
        $delete_messages_query = "DELETE FROM `messages` WHERE user_id = '$delete_id'";
        mysqli_query($conn, $delete_messages_query);

        // Delete user's cart from 'cart' table
        $delete_cart_query = "DELETE FROM `cart` WHERE user_id = '$delete_id'";
        mysqli_query($conn, $delete_cart_query);

        // Delete user's wishlist from 'wishlist' table
        $delete_wishlist_query = "DELETE FROM `wishlist` WHERE user_id = '$delete_id'";
        mysqli_query($conn, $delete_wishlist_query);

        // Redirect back to users_accounts.php after deletion
        header('location: users_accounts.php');
        exit(); // Ensure script stops after redirection
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users accounts</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">
   <h1 class="heading">User accounts</h1>
   <div class="box-container">

   <?php
      // Retrieve users from 'users' table
      $select_accounts_query = "SELECT * FROM `users`";
      $result = mysqli_query($conn, $select_accounts_query);

      if (mysqli_num_rows($result) > 0) {
         while ($fetch_accounts = mysqli_fetch_assoc($result)) {
   ?>
   <div class="box">
      <p> User id : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Username : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p> Email : <span><?= $fetch_accounts['email']; ?></span> </p>
      <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Delete this account? All related information will also be deleted!')" class="delete-btn">delete</a>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No accounts available!</p>';
      }
   ?>

   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>

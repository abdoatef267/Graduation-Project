<?php

include '../components/connect.php';

session_start();

// Check if admin is logged in
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location: admin_login.php');
    exit;
}

// Handle message deletion
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_message_query = "DELETE FROM `messages` WHERE id = '$delete_id'";

    if (mysqli_query($conn, $delete_message_query)) {
        header('location: messages.php');
        exit;
    } else {
        echo "Error deleting message: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="contacts">
   <h1 class="heading">Messages</h1>
   <div class="box-container">
      <?php
         $select_messages_query = "SELECT * FROM `messages`";
         $select_messages_result = mysqli_query($conn, $select_messages_query);

         if (mysqli_num_rows($select_messages_result) > 0) {
            while ($fetch_message = mysqli_fetch_assoc($select_messages_result)) {
      ?>
      <div class="box">
         <p>User id: <span><?= $fetch_message['user_id']; ?></span></p>
         <p>Name: <span><?= $fetch_message['name']; ?></span></p>
         <p>Email: <span><?= $fetch_message['email']; ?></span></p>
         <p>Number: <span><?= $fetch_message['number']; ?></span></p>
         <p>Message: <span><?= $fetch_message['message']; ?></span></p>
         <a href="messages.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('Delete this message?');" class="delete-btn">Delete</a>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">You have no messages</p>';
         }
      ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>

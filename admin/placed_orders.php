<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit; // توقف التنفيذ بعد إعادة التوجيه
}

if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];

   // تجهيز الاستعلام باستخدام mysqli
   $update_payment = mysqli_prepare($conn, "UPDATE `orders` SET payment_status = ? WHERE id = ?");
   mysqli_stmt_bind_param($update_payment, 'si', $payment_status, $order_id);
   mysqli_stmt_execute($update_payment);
   mysqli_stmt_close($update_payment);

   $message = 'تم تحديث حالة الدفع!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];

   // تجهيز الاستعلام باستخدام mysqli
   $delete_order = mysqli_prepare($conn, "DELETE FROM `orders` WHERE id = ?");
   mysqli_stmt_bind_param($delete_order, 'i', $delete_id);
   mysqli_stmt_execute($delete_order);
   mysqli_stmt_close($delete_order);

   header('location:placed_orders.php');
   exit; // توقف التنفيذ بعد إعادة التوجيه
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="orders">

<h1 class="heading">Placed Orders</h1>

<div class="box-container">

   <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`");
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
   ?>
   <div class="box">
      <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total price : <span>EGP.<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> Payment method : <span><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="select">
            <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
         </select>
        <div class="flex-btn">
         <input type="submit" value="update" class="option-btn" name="update_payment">
         <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
        </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No orders placed yet!</p>';
      }
   ?>

</div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>

<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
}

$message = [];

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = "DELETE FROM `cart` WHERE id = " . mysqli_real_escape_string($conn, $cart_id);
   if(mysqli_query($conn, $delete_cart_item)){
      $message[] = 'Item deleted from cart successfully!';
   } else {
      $message[] = 'Error deleting item from cart: ' . mysqli_error($conn);
   }
}

if(isset($_GET['delete_all'])){
   $delete_all_cart_items = "DELETE FROM `cart` WHERE user_id = " . mysqli_real_escape_string($conn, $user_id);
   if(mysqli_query($conn, $delete_all_cart_items)){
      header('location:cart.php');
   } else {
      $message[] = 'Error deleting all items from cart: ' . mysqli_error($conn);
   }
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = "UPDATE `cart` SET quantity = " . mysqli_real_escape_string($conn, $qty) . " WHERE id = " . mysqli_real_escape_string($conn, $cart_id);
   if(mysqli_query($conn, $update_qty)){
      $message[] = 'Cart quantity updated successfully!';
   } else {
      $message[] = 'Error updating cart quantity: ' . mysqli_error($conn);
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">

   <h3 class="heading">Shopping cart</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = "SELECT * FROM `cart` WHERE user_id = " . mysqli_real_escape_string($conn, $user_id);
      $result = mysqli_query($conn, $select_cart);
      if(mysqli_num_rows($result) > 0){
         while($fetch_cart = mysqli_fetch_assoc($result)){
            $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <div class="flex">
         <div class="price">EGP.<?= $fetch_cart['price']; ?>/-</div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
         <button type="submit" class="fas fa-edit" name="update_qty"></button>
      </div>
      <div class="sub-total"> Sub Total : <span>$<?= $sub_total; ?>/-</span> </div>
      <input type="submit" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete">
   </form>
   <?php
   $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty">your cart is empty</p>';
      }
   ?>
   </div>

   <div class="cart-total">
      <p>Grand Total : <span>EGP.<?= $grand_total; ?>/-</span></p>
      <a href="shop.php" class="option-btn">Continue Shopping.</a>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">Delete All Items ?</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout.</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>

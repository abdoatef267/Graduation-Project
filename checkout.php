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

// Fetch user information if available
$user_query = "SELECT name, email FROM `users` WHERE id = '$user_id'";
$user_result = $conn->query($user_query);

if($user_result->num_rows > 0) {
   $fetch_user = $user_result->fetch_assoc();
   $user_name = $fetch_user['name'];
   $user_email = $fetch_user['email'];
} else {
   $user_name = '';
   $user_email = '';
}

// Fetch user information if available
$info_query = "SELECT * FROM `user_information` WHERE user_id = '$user_id'";
$info_result = $conn->query($info_query);

if($info_result->num_rows > 0) {
   $fetch_info = $info_result->fetch_assoc();
   $phone_number = $fetch_info['phone_number'];
   $address_flat = $fetch_info['address_flat'];
   $address_street = $fetch_info['address_street'];
   $address_city = $fetch_info['address_city'];
   $address_state = $fetch_info['address_state'];
   $address_country = $fetch_info['address_country'];
   $pin_code = $fetch_info['pin_code'];
} else {
   $phone_number = '';
   $address_flat = '';
   $address_street = '';
   $address_city = '';
   $address_state = '';
   $address_country = '';
   $pin_code = '';
}

if(isset($_POST['order'])) {
   // Process order form submission

   // Sanitize and validate input fields
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = mysqli_real_escape_string($conn, $_POST['number']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $flat = mysqli_real_escape_string($conn, $_POST['flat']);
   $street = mysqli_real_escape_string($conn, $_POST['street']);
   $city = mysqli_real_escape_string($conn, $_POST['city']);
   $state = mysqli_real_escape_string($conn, $_POST['state']);
   $country = mysqli_real_escape_string($conn, $_POST['country']);
   $pin_code = mysqli_real_escape_string($conn, $_POST['pin_code']);

   $address = "flat no. $flat, $street, $city, $state, $country - $pin_code";
   $total_products = mysqli_real_escape_string($conn, $_POST['total_products']);
   $total_price = mysqli_real_escape_string($conn, $_POST['total_price']);

   // Begin database transaction
   $conn->autocommit(false);

   try {
      // Insert order details into `orders` table
      $insert_order_query = "INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price) VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$total_price')";
      $conn->query($insert_order_query);

      // Decrease product quantities in `products` table based on cart items
      $select_cart_query = "SELECT * FROM `cart` WHERE user_id = '$user_id'";
      $select_cart_result = $conn->query($select_cart_query);

      while($fetch_cart = $select_cart_result->fetch_assoc()) {
         if (isset($fetch_cart['pid'])) { // التأكد من وجود المفتاح 'pid'
            $product_id = $fetch_cart['pid'];
            $quantity_ordered = $fetch_cart['quantity'];

            $update_product_query = "UPDATE `products` SET quantity = quantity - '$quantity_ordered' WHERE id = '$product_id'";
            $conn->query($update_product_query);
         }
      }

      // Clear user's cart after successful order placement
      $delete_cart_query = "DELETE FROM `cart` WHERE user_id = '$user_id'";
      $conn->query($delete_cart_query);

      // Commit the transaction
      $conn->commit();

      $message[] = 'Order placed successfully!';
   } catch (Exception $e) {
      // Rollback the transaction if any error occurred
      $conn->rollback();
      $message[] = 'Error placing order: ' . $e->getMessage();
   }

   // Re-enable autocommit
   $conn->autocommit(true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   
   <!-- Font Awesome CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">
   <form action="" method="POST">

      <h3>Your Orders</h3>

      <div class="display-orders">
         <?php
         $grand_total = 0;
         $cart_items = [];
         $select_cart_query = "SELECT * FROM `cart` WHERE user_id = '$user_id'";
         $select_cart_result = $conn->query($select_cart_query);

         if($select_cart_result->num_rows > 0) {
            while($fetch_cart = $select_cart_result->fetch_assoc()) {
               $cart_items[] = $fetch_cart['name'] . ' (' . '$' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ')';
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
               ?>
               <p><?= $fetch_cart['name']; ?> <span>(<?= '$' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity']; ?>)</span></p>
               <?php
            }
         } else {
            echo '<p class="empty">Your cart is empty!</p>';
         }
         ?>
         <input type="hidden" name="total_products" value="<?= implode(', ', $cart_items); ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">Grand Total: <span>$<?= $grand_total; ?></span></div>
      </div>

      <h3>Place Your Order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Your Name:</span>
            <input type="text" name="name" class="box" maxlength="50" required value="<?= $user_name; ?>">
         </div>
         <div class="inputBox">
            <span>Your Number:</span>
            <input type="number" name="number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required value="<?= $phone_number; ?>">
         </div>
         <div class="inputBox">
            <span>Your Email:</span>
            <input type="email" name="email" class="box" maxlength="50" required value="<?= $user_email; ?>">
         </div>
         <div class="inputBox">
            <span>Payment Method:</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Cash On Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="paytm">eSewa</option>
               <option value="paypal">Khalti</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address Line 01:</span>
            <input type="text" name="flat" class="box" maxlength="50" required value="<?= $address_flat; ?>">
         </div>
         <div class="inputBox">
            <span>Address Line 02:</span>
            <input type="text" name="street" class="box" maxlength="50" required value="<?= $address_street; ?>">
         </div>
         <div class="inputBox">
            <span>City:</span>
            <input type="text" name="city" class="box" maxlength="50" required value="<?= $address_city; ?>">
         </div>
         <div class="inputBox">
            <span>State:</span>
            <input type="text" name="state" class="box" maxlength="50" required value="<?= $address_state; ?>">
         </div>
         <div class="inputBox">
            <span>Country:</span>
            <input type="text" name="country" class="box" maxlength="50" required value="<?= $address_country; ?>">
         </div>
         <div class="inputBox">
            <span>ZIP CODE:</span>
            <input type="number" name="pin_code" class="box" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" required value="<?= $pin_code; ?>">
         </div>
         <div class="inputBox">
   <span>ZIP CODE:</span>
   <input type="number" name="pin_code" class="box" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" required value="<?= $pin_code; ?>">
</div>
</div>

<input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Place Order">

</form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>

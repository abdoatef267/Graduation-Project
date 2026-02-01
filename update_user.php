<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('Location: user_login.php'); // Redirect to login if not logged in
   exit;
}

$message = [];

// Fetch user profile information
$fetch_profile_query = "SELECT * FROM `users` WHERE id = " . mysqli_real_escape_string($conn, $user_id);
$fetch_profile_result = mysqli_query($conn, $fetch_profile_query);
$fetch_profile = mysqli_fetch_assoc($fetch_profile_result);

if (!$fetch_profile) {
   // Handle case where no user profile found
   $fetch_profile = [
       "name" => "",
       "email" => "",
       "password" => "" // You should avoid storing passwords in session or variables
   ];
}

// Fetch user information if available
$fetch_info_query = "SELECT * FROM `user_information` WHERE user_id = " . mysqli_real_escape_string($conn, $user_id);
$fetch_info_result = mysqli_query($conn, $fetch_info_query);
$fetch_info = mysqli_fetch_assoc($fetch_info_result);

if (!$fetch_info) {
   // Handle case where no user information found
   $fetch_info = [
       "phone_number" => "",
       "address_flat" => "",
       "address_street" => "",
       "address_city" => "",
       "address_state" => "",
       "address_country" => "",
       "pin_code" => ""
   ];
}

if(isset($_POST['submit'])){
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

   if(!empty($name) && !empty($email)){
      $update_profile_query = "UPDATE `users` SET name = '" . mysqli_real_escape_string($conn, $name) . "', email = '" . mysqli_real_escape_string($conn, $email) . "' WHERE id = " . mysqli_real_escape_string($conn, $user_id);
      if(mysqli_query($conn, $update_profile_query)){
         $message[] = 'Profile updated successfully!';
      } else {
         $message[] = 'Error updating profile: ' . mysqli_error($conn);
      }
   }

   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1(filter_var($_POST['old_pass'], FILTER_SANITIZE_STRING));
   $new_pass = sha1(filter_var($_POST['new_pass'], FILTER_SANITIZE_STRING));
   $cpass = sha1(filter_var($_POST['cpass'], FILTER_SANITIZE_STRING));

   $empty_pass = sha1('');

   if($old_pass === $empty_pass){
      $message[] = 'Please enter old password!';
   } elseif($old_pass !== $prev_pass){
      $message[] = 'Old password not matched!';
   } elseif($new_pass !== $cpass){
      $message[] = 'Confirm password not matched!';
   } else {
      if($new_pass !== $empty_pass){
         $update_password_query = "UPDATE `users` SET password = '" . mysqli_real_escape_string($conn, $cpass) . "' WHERE id = " . mysqli_real_escape_string($conn, $user_id);
         if(mysqli_query($conn, $update_password_query)){
            $message[] = 'Password updated successfully!';
         } else {
            $message[] = 'Error updating password: ' . mysqli_error($conn);
         }
      } else {
         $message[] = 'Please enter a new password!';
      }
   }
}

if(isset($_POST['update_info'])){
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $flat = filter_var($_POST['flat'], FILTER_SANITIZE_STRING);
   $street = filter_var($_POST['street'], FILTER_SANITIZE_STRING);
   $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
   $state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
   $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
   $pin_code = filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING);

   $check_info_query = "SELECT * FROM `user_information` WHERE user_id = " . mysqli_real_escape_string($conn, $user_id);
   $check_info_result = mysqli_query($conn, $check_info_query);

   if(mysqli_num_rows($check_info_result) > 0){
      $update_info_query = "UPDATE `user_information` SET phone_number = '" . mysqli_real_escape_string($conn, $number) . "', address_flat = '" . mysqli_real_escape_string($conn, $flat) . "', address_street = '" . mysqli_real_escape_string($conn, $street) . "', address_city = '" . mysqli_real_escape_string($conn, $city) . "', address_state = '" . mysqli_real_escape_string($conn, $state) . "', address_country = '" . mysqli_real_escape_string($conn, $country) . "', pin_code = '" . mysqli_real_escape_string($conn, $pin_code) . "' WHERE user_id = " . mysqli_real_escape_string($conn, $user_id);
      if(mysqli_query($conn, $update_info_query)){
         $message[] = 'User information updated successfully!';
      } else {
         $message[] = 'Error updating user information: ' . mysqli_error($conn);
      }
   } else {
      $insert_info_query = "INSERT INTO `user_information` (user_id, phone_number, address_flat, address_street, address_city, address_state, address_country, pin_code) VALUES ('$user_id', '" . mysqli_real_escape_string($conn, $number) . "', '" . mysqli_real_escape_string($conn, $flat) . "', '" . mysqli_real_escape_string($conn, $street) . "', '" . mysqli_real_escape_string($conn, $city) . "', '" . mysqli_real_escape_string($conn, $state) . "', '" . mysqli_real_escape_string($conn, $country) . "', '" . mysqli_real_escape_string($conn, $pin_code) . "')";
      if(mysqli_query($conn, $insert_info_query)){
         $message[] = 'User information saved successfully!';
      } else {
         $message[] = 'Error saving user information: ' . mysqli_error($conn);
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_user_query = "DELETE FROM `users` WHERE id = " . mysqli_real_escape_string($conn, $delete_id);
   $delete_orders_query = "DELETE FROM `orders` WHERE user_id = " . mysqli_real_escape_string($conn, $delete_id);
   $delete_messages_query = "DELETE FROM `messages` WHERE user_id = " . mysqli_real_escape_string($conn, $delete_id);
   $delete_cart_query = "DELETE FROM `cart` WHERE user_id = " . mysqli_real_escape_string($conn, $delete_id);
   $delete_wishlist_query = "DELETE FROM `wishlist` WHERE user_id = " . mysqli_real_escape_string($conn, $delete_id);
   $delete_info_query = "DELETE FROM `user_information` WHERE user_id = " . mysqli_real_escape_string($conn, $delete_id);

   // Perform deletion queries
   if(mysqli_query($conn, $delete_user_query) && mysqli_query($conn, $delete_orders_query) && mysqli_query($conn, $delete_messages_query) && mysqli_query($conn, $delete_cart_query) && mysqli_query($conn, $delete_wishlist_query) && mysqli_query($conn, $delete_info_query)){
      session_destroy(); // End session after account deletion
      header('location: user_login.php'); // Redirect to login after deletion
   } else {
      $message[] = 'Error deleting account and associated data: ' . mysqli_error($conn);
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .main-container {
         display: flex;
         margin: 20px;
      }
      .floating-menu {
         width: 260px;
         background-color: #f9f9f9;
         border: 1px solid #ccc;
         border-radius: 8px;
         margin-right: 20px;
         display: flex;
         flex-direction: column;
         align-items: center;
      }
      .user-section {
         background-color: #ccc;
         width: 100%;
         height: 80px;
         padding: 10px;
         text-align: center;
      }
      .button-group {
         width: 90%;
         display: flex;
         flex-direction: column;
         padding: 10px 0;
      }
      .menu-button {
         background-color: white;
         border: 1px solid #ccc;
         padding: 15px;
         margin-bottom: 3px;
         text-align: left;
         cursor: pointer;
         border-radius: 4px;
      }
      .menu-button:hover {
         background-color: #e0e0e0;
      }
      .form-container {
         flex: 1;
         padding: 20px;
         background-color: #f9f9f9;
         border-radius: 8px;
         border: 1px solid #ccc;
      }
      
      .btn {
         background-color: darkred;
         color: white;
         padding: 10px 15px;
         border: none;
         border-radius: 4px;
         cursor: pointer;
         font-size: 16px;
      }
      .btn:hover {
         background-color: red;
      }
      .deta {
         text-align: center;
         display: inline-block;
         width: 290px;
         padding: 12px;
         margin-top: 15px;
         margin-left: 20px;
         cursor: pointer;
         font-size: 18px;
         color: white;
         border-radius: 5px;
         text-decoration: none;
         background-color: darkred;
      }
      .deta:hover {
         background-color: red;
      }
      .buttonnnn {
         display: flex;
         padding-left: 120px;
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="main-container">
   <div class="floating-menu">
      <div class="button-group">
         <a style="font-size:24px;color:black;" href="update_user.php?edit_account" class="menu-button">Edit Account</a>
         <a style="font-size:24px;color:black;" href="update_user.php?user_information" class="menu-button">User information</a>
         <a style="font-size:24px;color:black;" href="update_user.php?delete_acc" class="menu-button">Delete Account</a>
         <a style="font-size:24px;color:black;" href="components/user_logout.php" class="menu-button" onclick="return confirm('logout from the website?');">Logout</a>
      </div>
   </div>
   <div class="form-container">
      <?php
      if (isset($_GET['edit_account'])) {
         ?>
         <form method="post" action="">
            <center><h2>Edit Account</h2></center><br>
            <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" value="<?= htmlspecialchars($fetch_profile["name"]); ?>">
            <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= htmlspecialchars($fetch_profile["email"]); ?>">
            <input type="password" name="old_pass" placeholder="Enter your old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" placeholder="Confirm your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="hidden" name="prev_pass" value="<?= htmlspecialchars($fetch_profile["password"]); ?>">
            <input type="submit" value="Update Now" class="btn" name="submit">
         </form>
         <?php
      } elseif (isset($_GET['user_information'])) {
         ?>
         <form method="post" action="">
            <center><h2>User Information</h2></center><br>
            <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" value="<?= htmlspecialchars($fetch_profile["name"]); ?>">
            <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= htmlspecialchars($fetch_profile["email"]); ?>">
            <input type="number" name="number" placeholder="Enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required value="<?= htmlspecialchars($fetch_info["phone_number"]); ?>">
            <input type="text" name="flat" placeholder="Address e.g. Flat number" class="box" maxlength="50" required value="<?= htmlspecialchars($fetch_info["address_flat"]); ?>">
            <input type="text" name="street" placeholder="Street name" class="box" maxlength="50" required value="<?= htmlspecialchars($fetch_info["address_street"]); ?>">
            <input type="text" name="city" placeholder="City" class="box" maxlength="50" required value="<?= htmlspecialchars($fetch_info["address_city"]); ?>">
            <input type="text" name="state" placeholder="State" class="box" maxlength="50" required value="<?= htmlspecialchars($fetch_info["address_state"]); ?>">
            <input type="text" name="country" placeholder="Country" class="box" maxlength="50" required value="<?= htmlspecialchars($fetch_info["address_country"]); ?>">
            <input type="number" min="0" name="pin_code" placeholder="Zip Code" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required value="<?= htmlspecialchars($fetch_info["pin_code"]); ?>">
            <input type="submit" value="Update Now" class="btn" name="update_info">
         </form>
         <?php
      } elseif (isset($_GET['delete_acc'])) {
         ?>
         <center><h1 style="color:black;">Do You Really Want To Delete Your Account!</h1></center>
         <div class="buttonnnn">
            <a href="update_user.php?delete=<?= $user_id; ?>" class='deta'>Yes, I Want To Delete</a>
            <a href='update_user.php' style="background-color:darkblue;" class='deta'>No, I Don't Want To Delete</a>
         </div>
         <?php
      }
      ?>
   </div>
</div>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>

</body>
</html>

<?php
if(isset($message) && is_array($message)) {
   foreach($message as $msg) {
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="home.php" class="logo">Junior<span> Store</span></a>

      <nav class="navbar">
         <a style="text-decoration: none;" href="home.php">Home</a>
         <a style="text-decoration: none;" href="orders.php">Orders</a>
         <a style="text-decoration: none;" href="shop.php">Shop Now</a>
         <a style="text-decoration: none;" href="contact.php">Contact Us</a>
      </nav>

      <div class="icons">
         <?php
         // استعلامات لاستخراج عدد العناصر في قائمة الرغبات وسلة التسوق
         $count_wishlist_items = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id'");
         $total_wishlist_counts = mysqli_num_rows($count_wishlist_items);

         $count_cart_items = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
         $total_cart_counts = mysqli_num_rows($count_cart_items);
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php"><i class="fas fa-search"></i>Search</a>
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php          
         // استعلام لاستخراج معلومات المستخدم
         $select_profile = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'");
         if(mysqli_num_rows($select_profile) > 0){
            $fetch_profile = mysqli_fetch_assoc($select_profile);
            ?>
            <p><?= $fetch_profile["name"]; ?></p>
            <a href="update_user.php" class="btn">Update Profile.</a>
            <div class="flex-btn">
               <a href="user_register.php" class="option-btn">Register.</a>
               <a href="user_login.php" class="option-btn">Login.</a>
            </div>
            <a href="components/user_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a> 
            <?php
         } else {
            ?>
            <p>Please Login Or Register First to proceed !</p>
            <div class="flex-btn">
               <a href="user_register.php" class="option-btn">Register</a>
               <a href="user_login.php" class="option-btn">Login</a>
            </div>
            <?php
         }
         ?>      
      </div>

   </section>

</header>

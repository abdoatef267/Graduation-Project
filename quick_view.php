<?php
// Include the connection file
include 'components/connect.php';

// Start session
session_start();

// Initialize variables
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Include wishlist/cart logic
include 'components/wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quick view</title>
   
   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">
   <h1 class="heading">Quick view</h1>

   <?php
   // Ensure $_GET['pid'] is set and numeric
   if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
       $pid = $_GET['pid'];

       // Query to fetch product details
       $query = "SELECT * FROM `products` WHERE `id` = $pid";
       $result = mysqli_query($conn, $query);

       if (mysqli_num_rows($result) > 0) {
           while ($fetch_product = mysqli_fetch_assoc($result)) {
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
            </div>
         </div>
         <div class="content">
            <div class="name"><?= $fetch_product['name']; ?></div>
            <div class="flex">
               <div class="price"><span>EGP.</span><?= $fetch_product['price']; ?><span>/-</span></div>
               <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
            </div>
            <div style="font-size:14px;" class="quantity">Quantity: <?= $fetch_product['quantity']; ?></div>
            <div class="flex-btn">
               <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
               <input class="option-btn" type="submit" name="add_to_wishlist" value="Add to Wishlist">
            </div>
         </div>
      </div>
      <h1 style="font-size:24px;">Specifications :</h1>
      <div style="font-size:24px;" class="details"><?= $fetch_product['details']; ?></div>
   </form>
   <?php
           }
       } else {
           echo '<p class="empty">No products found!</p>';
       }
   } else {
       echo '<p class="empty">No product ID specified!</p>';
   }
   ?>

</section>

<section class="products">
   <h1 class="heading">Latest Products</h1>
   <div class="box-container">
   <?php
   // Query to fetch latest products
   $query = "SELECT * FROM `products`";
   $result = mysqli_query($conn, $query);

   if (mysqli_num_rows($result) > 0) {
       while ($fetch_product = mysqli_fetch_assoc($result)) {
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>EGP.</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
   </form>
   <?php
       }
   } else {
       echo '<p class="empty">No products found!</p>';
   }
   ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>

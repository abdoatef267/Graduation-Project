<?php
include 'components/connect.php';
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
include 'components/wishlist_cart.php';
$search_results = [];
if (isset($_POST['search_btn'])) {
    $search_box = $_POST['search_box'];
    $query = "SELECT * FROM `products` WHERE `name` LIKE '%" . mysqli_real_escape_string($conn, $search_box) . "%'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($fetch_product = mysqli_fetch_assoc($result)) {
                $search_results[] = $fetch_product;
            }
        } else {
            echo '<p class="empty">no products found!</p>';
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_free_result($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search page</title>
   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'components/user_header.php'; ?>
<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="Search here..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>
<section class="products">
   <h1 class="heading">Search Results</h1>
   <div class="box-container">
   <?php
   if (!empty($search_results)) {
       foreach ($search_results as $fetch_product) {
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
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
       }
   } else {
       echo '<p class="empty">no products found!</p>';
   }
   ?>

   </div>

</section>


<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>

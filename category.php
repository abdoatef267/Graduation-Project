<?php
include 'components/connect.php';
session_start();

// Initialize user ID (if logged in)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Include wishlist/cart logic
include 'components/wishlist_cart.php';

// Fetch category name from URL parameter
$category_name = isset($_GET['category_name']) ? $_GET['category_name'] : null;

// Function to sanitize and validate input
function sanitize_input($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}

if ($category_name) {
    // Fetch category id based on category name
    $safe_category_name = sanitize_input($conn, $category_name);
    $select_category = "SELECT id FROM categories WHERE name = '$safe_category_name'";
    $result_category = mysqli_query($conn, $select_category);

    if ($result_category && mysqli_num_rows($result_category) > 0) {
        $category_id = mysqli_fetch_assoc($result_category)['id'];

        // Fetch products for the selected category id
        $select_products = "SELECT * FROM products WHERE category_id = $category_id";
    } else {
        // Handle case when category does not exist
        echo "Category not found!";
        exit();
    }
} else {
    // Fetch all products if no category selected
    $select_products = "SELECT * FROM products";
}

$result_products = mysqli_query($conn, $select_products);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>KinBech.Com</title>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>KinBech.Com</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="home-products">
<h1 class="heading"><?php echo htmlspecialchars($category_name); ?></h1>
   <div class="swiper products-slider">
      <div class="swiper-wrapper">
         <?php
         if (mysqli_num_rows($result_products) > 0) {
            while ($fetch_product = mysqli_fetch_assoc($result_products)) {
               ?>
               <form action="" method="post" class="swiper-slide slide">
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
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>
      </div>
      <div class="swiper-pagination"></div>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

<script>
   var swiper = new Swiper(".products-slider", {
       loop: false, // Disable the loop to avoid duplication
       spaceBetween: 20,
       pagination: {
           el: ".swiper-pagination",
           clickable: true,
       },
       breakpoints: {
           550: {
               slidesPerView: 2,
           },
           768: {
               slidesPerView: 2,
           },
           1024: {
               slidesPerView: 3,
           },
       },
   });
</script>
</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>

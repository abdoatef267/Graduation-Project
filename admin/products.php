<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit; // Always exit after header redirect
}

$message = []; // Initialize an array to store messages

if (isset($_POST['add_product'])) {
    // Sanitize input values
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $category_id = (int) $_POST['category_id'];
    $quantity = (int) $_POST['quantity'];

    // File uploads handling
    $image_01 = $_FILES['image_01']['name'];
    $image_02 = $_FILES['image_02']['name'];
    $image_03 = $_FILES['image_03']['name'];

    // Check if product name already exists
    $check_query = "SELECT * FROM products WHERE name = '$name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $message[] = 'Product name already exists!';
    } else {
        // Prepare and execute insert query
        $insert_query = "INSERT INTO products (name, details, price, image_01, image_02, image_03, category_id, quantity)
                         VALUES ('$name', '$details', '$price', '$image_01', '$image_02', '$image_03', $category_id, $quantity)";

        if (mysqli_query($conn, $insert_query)) {
            // Handle image uploads
            $valid_image_size = true;
            $max_image_size = 2000000; // 2MB

            if ($_FILES['image_01']['size'] > $max_image_size || $_FILES['image_02']['size'] > $max_image_size || $_FILES['image_03']['size'] > $max_image_size) {
                $valid_image_size = false;
                $message[] = 'Image size is too large (max 2MB)!';
            } else {
                move_uploaded_file($_FILES['image_01']['tmp_name'], '../uploaded_img/' . $image_01);
                move_uploaded_file($_FILES['image_02']['tmp_name'], '../uploaded_img/' . $image_02);
                move_uploaded_file($_FILES['image_03']['tmp_name'], '../uploaded_img/' . $image_03);
            }

            if ($valid_image_size) {
                $message[] = 'New product added!';
            }
        } else {
            $message[] = 'Failed to add product.';
        }
    }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   // Select product to delete and unlink images
   $select_query = "SELECT * FROM products WHERE id = $delete_id";
   $select_result = mysqli_query($conn, $select_query);
   $fetch_delete_image = mysqli_fetch_assoc($select_result);

   if ($fetch_delete_image) {
       unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
       unlink('../uploaded_img/' . $fetch_delete_image['image_02']);
       unlink('../uploaded_img/' . $fetch_delete_image['image_03']);

       // Delete product from database
       $delete_query = "DELETE FROM products WHERE id = $delete_id";
       mysqli_query($conn, $delete_query);

       // Delete product from cart
       $delete_cart_query = "DELETE FROM cart WHERE product_id = $delete_id";
       mysqli_query($conn, $delete_cart_query);

       // Delete product from wishlist
       $delete_wishlist_query = "DELETE FROM wishlist WHERE pid = $delete_id"; // تعديل 'product_id' إلى 'pid'
       mysqli_query($conn, $delete_wishlist_query);
   }

   header('location:products.php');
   exit; // Always exit after header redirect
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">
   <h1 class="heading">Add Product</h1>
   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Product Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>Product Price (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
         <div class="inputBox">
            <span>Image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Image 02 (required)</span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Image 03 (required)</span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Product description (required)</span>
            <textarea name="details" placeholder="enter product details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
         <div class="inputBox">
            <span>Product Category (required)</span>
            <select name="category_id" class="box" required>
               <option value="" disabled selected>Select Category</option>
               <?php
               $select_categories_query = "SELECT * FROM categories";
               $select_categories_result = mysqli_query($conn, $select_categories_query);
               while ($fetch_categories = mysqli_fetch_assoc($select_categories_result)) {
                   echo '<option value="' . $fetch_categories['id'] . '">' . $fetch_categories['name'] . '</option>';
               }
               ?>
            </select>
         </div>
         <div class="inputBox">
            <span>Quantity (required)</span>
            <input type="number" min="0" class="box" required name="quantity">
         </div>
      </div>
      
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>
</section>

<section class="show-products">
   <h1 class="heading">Products Added</h1>
   <div class="box-container">
      <?php
      $select_products_query = "SELECT * FROM products";
      $select_products_result = mysqli_query($conn, $select_products_query);

      if (mysqli_num_rows($select_products_result) > 0) {
          while ($fetch_products = mysqli_fetch_assoc($select_products_result)) {
              ?>
              <div class="box">
                  <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                  <div class="name"><?= $fetch_products['name']; ?></div>
                  <div class="price">EGP.<span><?= $fetch_products['price']; ?></span>/-</div>
                  <div class="details"><span><?= $fetch_products['details']; ?></span></div>
                  <div class="quantity">Quantity: <?= $fetch_products['quantity']; ?></div>
                  <div class="flex-btn">
                     <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
                     <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
                  </div>
              </div>
          <?php
          }
      } else {
          echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>

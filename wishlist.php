<?php
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

include 'components/wishlist_cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $wishlist_id = $_POST['wishlist_id'];
    $delete_wishlist_item = mysqli_query($conn, "DELETE FROM `wishlist` WHERE id = '$wishlist_id'");
    if (!$delete_wishlist_item) {
        die("حدث خطأ أثناء حذف العنصر من قائمة الرغبات: " . mysqli_error($conn));
    }
}

if (isset($_GET['delete_all'])) {
    $delete_wishlist_items = mysqli_query($conn, "DELETE FROM `wishlist` WHERE user_id = '$user_id'");
    if (!$delete_wishlist_items) {
        die("حدث خطأ أثناء حذف جميع العناصر من قائمة الرغبات: " . mysqli_error($conn));
    }
    header('location: wishlist.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Wishlist</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h3 class="heading">Your Wishlist.</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id'");
      if (mysqli_num_rows($select_wishlist) > 0) {
         while ($fetch_wishlist = mysqli_fetch_assoc($select_wishlist)) {
            $grand_total += $fetch_wishlist['price'];  
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
      <a href="quick_view.php?pid=<?= $fetch_wishlist['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_wishlist['image']; ?>" alt="">
      <div class="name"><?= $fetch_wishlist['name']; ?></div>
      <div class="flex">
         <div class="price">EGP.<?= $fetch_wishlist['price']; ?>/-</div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
      <input type="submit" value="delete item" onclick="return confirm('delete this from wishlist?');" class="delete-btn" name="delete">
   </form>
   <?php
      }
   } else {
      echo '<p class="empty">your wishlist is empty</p>';
   }
   ?>
   </div>

   <div class="wishlist-total">
      <p>Grand Total : <span>EGP.<?= $grand_total; ?>/-</span></p>
      <a href="shop.php" class="option-btn">Continue Shopping.</a>
      <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('delete all from wishlist?');">delete all item</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>

<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

include 'components/wishlist_cart.php';

// استعلام لاستخراج التصنيفات
$select_categories = mysqli_query($conn, "SELECT DISTINCT * FROM `categories`");

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>junior.Com</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
        .hommm {
    width: 950px;
    height: 450px;
    box-shadow: 20px 20px 30px black;
    background-image: url(img4.jpg);
    animation-name: photo;
    animation-delay: 1s;
    animation-duration: 10s;
    animation-iteration-count: infinite;
    background-repeat: no-repeat;
    background-size: 1000px 500px;
    margin-left: 2%;
}

@keyframes photo {
    5% {
        background-image: url(photo_2024-06-18_02-14-56.jpg);
    }
    10% {
        background-image: url(photo_2024-06-18_02-14-31.jpg);
    }
    15% {
        background-image: url(img5.jpg);
    }
    20% {
        background-image: url(img6.jpg);
    }
    25% {
        background-image: url(photo_2024-06-18_02-43-15.jpg);
    }
    35% {
        background-image: url(img1.jpg);
    }
    40% {
        background-image: url(img2.jpg);
    }
    45% {
        background-image: url(img4.jpg);
    }
    50% {
        background-image: url(img7.jpg);
    }
    60% {
        background-image: url(img8.jpg);
    }
    65% {
        background-image: url(img9.jpg);
    }
}
    </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<br><br>
<center><div class="hommm"></div></center>
<br><br>
<section class="category">

   <h1 class="heading">Shop by Category</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <?php
   if (mysqli_num_rows($select_categories) > 0) {
       while($fetch_categories = mysqli_fetch_assoc($select_categories)) {
           echo '
           <a href="category.php?category_name=' . urlencode($fetch_categories['name']) . '" class="swiper-slide slide">
               <img src="admin/uploaded_img/' . $fetch_categories['image'] . '" alt="">
               <h3>' . $fetch_categories['name'] . '</h3>
           </a>
           ';
       }
   } else {
       echo '<p class="empty">No categories added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>



<section class="home-products">
   <h1 class="heading">Latest products</h1>
   <div class="swiper products-slider">
      <div class="swiper-wrapper">
         <?php
         // استعلام لاستخراج المنتجات
         $select_products = mysqli_query($conn, "SELECT * FROM `products`");

         if (mysqli_num_rows($select_products) > 0) {
            while($fetch_product = mysqli_fetch_assoc($select_products)){
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
var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:false,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

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

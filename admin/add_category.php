<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit; // Ensure script stops here if user is not logged in
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['category_image'])) {
    $category_name = $_POST['category_name'];
    $category_image = $_FILES['category_image']['name'];
    $category_image_tmp_name = $_FILES['category_image']['tmp_name'];
    $category_image_folder = 'uploaded_img/' . $category_image;

    move_uploaded_file($category_image_tmp_name, $category_image_folder);

    $insert_query = "INSERT INTO categories (name, image) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "ss", $category_name, $category_image);
    mysqli_stmt_execute($stmt);

    header('Location: add_category.php');
    exit;
}

if (isset($_GET['delete'])) {
    $category_id = $_GET['delete'];
    $delete_query = "DELETE FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);

    header('Location: add_category.php');
    exit;
}

$select_query = "SELECT * FROM categories";
$result = mysqli_query($conn, $select_query);
$categories_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Categories</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">
   <style> 
      .add-category {
   max-width: 600px;
   margin: 0 auto;
   padding: 20px;
   background-color: #f8f8f8;
   box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
   border-radius: 10px;
   text-align: center;
}

.add-category h1 {
   font-size: 24px;
   margin-bottom: 20px;
   color: #333;
}

.add-category form {
   display: flex;
   flex-direction: column;
   gap: 15px;
}

.add-category input[type="text"],
.add-category input[type="file"] {
   padding: 10px;
   font-size: 16px;
   border: 1px solid #ddd;
   border-radius: 5px;
   width: 100%;
}

.add-category input[type="text"]::placeholder {
   color: #aaa;
}

.add-category input[type="file"] {
   padding: 5px;
}

.add-category .btn {
   padding: 10px 20px;
   font-size: 16px;
   color: #fff;
   background-color: #007bff;
   border: none;
   border-radius: 5px;
   cursor: pointer;
   transition: background-color 0.3s ease;
}

.add-category .btn:hover {
   background-color: #0056b3;
} 

.categories-grid {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Adjust column width as needed */
         gap: 20px; /* Adjust gap between items */
      }

      .category-item {
         display: flex;
         align-items: center;
         padding: 10px;
         background-color: #f1f1f1;
         border-radius: 5px;
      }

      .category-item img {
         width: 50px;
         height: 50px;
         object-fit: cover;
         border-radius: 5px;
         margin-right: 10px;
      }

      .category-item .name {
         flex-grow: 1;
         font-size: 18px; /* Adjust font size */
         color: #333;
      }

      .category-item .delete-btn {
         padding: 5px 10px;
         color: #fff;
         background-color: #ff4d4d;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         transition: background-color 0.3s ease;
      }

      .category-item .delete-btn:hover {
         background-color: #cc0000;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="contacts">
   <h1 class="heading">Add New Category</h1>
   <div class="box-container">
      <section class="add-category">
         <form action="add_category.php" method="post" enctype="multipart/form-data">
            <input type="text" name="category_name" placeholder="Enter Category Name" required>
            <input type="file" name="category_image" accept="image/*" required>
            <input type="submit" value="Add Category" class="btn">
         </form>
      </section>
   </div>
</section>

<section class="categories-list">
   <h1 class="heading">Categories Added</h1>
   <div class="box-container">
      <div class="categories-grid">
         <?php foreach ($categories_list as $category): ?>
            <div class="category-item">
               <img src="uploaded_img/<?= $category['image']; ?>" alt="">
               <div class="name"><?= $category['name']; ?></div>
               <div class="delete-btn-container">
                  <a href="add_category.php?delete=<?= $category['id']; ?>" class="delete-btn" onclick="return confirm('Delete this category?');">Delete</a>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   </div>
</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>

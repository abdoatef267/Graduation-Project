<?php

// تأكد من أن الاتصال بقاعدة البيانات معرفة في ملف connect.php
require_once 'connect.php';

// التحقق من أن المستخدم قام بتسجيل الدخول
if(!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

// إضافة المنتج إلى قائمة الأمنيات
if(isset($_POST['add_to_wishlist'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['pid']; // تعديل هنا
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // التحقق مما إذا كان المنتج موجوداً بالفعل في قائمة الأمنيات
    $check_wishlist_query = "SELECT * FROM `wishlist` WHERE user_id = '$user_id' AND pid = '$product_id'"; // تعديل 'product_id' إلى 'pid'
    $check_wishlist_result = mysqli_query($conn, $check_wishlist_query);

    if(mysqli_num_rows($check_wishlist_result) > 0) {
        $message[] = 'Already added to wishlist!';
    } else {
        // إدراج المنتج إلى قائمة الأمنيات
        $insert_wishlist_query = "INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES ('$user_id', '$product_id', '$name', '$price', '$image')"; // تعديل 'product_id' إلى 'pid'
        if(mysqli_query($conn, $insert_wishlist_query)) {
            $message[] = 'Added to wishlist!';
        } else {
            $message[] = 'Error adding to wishlist: ' . mysqli_error($conn);
        }
    }
}

// إضافة المنتج إلى السلة
if(isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['pid']; // تعديل هنا
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $qty = $_POST['qty'];

    // التحقق مما إذا كان المنتج موجوداً بالفعل في السلة
    $check_cart_query = "SELECT * FROM `cart` WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $check_cart_result = mysqli_query($conn, $check_cart_query);

    if(mysqli_num_rows($check_cart_result) > 0) {
        $message[] = 'Already added to cart!';
    } else {
        // التحقق من كمية المنتج المتاحة في جدول المنتجات
        $check_quantity_query = "SELECT quantity FROM `products` WHERE id = '$product_id'";
        $check_quantity_result = mysqli_query($conn, $check_quantity_query);

        if(mysqli_num_rows($check_quantity_result) > 0) {
            $product = mysqli_fetch_assoc($check_quantity_result);
            if($product['quantity'] < $qty) {
                $message[] = 'Not enough quantity available!';
            } else {
                // إذا كان المنتج موجودًا في قائمة الأمنيات، احذفه قبل إضافته إلى السلة
                $check_wishlist_query = "SELECT * FROM `wishlist` WHERE user_id = '$user_id' AND pid = '$product_id'"; // تعديل 'product_id' إلى 'pid'
                $check_wishlist_result = mysqli_query($conn, $check_wishlist_query);

                if(mysqli_num_rows($check_wishlist_result) > 0) {
                    $delete_wishlist_query = "DELETE FROM `wishlist` WHERE user_id = '$user_id' AND pid = '$product_id'"; // تعديل 'product_id' إلى 'pid'
                    mysqli_query($conn, $delete_wishlist_query);
                }

                // إدراج المنتج إلى السلة
                $insert_cart_query = "INSERT INTO `cart` (user_id, product_id, name, price, quantity, image) VALUES ('$user_id', '$product_id', '$name', '$price', '$qty', '$image')"; // تعديل هنا
                if(mysqli_query($conn, $insert_cart_query)) {
                    $message[] = 'Added to cart!';
                } else {
                    $message[] = 'Error adding to cart: ' . mysqli_error($conn);
                }
            }
        } else {
            $message[] = 'Product not found!';
        }
    }
}

// عرض الرسائل

?>

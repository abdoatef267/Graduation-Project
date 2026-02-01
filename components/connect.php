<?php

// معلومات الاتصال بقاعدة البيانات
$host = 'localhost';
$db_name = 'shop_db';
$user = 'root';
$password = '';

// الاتصال بقاعدة البيانات
$conn = mysqli_connect($host, $user, $password, $db_name);

// التحقق من نجاح الاتصال
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// قطع الاتصال (اختياري)
// mysqli_close($conn);

?>

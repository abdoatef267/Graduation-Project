<?php
// تحقق من وجود الرسائل قبل عرضها
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
        <a href="../admin/dashboard.php" class="logo">Admin<span>Panel</span></a>
        <nav class="navbar">
            <a href="../admin/dashboard.php">Home</a>
            <a href="../admin/add_category.php">Category</a>
            <a href="../admin/products.php">Products</a>
            <a href="../admin/placed_orders.php">Orders</a>
            <a href="../admin/admin_accounts.php">Admins</a>
            <a href="../admin/users_accounts.php">Users</a>
            <a href="../admin/messages.php">Messages</a>
        </nav>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>
        <div class="profile">
            <?php
            // استعلام لاسترداد بيانات الملف الشخصي للمشرف
            $admin_id = $_SESSION['admin_id']; // افترضنا أن هناك متغير $_SESSION للمعرف الخاص بالمشرف
            $select_profile_query = "SELECT * FROM admins WHERE id = $admin_id";
            $select_profile_result = mysqli_query($conn, $select_profile_query);

            // التحقق من نجاح الاستعلام
            if ($select_profile_result && mysqli_num_rows($select_profile_result) > 0) {
                $fetch_profile = mysqli_fetch_assoc($select_profile_result);
                echo '<p>'.$fetch_profile['name'].'</p>';
            }
            ?>
            <a href="../admin/update_profile.php" class="btn">Update Profile</a>
            <div class="flex-btn">
                <a href="../admin/register_admin.php" class="option-btn">Register</a>
                <a href="../admin/admin_login.php" class="option-btn">Login</a>
            </div>
            <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a>
        </div>
    </section>
</header>

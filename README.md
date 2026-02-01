# 🛒 Junior Store - E-Commerce Graduation Project

![Project Status](https://img.shields.io/badge/Status-Completed-success)
![PHP](https://img.shields.io/badge/Backend-PHP-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)
![Frontend](https://img.shields.io/badge/Frontend-HTML%20%7C%20CSS%20%7C%20JS-yellow)

**Junior Store** is a comprehensive web-based e-commerce platform designed to provide a seamless online shopping experience. This project was developed as a graduation requirement for the **Faculty of Industrial Technology and Energy at Fayoum International Technology University (FITU)**.

The system features a robust **User Interface** for customers to browse and purchase products, and a powerful **Admin Dashboard** for complete store management.

---

## 🚀 Features

### 👤 User Module
* **Authentication:** Secure User Registration and Login system.
* **Product Browsing:** View latest products, filter by categories (Laptops, Mobiles, Watches, etc.).
* **Smart Search:** Real-time search functionality to find products easily.
* **Shopping Cart:** Add products to cart, update quantities, and calculate totals dynamically.
* **Wishlist:** Save favorite items for later.
* **Checkout System:** Secure checkout process with "Cash on Delivery" payment method.
* **Order Tracking:** Users can view their order history and status (Pending/Completed).
* **User Profile:** Manage account details (Name, Email, Password, Address).
* **Contact Us:** Direct messaging system to store administrators.

### 🛠 Admin Module (Control Panel)
* **Dashboard:** Real-time statistics (Total Pendings, Completed Orders, Number of Products, Users, and Admins).
* **Product Management:** Add, Update, and Delete products with images and details.
* **Category Management:** Create and manage product categories.
* **Order Management:** View all placed orders and update their status (Pending -> Completed).
* **User & Admin Management:** View and manage registered users and admin accounts.
* **Messages:** View and respond to customer inquiries.
* **Profile Update:** Secure admin credential management.

---

## 🛠️ Technologies Used

* **Frontend:** HTML5, CSS3, JavaScript.
* **Backend:** Native PHP.
* **Database:** MySQL / MariaDB.
* **Server Environment:** XAMPP (Apache HTTP Server).
* **Tools:** Visual Studio Code, phpMyAdmin.

---

## ⚙️ Installation & Setup

Follow these steps to run the project locally on your machine:

1.  **Install XAMPP:** Download and install [XAMPP](https://www.apachefriends.org/).
2.  **Start Servers:** Open XAMPP Control Panel and start **Apache** and **MySQL**.
3.  **Clone the Repo:**
    ```bash
    git clone https://github.com/abdoatef267/Graduation-Project.git
    ```
    *(Or download the ZIP file and extract it)*.
4.  **Move Files:** Move the project folder to `C:\xampp\htdocs\junior-store`.
5.  **Setup Database:**
    * Open your browser and go to `http://localhost/phpmyadmin`.
    * Create a new database named **`shop_db`**.
    * Click on **Import** tab.
    * Choose the file `shop_db.sql` provided in the project folder and click **Go**.
6.  **Configure Connection:**
    * Ensure `components/connect.php` matches your local settings (Default XAMPP settings are used):
    ```php
    $host = 'localhost';
    $db_name = 'shop_db';
    $user = 'root';
    $password = '';
    ```
7.  **Run the Project:**
    * **User Interface:** `http://localhost/junior-store/home.php`
    * **Admin Panel:** `http://localhost/junior-store/admin/admin_login.php`

---

## 🗄️ Database Structure

The project uses a relational database (`shop_db`) containing the following tables:
* `users`: Stores customer credentials.
* `admins`: Stores administrator credentials.
* `products`: Stores item details (price, image, category, quantity).
* `categories`: Stores product categories.
* `cart`: Manages items currently in user carts.
* `wishlist`: Manages user favorites.
* `orders`: Stores order details and shipping addresses.
* `messages`: Stores contact form submissions.

---

## 🙏 Acknowledgements

Special thanks to our supervisors for their guidance and support throughout this project:
* **Dr. Esraa El-hariri**
* **Dr. Asmaa Hashem**

---

&copy; 2026 Junior Store Project. All rights reserved.

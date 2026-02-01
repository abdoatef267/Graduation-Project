<?php
include '../components/connect.php';  // Include the database connection
session_start();

$message = ''; // Initialize message variable

if(isset($_POST['submit'])) {
   // Sanitize and validate input
   $name = mysqli_real_escape_string($conn, $_POST['admin_email']);
   $pass = mysqli_real_escape_string($conn, $_POST['admin_pass']);
   $pass = sha1($pass); // Using sha1 for password hashing (not recommended, consider using better hashing methods)

   // Query to select admin
   $query = "SELECT * FROM `admins` WHERE name = '$name' AND password = '$pass'";
   $result = mysqli_query($conn, $query);

   if(mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $_SESSION['admin_id'] = $row['id'];
      header('location: dashboard.php');
      exit;
   } else {
      $message = 'Incorrect username or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <style>
      body {
         background-color: #1a1a1a; /* Dark background color */
         color: #f5f5f5; /* Light text color */
         font-family: 'Cinzel', serif; /* Elegant fantasy font */
         background-image: url('path_to_your_fantasy_background_image.jpg'); /* Add a dark fantasy background image */
         background-size: cover;
         background-position: center;
         background-attachment: fixed;
         margin: 0;
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
      }

      .container {
         max-width: 400px;
         margin: 20px;
         padding: 20px;
         background: rgba(0, 0, 0, 0.8); /* Dark translucent background */
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
         border-radius: 10px;
         text-align: center;
      }

      .form-login-heading {
         margin-bottom: 30px;
         font-size: 2.5em;
         color: #ffcc00; /* Gold color for heading */
         text-shadow: 2px 2px 4px #000000;
      }

      .form-control {
         background-color: #333333; /* Dark input background */
         color: #ffffff; /* Light text color for inputs */
         border: none;
         border-radius: 5px;
         margin-bottom: 15px;
         padding: 10px;
         font-size: 1.1em;
         box-shadow: 0 0 5px rgba(255, 255, 255, 0.1);
         width: 100%;
         box-sizing: border-box;
      }

      .form-control::placeholder {
         color: #aaaaaa; /* Light grey placeholder text */
      }

      .btn-primary {
         background-color: #444444; /* Dark button background */
         border: none;
         border-radius: 5px;
         cursor: pointer;
         padding: 10px 20px;
         font-size: 1.1em;
         color: #ffcc00; /* Gold text color */
         box-shadow: 0 0 10px rgba(255, 204, 0, 0.5);
         transition: background-color 0.3s, transform 0.3s;
      }

      .btn-primary:hover {
         background-color: #555555; /* Slightly lighter button background on hover */
         transform: scale(1.05);
      }

      .btn-primary:active {
         background-color: #666666; /* Even lighter button background on active */
         transform: scale(1);
      }

      @keyframes fadeIn {
         0% { opacity: 0; }
         100% { opacity: 1; }
      }

      .container {
         animation: fadeIn 1s ease-in-out;
      }

      .message {
         background-color: #ff9999; /* Error message background color */
         color: #900; /* Error message text color */
         padding: 10px;
         margin-bottom: 15px;
         border-radius: 4px;
         display: flex;
         align-items: center;
         justify-content: space-between;
      }

      .message span {
         flex: 1;
      }

      .message i {
         cursor: pointer;
      }
   </style>
</head>
<body>

<?php if(!empty($message)): ?>
<div class="message">
   <span><?php echo $message; ?></span>
   <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
</div>
<?php endif; ?>

<div class="container">
    <form class="form-login" action="" method="post">
        <h2 class="form-login-heading">Admin Login</h2>
        <input class="form-control" type="text" name="admin_email" required placeholder="Enter your username" maxlength="20">
        <input class="form-control" type="password" name="admin_pass" required placeholder="Enter your password" maxlength="20"><br>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Log in</button>
    </form>
</div>

</body>
</html>

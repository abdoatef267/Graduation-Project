<?php
include 'components/connect.php';
session_start();
$message = [];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
if(isset($_POST['send'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $msg = mysqli_real_escape_string($conn, $_POST['msg']);

    // Check if message already exists
    $select_query = "SELECT * FROM `messages` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'";
    $select_result = mysqli_query($conn, $select_query);

    if(mysqli_num_rows($select_result) > 0){
        $message[] = 'already sent message!';
    } else {
        // Insert new message
        $insert_query = "INSERT INTO `messages` (user_id, name, email, number, message) VALUES ('$user_id', '$name', '$email', '$number', '$msg')";
        if(mysqli_query($conn, $insert_query)){
            $message[] = 'sent message successfully!';
        } else {
            $message[] = 'error sending message: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php'; ?>

<section class="contact">
    <form action="" method="post">
        <h3>Get in touch.</h3>
        <input type="text" name="name" placeholder="enter your name" required maxlength="20" class="box">
        <input type="email" name="email" placeholder="enter your email" required maxlength="50" class="box">
        <input type="number" name="number" min="0" max="9999999999" placeholder="enter your number" required onkeypress="if(this.value.length == 10) return false;" class="box">
        <textarea name="msg" class="box" placeholder="enter your message" cols="30" rows="10"></textarea>
        <input type="submit" value="send message" name="send" class="btn">
    </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>

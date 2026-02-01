<style>
.footer{
   background: url(footer-bg.jpg) no-repeat;
   background-size: cover;
   background-position: center;
}
.footer .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(25rem, 1fr));
   gap: 3rem;
}

.footer .box-container .box h3{
   color:var(--white);
   font-size: 2.5rem;
   padding-bottom: 2rem;
}

.footer .box-container .box a{
   color:var(--white);
   font-size: 1.5rem;
   padding-bottom: 1.5rem;
   display: block;
}

.footer .box-container .box a i{
   color:var(--tomato);
   padding-right: .5rem;
   transition: .2s linear;
}

.footer .box-container .box a:hover i{
   padding-right: 2rem;
}

.footer .credit{
   text-align: center;
   padding-top: 3rem;
   margin-top: 3rem;
   border-top: .1rem solid var(--light-white);
   font-size: 2rem;
   color:var(--white);
}

.footer .credit span{
   color:var(--tomato);
}
.active{
   color: var(--tomato)!important;
}
:root{
   --main-color:#247ba0;
   --black:#222;
   --white:#fff;
   --tomato:#fd5e42;
   --light-black:#777;
   --light-white:#fff9;
   --dark-bg:rgba(0,0,0,.7);
   --light-bg:#eee;
   --border:.1rem solid var(--main-color);
   --box-shadow:0 .5rem 1rem rgba(0,0,0,.1);
   --text-shadow:0 1.5rem 3rem rgba(0,0,0,.3);
}
</style>
<footer class="footer">

   <section class="grid">

      <div class="box">
         <h3 style="color:white;">Quick links</h3>
         <a href="home.php"> <i class="fas fa-angle-right"></i> Home</a>
         <a href="shop.php"> <i class="fas fa-angle-right"></i> Shop</a>
         <a href="contact.php"> <i class="fas fa-angle-right"></i> Contact</a>
      </div>

      <div class="box">
         <h3 style="color:white;">Extra links</h3>
         <a href="user_login.php"> <i class="fas fa-angle-right"></i> Login</a>
         <a href="user_register.php"> <i class="fas fa-angle-right"></i> Register</a>
         <a href="cart.php"> <i class="fas fa-angle-right"></i> Cart</a>
         <a href="orders.php"> <i class="fas fa-angle-right"></i> Orders</a>
      </div>

      <div class="box">
         <h3 style="color:white;">Contact Us.</h3>
         <a href=""><i class="fas fa-phone"></i> +20 155 412 7055</a>
         <a href=""><i class="fas fa-phone"></i> +20 155 412 7055</a>
         <a href="mailto:junior_store@gmail.com"><i class="fas fa-envelope"></i> junior_store@gmail.com</a>
         <a href="https://www.google.com/myplace"><i class="fas fa-map-marker-alt"></i> Fayoum, Egypt </a>
      </div>

      <div class="box">
         <h3 style="color:white;">Follow Us</h3>
         <a href="" target="_blank"><i class="fab fa-facebook-f"></i>facebook</a>
         <a href="" target="_blank"><i class="fab fa-twitter"></i>Twitter</a>
         <a href="" target="_blank"><i class="fab fa-instagram"></i>Instagram</a>
         <a href="" target="_blank"><i class="fab fa-linkedin"></i>Linkedin</a>
      </div>

   </section>

   
   <div class="credit"> designed by <span style="color:#247ba0;">Web Junior</span> | | </div>

</footer>
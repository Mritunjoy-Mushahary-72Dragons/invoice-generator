<?php
session_start();
if(isset($_SESSION['user_id']))
{
  // echo "sidhant";
  echo "<pre>";
    print_r($_SESSION);
    header("Location:invoice.php");
    exit();
}
else
{
  echo "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page | Online Training Tool</title>
  <link href="https://fonts.googleapis.com/css2?family=Muli&display=swap" rel="stylesheet" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"
    integrity="sha256-PF6MatZtiJ8/c9O9HQ8uSUXr++R9KBYu4gbNG5511WE=" crossorigin="anonymous" />
    <!-- <link href="./css/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="css/index.css" />
  <link rel="stylesheet" type="text/css" href="css/dialog.css" />
</head>

<body class="login-body">
  <div class="row" id="login_container">
    <div class="hbg"></div>
    <div class="forget-password">
      <div class="forget-title">Forget Password</div>
      <div class="forget-content">
        <p>Please enter your email address to receive a link for resetting your password.</p>
        <input type="text">
      </div>
      <div class="forget-button">
        <button class="cancel-forget">Cancel</button>
        <button class="send-forget">Send</button>
      </div>
    </div>
    <div class="column left" style="background-color: #141414;">
      <div class="login-bg-img">
        <img src="/img/72logo.png" alt="" />
      </div>
    </div>
    <div class="column right" style="background-image: linear-gradient(-135deg, #ffffff, #ad9440);">
      <form action="#" id="backlog-form" autocomplete="on">
        <h2 class="login-title">Online Training Tool</h2>
        <div class="login-img">
          <img src="/img/72logo.png" alt="" />
        </div>
        <div class="input-group">
          <span class="input-group-addon"><i class="fas fa-envelope"></i></span>
          <input type="text" class="login-input" placeholder="Please enter email address"  autocomplete="on" /><br />
        </div>
        <div class="input-group" style="position: relative;">
          <span class="input-group-addon"><i class="fas fa-key"></i></span>
          <input type="text" class="login-password" placeholder="Please enter password" />
          <span class="input-group-addin" style="margin: 0;top:35px;right:10px"><i class="fas fa-eye show"></i></span>
        </div>
        <a href="#">
          <p class="forgot-pass">Forgot password?</p>
        </a>
        <button type="button" class="login-btn">Login</button>
      </form>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="js/dialog.js"></script>
  <script>
    $(document).ready(function() {



      //forget password
      $('.forgot-pass').click(function() {
        $('.forget-password').css('display', 'block')
        $('.hbg').css('display', 'block')
      })
      $('.cancel-forget').click(function() {
        $('.forget-password').css('display', 'none')
        $('.hbg').css('display', 'none')
        $('.forget-content input').val('')
      })
      $('.send-forget').click(function() {
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        if ($('.forget-content input').val() == '') {
          var tablsss = new dialogBox(1, 'Warning', "Please Enter Your Email");
          tablsss.createDialog();
        } else if (!testEmail.test($('.forget-content input').val())) {
          var tablsss = new dialogBox(1, 'Warning', "Invalid Email Address");
          tablsss.createDialog();
        } else {
          console.log($('.forget-content input').val())
          $.ajax({
            type: 'post',
            url: 'login-api?api=forgot_pwd',
            data: {
              email: $('.forget-content input').val()
            },
            success: function(res) {
              console.log(res);
              return false;
            },
            error: function(data) {
              alert(
                "An error has occurred while entering email. Please try again"
              );
            }
          });
          $('.forget-password').css('display', 'none')
          $('.hbg').css('display', 'none')
          $('.forget-content input').val('')
        }
      })

      // input field validation
      $(".login-btn").click(function() {
      
        var mail = $(".login-input").val();
        var password = $(".login-password").val();
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

        if (mail == "") {
          var tablsss = new dialogBox(1, 'Warning', "Please enter your email");
          tablsss.createDialog();
          return
        } else if (!testEmail.test(mail)) {
          var tablsss = new dialogBox(1, 'Warning', "Invalid Email Address");
          tablsss.createDialog();
          return
        } else if (password == "") {
          var tablsss = new dialogBox(1, 'Warning', "Please enter your password");
          tablsss.createDialog();
          return
        } else {
          $.ajax({
            type: "POST",
            url: "login-api.php?api=login",
            // xhrFields: {withCredentials: true},
            // crossDomain: true,
            data: {
              username: mail,
              password: password
            },
            success: function(data) {
              console.log(data);
              if (data == 'true') {
                var tablsss = new dialogBox(1, 'Login', "login successful!");
                tablsss.createDialog();
                  window.location.href = 'invoice.php';
              } else {
                var tablsss = new dialogBox(1, 'Warning', "Invalid Username or Password!");
                tablsss.createDialog();
              }
            }
          });
        }
      });

      // Show / Hide Password
      $("body").on("click", ".show", function() {
        var el = $(this).parent();
        var prt = $(el).parent().find(".login-password");
        $(prt).css("-webkit-text-security", "unset");
        $(this).hide();
        // $(el).append(
        //   `<i class="fas fa-eye-slash hide"></i
        //         >`
        // );
        $(el).append("<i class=\"fas fa-eye-slash hide\"></i\n>");
      });
      $("body").on("click", ".hide", function() {
        var el = $(this).parent();
        var prt = $(el).parent().find(".login-password");
        $(prt).css("-webkit-text-security", "disc");
        $(this).hide();
        // $(el).append(`<i class="fas fa-eye show"></i
        //         >`);
        $(el).append("<i class=\"fas fa-eye show\"></i\n>");
      });
    });
  </script>
</body>

</html>
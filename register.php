<?php include('server.php');
  checkHttps();
  checkCookie();
  if(isset($_SESSION['email'])){
    header('location: home.php');
  }
  ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/jquery-3.3.1.min.js"></script>
</head>
<body>
 <?php include('header.php') ?>
  <div class="container-fluid mycontainer">
    <div class="row myrow">
      <?php include('navbar.php') ?>
      <div class="col-md-4 mydivform">
        <form method="post" onsubmit="return check_psw()">
          <?php include('errors.php') ?>
          <div class="form-group">
            <label for="email">Insert Email:</label>
            <input type="email" class="form-control" name="email" id="email" title="insert email" placeholder="Insert Email (it will be your username)" required />
          </div>
          <div class="form-group">
            <label for="password">Insert Password:</label>
            <input type="password" class="form-control" name="password" id="password" minlength="3" placeholder="Insert password please" required />
          </div>
          <div class="form-group">
            <button id="register" type="submit" class="btn btn-primary" name="register_user">Register</button>
          </div>
          <span id="error"></span>
        </form>
      </div>  
    </div>
  </div>
  <?php include('footer.php') ?> 
</body>
<script type="text/javascript">

  function check_psw(){
    var psw = $("#password").val();
    var regexp_psw = new RegExp(/^[A-Za-z0-9]+$/);
    if(regexp_psw.test(psw) || psw.length < 3){
      $("#error").html("The password must contain at least three characters, one of which is not alphanumeric");
      $('#error').fadeIn().delay(2000).fadeOut();
      return false;
    }
    return true;
  }
  
</script>
</html>
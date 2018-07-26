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
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <?php include('header.php') ?>
  <div class="container-fluid mycontainer">
    <div class="row myrow">
      <?php include('navbar.php') ?>
      <div class="col-md-4 mydivform">
        <?php include('errors.php') ?>
        <form method="post" action="login.php">
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Insert your email please" required />
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Insert password please" required />
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary" name="login_user" id="login_user">Login</button>
          </div>
        </form>
      </div>  
    </div>
  </div>
  <?php include('footer.php') ?>  
</body>
<script src="js/jquery-3.3.1.min.js">
</script>
</html>
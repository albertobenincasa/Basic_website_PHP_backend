<?php
ob_start();
session_start();
$errors = array();

$GLOBALS['maxcols'] = 9;
$GLOBALS['maxrows'] = 7;
$GLOBALS['maxlength'] = 4;

if(isset($_POST['register_user'])){
	$db = dbConnection();
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password = $_POST['password'];

	if(empty($email)){ array_push($errors, "Email is required"); }

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ array_push($errors, "Email is not valid");	}

	if(empty($password)){ array_push($errors, "Password required"); }

	if(!preg_match('/[^A-Za-z0-9]+/', $password) || strlen( $password) < 3){ array_push($errors, "The password must contain at least three characters, one of which is not alphanumeric"); }

	$check_user_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
	$result = mysqli_query($db, $check_user_query);
	$user = mysqli_fetch_assoc($result);
	if($user){ 
		array_push($errors, "email already registered");
		return false;
	}

	if(count($errors) == 0){
		try {
			$password = md5($password);
			mysqli_autocommit($db, false);
			$query = "SELECT * FROM users FOR UPDATE OF users";
			$query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

			if(!mysqli_query($db, $query)){
				throw new Exception("Error Processing Request", 1);
			}
			if(!mysqli_commit($db)){
				throw Exception("Commit failed");
			}

			$_SESSION['email'] = $email;
			$_SESSION['time'] = time();
			setcookie("user", $email, time() + 120, "/");
			$_SESSION['success'] = "You are now logged in";
			mysqli_autocommit($db, true);
			mysqli_close($db);
			header('location: home.php');

		} catch (Exception $e) {
		mysqli_rollback($db);
		echo "Rollback ".$e->getMessage();
		mysqli_autocommit($db, true);
		mysqli_close($db);
		return false;
		}
	}
	mysqli_close($db);
}



// login user
if (isset($_POST['login_user'])) {
	$db = dbConnection();
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = $_POST['password'];

  if (empty($password)) { array_push($errors, "Password is required"); }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['email'] = $email;
  	  $_SESSION['time'] = time();
  	  //ini_set('session.gc_maxlifetime', 10);
  	  setcookie("user", $email, time() + (86400 * 30), "/");
  	  mysqli_close($db);
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: home.php');
  	}else {
  		array_push($errors, "Wrong email/password combination");
  	}
  }
  mysqli_close($db);
}

if(isset($_GET['getpoints'])){
	$db = dbConnection();
	$query = "SELECT * FROM gameboard";
	$result = mysqli_query($db, $query);
	$points = array();

	if ($result->num_rows > 0) {
    // output data of each row
    	while($row = mysqli_fetch_array($result)) {
        if(array_key_exists($row['user'], $points)){
        	array_push($points[$row['user']], $row['x0'], $row['y0'], $row['x1'], $row['y1']);
        } else {
        	$points[$row['user']] = [$row['x0'], $row['y0'], $row['x1'], $row['y1']];
    	}
        }
	} else {
		mysqli_close($db);
    	echo "0 results";
	}
		//insert table
		mysqli_close($db);
		echo json_encode($points);
}

if(isset($_POST['postfunctions'])){
	$function = $_POST['postfunctions'];
	$string = "";

	switch($function){
		case 'deletepiece':
			if(!checkSession()){echo "-1"; exit();}
			$db = dbConnection();
			$user = $_SESSION['email'];
			try {
				mysqli_autocommit($db, false);
				mysqli_query($db, "SELECT * FROM gameboard FOR UPDATE OF gameboard");

				$query = "DELETE FROM gameboard WHERE user='$user' ORDER BY insert_time DESC LIMIT 1";
				if(!mysqli_query($db, $query)){
					throw new Exception("Error Processing Request", 3);
				}
				if(!mysqli_commit($db)){
					throw new Exception("Error Processing Request", 4);
				}
				mysqli_autocommit($db, true);
				mysqli_close($db);
				$string = "piece correctly deleted";
			} catch (Exception $e) {
				mysqli_rollback($db);
				echo "Rollback ".$e->getMessage();
				mysqli_autocommit($db, true);
				mysqli_close($db);
				return false;
			}

			break;

		case 'insertpiece':
			if(!checkSession()){echo "-1"; exit();}
			$db = dbConnection();
			$data = $_POST['arguments'];

			$y0 = $data["y0"];
			$x0 = $data["x0"];
			$y1 = $data["y1"];
			$x1 = $data["x1"];

			$len = 0;

			if ($x0 != $x1) {
				$len = $x1 - $x0 +1;
			} else {
				$len = $y1 - $y0 +1;
			}

			if($len != $GLOBALS['maxlength']){
				echo "piece length don't admitted.";
				mysqli_close($db);
				return false;
			}

			if($x0 >= $maxcols || $x1 >= $maxcols || $y0 >= $maxrows || $y1 >= $maxrows || $x0 < 0 || $x1 < 0 || $y0 < 0 || $y1 < 0){
				echo "piece coordinates don't admitted.";
				mysqli_close($db);
				return false;
			}

			//sleep(3);

			try {
				mysqli_autocommit($db, false);
				mysqli_query($db, "SELECT * FROM gameboard FOR UPDATE OF gameboard");

				if($x0 == $x1){
				$query = "SELECT * FROM gameboard WHERE x0=x1 AND ((x0>=$x0-1 AND x0<=$x0+1) AND ((y0>=$y0-1 AND y0<=$y1+1) OR (y1>=$y0-1 AND y1<=$y1+1))) OR y0=y1 AND ((y0>=$y0-1 AND y0<=$y1+1) AND ((x0>=$x0-1 AND x0<=$x0+1) OR (x1>=$x1-1 AND x1<=$x1+1))) LIMIT 1 ";

				$result = mysqli_query($db, $query);
				$do_not_insert = mysqli_fetch_assoc($result);

				if($do_not_insert){
					echo "position don't admitted";
					mysqli_close($db);
					return false;
				} else {
					$user = $_SESSION['email'];
					$time = time();
					$sqltime = date("Y-m-d H:i:s", $time);
		
					$query = "INSERT INTO gameboard (user, insert_time, x0, y0, x1, y1) VALUES ('$user', '$sqltime', '$x0', '$y0', '$x1', '$y1')";
					mysqli_query($db, $query);
					mysqli_commit($db);
					mysqli_autocommit($db, true);
					mysqli_close($db);
					$string = "piece correctly inserted";
				}
			} else if($y0 == $y1){
				$query = "SELECT * FROM gameboard WHERE y0=y1 AND ((y0>=$y0-1 AND y0<=$y0+1) AND ((x0>=$x0-1 AND x0<=$x1+1) OR (x1>=$x0-1 AND x1<=$x1+1))) OR x0=x1 AND ((x0>=$x0-1 AND x0<=$x1+1) AND ((y0>=$y0-1 AND y0<=$y0+1) OR (y1>=$y1-1 AND y1<=$y1+1))) LIMIT 1 ";

				$result = mysqli_query($db, $query);
				$do_not_insert = mysqli_fetch_assoc($result);

				if($do_not_insert){
					echo "position don't admitted";
					mysqli_close($db);
					return false;
				} else {
					$user = $_SESSION['email'];
					$time = time();
					$sqltime = date("Y-m-d H:i:s", $time);
					$query = "INSERT INTO gameboard (user, insert_time, x0, y0, x1, y1) VALUES ('$user', '$sqltime', '$x0', '$y0', '$x1', '$y1')";

					mysqli_query($db, $query);
					mysqli_commit($db);
					mysqli_autocommit($db, true);
					mysqli_close($db);
					$string = "piece correctly inserted";
				}
			} else {
				echo "false";
				return false;
			}
				
			} catch (Exception $e) {
				mysqli_rollback($db);
				echo "Rollback ".$e->getMessage();
				mysqli_autocommit($db, true);
				mysqli_close($db);
				echo "false";
				return false;
			}
			break;

		case 'user_session':
			if(!checkSession()){echo "-1"; exit();}
			break;
				
		default:
			# code...
			break;
	}

	echo "$string";
}

function checkHttps(){
    if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] == 'off') {
        header("location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit();
    }
}

function checkSession(){
  $t=time();
  $diff=0;
  $new=false;
  if (isset($_SESSION['time'])){
    $t0=$_SESSION['time'];
    $diff=($t-$t0);  // inactivity period
  } else {
    $new=true;
  }
  if ($new || ($diff > 120)) {

  	$_SESSION=array();

    if (ini_get("session.use_cookies")) {
    	$params = session_get_cookie_params();
      	setcookie(session_name(), '', time() - 3600*24, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
    return false;
  } else {
    $_SESSION['time']=time();
  }
  return true;
}

function checkCookie(){
	setcookie("test_cookie", "test", time() + 3600, '/');
	if(count($_COOKIE) == 0){
		echo "Enable cookies to navigate this site";
		exit();
	}
}

function dbConnection(){
	$db = mysqli_connect("localhost", "s251415", "toloeles");
	if(mysqli_connect_errno()){
		die("Internal server error" . mysqli_connect_errno());
	}
	if(!mysqli_select_db($db, "s251415")){
		die("Selection of DB error");
	}

	return $db;
}

?>



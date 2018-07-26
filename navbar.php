<div class="col-md-2 mycol">  
  <nav class="sidebar">

    <!-- Links -->
    <ul class="navbar-nav">
    	<?php 
    		if(isset($_SESSION['email'])){
      		echo "
      			<li class='nav-item'>
      				<a id='insertpiece' class='btn btn-primary' href='#'>Insert</a>
      			</li>
      			<li class='nav-item'>
      				<a id='removepiece' class='btn btn-primary' href='#'>Remove</a>
      			</li>
            <li class='nav-item'>
              <a id='deletepiece' class='btn btn-primary' href='#'>Delete</a>
            </li>
            <li class='nav-item'>
              <a class='btn btn-primary' href='logout.php'>Logout</a>
            </li>
      		";
    		} else {
    			echo "
    				<li class='nav-item'>
      		  		<a class='btn btn-primary' href='login.php'>Login</a>
      			</li>
      			<li class='nav-item'>
      		  		<a class='btn btn-primary' href='register.php'>Signup</a>
      			</li>
      		";
    		}
    	?>
    </ul>
  </nav>
</div>
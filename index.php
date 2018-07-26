<?php include('server.php');
	checkCookie();
	if(isset($_SESSION['email'])){
		header('location: home.php');
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Index</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="js/jquery-3.3.1.min.js"></script>
	</head>
	<body>
		<?php include('header.php') ?>
		<noscript>
    	Javascript is not enabled. Please, enable it!
		</noscript>
		<div class="container-fluid mycontainer">
			<div class="row myrow">
				<?php include('navbar.php') ?>
				<div class="container-fluid col-md-10">
				<?php 
					echo "<table class='mytable'>";

					for($i = 0; $i < $GLOBALS['maxrows']; $i++){
        		echo "<tr name='row'>";
        		for ($j = 0; $j < $GLOBALS['maxcols']; $j++) {
            	echo "<td class='no-checked' id='$i-$j'></td>";      
        		}
        		echo "</tr>";
      		}
      		echo "</table>";
				?>
				</div>
			</div>
		</div>
		<?php include('footer.php') ?>
		
		
	</body>
	<script type="text/javascript">

		$.ajax({
			url: "server.php",
			data: "getpoints",
			type: "GET"
		}).done(function(obj){
			$.each(JSON.parse(obj), function(idx, obj){

				for (var i = 0; i < obj.length; i++) {
					x0 = obj[i];
					y0 = obj[++i];
					x1 = obj[++i];
					y1 = obj[++i];

					if(x1 == x0){
						for(j=y0; j<=y1; j++){
							$('#'+(j+'-'+x0)).removeClass('no-checked');
							$('#'+(j+'-'+x0)).addClass('no-user-checked');
						}
					} else if(y0==y1){
						for(j=x0; j<=x1; j++){
							$('#'+(y0+'-'+j)).removeClass('no-checked');
	    				$('#'+(y0+'-'+j)).addClass('no-user-checked');
	    			}
					}
				}
			});
		});
	</script>
</html>
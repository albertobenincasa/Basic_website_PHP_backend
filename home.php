<?php 
	include('server.php');
	if(!isset($_SESSION['email'])){header("Location: index.php");}
	checkCookie();
	checkSession();	
	checkHttps();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
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
				<?php include('errors.php') ?>
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
				<span id="error"></span>
				</div>
			</div>
			<input type="hidden" value="<?php if(isset($_SESSION['email'])){ echo $_SESSION['email'];} ?>" id="session">	
		</div>
		<?php include('footer.php') ?>
		
		
	</body>
	<script type="text/javascript">
		$('.no-checked').click(function(){

			$.ajax({
				url: "server.php",
				data: {postfunctions: "user_session"},
				type: "POST"
			}).done(function(data){
				if(data == "-1"){
					alert("session expired");
					window.location.href = "index.php";
				}
			});

			sessName = $("#session").val();
			var length = <?php echo $GLOBALS['maxlength']; ?>;

			if(sessName!=''){
  				if(($('.grey').length) < 1 && length == 1){
  					$(this).toggleClass('grey');
  				} else if (($('.grey').length) < 2 && length != 1){
					$(this).toggleClass('grey');
				}

    			if(($('.grey').length) > 1){
    				y0 = $('.grey').first().attr('id').split('-')[0];
    				x0 = $('.grey').first().attr('id').split('-')[1];
    				y1 = $('.grey').last().attr('id').split('-')[0];
    				x1 = $('.grey').last().attr('id').split('-')[1];

    				if(x1 == x0){
    				//verticale
    					for(i=y0; i<y1; i++){
    						if($('#'+(i+'-'+x0)).hasClass('user-checked') || $('#'+(i+'-'+x0)).hasClass('no-user-checked')){
    							//stampa errore
    							$("#error").html("your piece cannot pass through other pieces");
    							$('#error').fadeIn().delay(2000).fadeOut();
    							$('.grey').removeClass('grey');
    							return false;
    						} else {
    							$('#'+(i+'-'+x0)).addClass('grey');
    						}
    					}
    				} else if(y1 == y0){
    					//orizzontale
    					for(i=x0; i<x1; i++){
    						if($('#'+(y0+'-'+i)).hasClass('user-checked') || $('#'+(y0+'-'+i)).hasClass('no-user-checked')){
    							//stampa errore
    							$("#error").html("your piece cannot pass through other pieces");
    							$('#error').fadeIn().delay(2000).fadeOut();
    							$('.grey').removeClass('grey');
    							return false;
    						} else {
    							$('#'+(y0+'-'+i)).addClass('grey');
    						}  						
    					}
    				} else {
    					//metti errore
    					$("#error").html("your piece has to be only horizontal or vertical");
    					$('#error').fadeIn().delay(2000).fadeOut();
    					$('.grey').removeClass('grey');
    				}
    			}
    		}
  		});

		$("#removepiece").click(function(){
			$.ajax({
				url: "server.php",
				data: {postfunctions: "user_session"},
				type: "POST"
			}).done(function(data){
				if(data == "-1"){
					alert("session expired");
					window.location.href = "index.php";
				}
			});
			$(".grey").removeClass('grey');
		});

		$.ajax({
			url: "server.php",
			data: "getpoints",
			type: "GET"
		}).done(function(obj){
			 sessName = $("#session").val();
			$.each(JSON.parse(obj), function(idx, obj){

				if(idx == sessName){
					for (var i = 0; i < obj.length; i++) {
						x0 = obj[i];
						y0 = obj[++i];
						x1 = obj[++i];
						y1 = obj[++i];

						if(x1 == x0){
							for(j=y0; j<=y1; j++){
								$('#'+(j+'-'+x0)).removeClass('no-checked');
								$('#'+(j+'-'+x0)).addClass('user-checked');
							}
						} else if(y0==y1){
							for(j=x0; j<=x1; j++){
								$('#'+(y0+'-'+j)).removeClass('no-checked');
    						$('#'+(y0+'-'+j)).addClass('user-checked');
    					}
						}
					}
				} else {
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
				}
			});
		});

		$("#insertpiece").click(function(){
			var length = <?php echo $GLOBALS['maxlength']; ?>
		
			if(($(".grey").length) != length){
				$("#error").html("length admitted:"+length);
    			$('#error').fadeIn().delay(2000).fadeOut();
    			$('.grey').removeClass('grey');
			} else{
				var points = {};
				points["y0"] = $('.grey').first().attr('id').split('-')[0];
				points["x0"] = $('.grey').first().attr('id').split('-')[1];
				points["y1"] = $('.grey').last().attr('id').split('-')[0];
				points["x1"] = $('.grey').last().attr('id').split('-')[1];

				$.ajax({
					url: "server.php",
					data: { postfunctions: 'insertpiece', arguments: points },
					type: "POST"
				}).done(function(data){
					if(data == "-1"){
						alert("session expired");
						window.location.href = "index.php";
					} else {
						alert(data);
						location.reload(true);									
					}
				});
			}
		});

		$("#deletepiece").click(function(){
			if($(".user-checked").length != 0){
				$.ajax({
					url: "server.php",
					data: {postfunctions: "deletepiece"},
					type: "POST"
				}).done(function(data){
					if(data == "-1"){
						alert("session expired");
						window.location.href = "index.php";
					} else {
						alert(data);
						location.reload(true);
					}
				});
			} else {
				$("#error").html("you have no pieces to remove");
    			$('#error').fadeIn().delay(2000).fadeOut();
			}
		});
	

	</script>
</html>
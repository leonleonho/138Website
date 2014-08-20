<?php
	session_start();
	if(!isset($_SESSION['id'])) {
		header('Location: login.html');

	}
	require('../php/config.php');
	// Check connection
	$con=mysqli_connect("$host","$user","$password","$db");
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$request = "SELECT COUNT(id) FROM post";
	$result = mysqli_query($con,$request);
	while($row = mysqli_fetch_array($result)) {
		$numOfRows = $row;
	}
	$numOfRows = $numOfRows[0];
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
<title>Control Panel</title>
<link rel="stylesheet" href="style.css"/>
<script src="//connect.facebook.net/en_US/sdk.js"></script>
<script src="../js/jquery.min.js"></script>
	<link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="../css/froala_editor.min.css" rel="stylesheet" type="text/css">
	<link href="../css/froala_reset.min.css" rel="stylesheet" type="text/css">
	<script src="../js/beautify/beautify-html.js"></script>
	<script src="../js/froala_editor.min.js"></script>
	<script src="../js/plugins/block_styles.min.js"></script>
	<!--[if lt IE 9]>
	<script src="../js/froala_editor_ie8.min.js"></script>
	<![endif]-->
	<script src="js/main.js"></script>
</head>
<script>
	var ACCESS_TOKEN = "524905380971501|vwvIt1ualZz_1V5DX17el6NJPe0";
	FB.init({
	    version: 'v2.0',
	    appId: 524905380971501
	});

</script>
<body>
	<div id = "header">
		<div id = "logo">
			<img src = "http://www.scouts.ca/brandcentre/resources/logo/scouts-logo-notagline-web.png" alt = "logo" height = "144px"  width = "180px">
		</div>
		<div id = "title">138 Scout Group Control Panel</div>
	<br style="clear:both;"/><hr />
	</div>
		<div id = "menu">
			<ul>
				<li id = "menuItems" onclick = "showHide('create')"><a href = "#">Create Post</a></li>
				<li id = "menuItems" onclick = "showHide('edit')"><a href = "#">Edit Post</a></li>
				<li id = "menuItems" onclick = "showHide('calender')"><a href = "#">Calender</a></li>
				<li id = "menuItems" ><a href = "php/logoff.php">Log Off</a></li>
			</ul>
		</div>
		<div id = "content">
			<div id = "create"><h1>Create Post</h1>
				<div id = "error"></div>
				<form name="input" action="#" method="post">
					Header:<br />
					<input type = "text" name = "header" id = "postHeader"><br />
					Add facebook photo album(copy and paste the url of album)<br />
					<input type = "text" name = "album" id = "album"><br />
					Additional Details<br />
					<textarea rows = "50" cols = "100" name = "description" id = "description"></textarea><br />
				</form>	
				<button value = "Create" onclick = "submit()">Create Post</button>
				<button value = "Preview Post" onclick = "preview()">Preview Post</button>
				<h1>Preview</h1>
				<div id = "preview"></div>
			</div>	
			
			<div id = "edit"><h1>Edit Post</h1> 
				<div id = "editPosts">
				</div>
				<div id = "pagination">
				<?php
					$numOfPages = $numOfRows / 25;
					for ($i = 1; $i <= $numOfPages; $i++) {
						echo "<a href = '#' onclick = 'grabPosts($i, 25)'>$i</a> ";
					}
				?>
				</div>
			</div>
			<div id = "calender"><h1>Calender</h1>
				<iframe src="https://www.google.com/calendar/embed?src=pccrovers.com_pojeic2sd1ojijt7ohop7gt338%40group.calendar.google.com&ctz=America/Vancouver" style="border: 0" width="80%" height="600" frameborder="0" scrolling="no">
				</iframe>
			</div>
		</div>

	<script>
		$(function(){
			$('#description').editable({
				inlineMode: false
			})
		});
	</script>

</body>

</html>
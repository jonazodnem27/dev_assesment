<!DOCTYPE html>
<html>
<head>
	<title><?=$title;?></title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">

*{
	font-family: 'Montserrat';
}

body{
	background: linear-gradient(to left, rgb(44, 62, 80), rgb(52, 152, 219))
}
	.nav-tabs>li{
		width: 50%
	}

	.nav-tabs>li.active a{
		background: #0095da;
		border: 0;
		color: white;
		font-weight: bold
	}

	.nav-tabs{
		border:0;
	}

	.nav-tabs>li>a{
		border-radius: 0px;
		background: #435159;
		border: 0;
		text-align: center;
		color: #9cb2a7;
		font-size: 15px;
		padding: 15px;
	}

	.nav-tabs>li>a:hover, .nav-tabs>li.active a:hover{
		border:0;
	}

	.nav-tavs>li.active{
		background: #0095da
	}

	.tab-content{
		color: #435159
	}

	.form-control{
		border-radius: 0px !important;
	}

	label.error{
		background: #ff6868;
		color: white;
		text-transform: uppercase;
		margin: 10px 0px;
		padding: 2px;
	}
</style>
</head>
<body>
<br><br><br>
<?php include('main2.php');?>

</body>
</html>
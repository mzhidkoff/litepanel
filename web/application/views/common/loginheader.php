<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo $description ?>">
	<meta name="keywords" content="<?php echo $keywords ?>">
	<meta name="generator" content="WEBEV.RU">
	
	<title><?php echo $title ?> | <?php echo $description ?></title>
    
    <link href="/application/public/css/main.css" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	
	<script src="/application/public/js/jquery.min.js"></script>
	<script src="/application/public/js/jquery.form.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="/application/public/js/main.js"></script>

	<style>
		body {
			padding-top: 72px;
			padding-bottom: 40px;
			background-color: #F5F5F5;
		}
	</style>
</head>
<body>
	<!-- Powered by LitePanel -->
	<div id="content" class="container">
		<?php if(isset($error)): ?><div class="alert alert-danger"><strong>Ошибка!</strong> <?php echo $error ?></div><?php endif; ?> 
		<?php if(isset($warning)): ?><div class="alert alert-warning"><strong>Внимание!</strong> <?php echo $warning ?></div><?php endif; ?> 
		<?php if(isset($success)): ?><div class="alert alert-success"><strong>Выполнено!</strong> <?php echo $success ?></div><?php endif; ?> 

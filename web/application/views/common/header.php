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
	<script src="/application/public/js/jquery.flot.min.js"></script>
	<script src="/application/public/js/jquery.flot.time.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="/application/public/js/main.js"></script>
	
	<!-- Ionicons website: https://ionicons.com/ -->
	<link href="https://unpkg.com/ionicons@4.5.10-1/dist/css/ionicons.min.css" rel="stylesheet">
</head>
<body>
	<!-- Powered by LitePanel -->
	<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
	  <div class="container">
		  <a class="navbar-brand" href="/"><?php echo $title ?></a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse" id="navbarHeader">
			<ul class="navbar-nav mr-auto">
			  <?php if($logged == true): ?>
			  <li class="nav-item<?php if($activesection == "servers"): ?> active<?php endif; ?>">
				<a class="nav-link" href="/servers/index">Сервера</a>
			  </li>
			  <li class="nav-item<?php if($activesection == "tickets"): ?> active<?php endif; ?>">
				<a class="nav-link" href="/tickets/index">Поддержка</a>
			  </li>
			  <?php endif; ?>
			  <li class="nav-item<?php if($activesection == "news"): ?> active<?php endif; ?>">
				<a class="nav-link" href="/news/index">Новости<?php if($user_news > 0): ?> <span class="badge badge-pill badge-secondary">+<?php echo $user_news ?></span><?php endif; ?></a>
			  </li>
			</ul>
			<?php if($logged == true): ?>
			<ul class="navbar-nav">
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarHeaderDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="icon ion-md-card"></i> <?php echo $user_balance ?> руб
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarHeaderDropdown">
				  <a class="dropdown-item" href="/">Профиль</a>
				  <a class="dropdown-item" href="/account/pay">Пополнить</a>
				  <div class="dropdown-divider"></div>
				  <a class="dropdown-item" href="/account/invoices">История пополнений</a>
				</div>
			  </li>
			</ul>
			<?php else: ?>
			<ul class="navbar-nav"><li class="nav-item">
				<a class="nav-link" href="/account/login">Войти <i class="icon ion-md-log-in"></i></a>
			</li></ul>
			<?php endif; ?>
		  </div>
	  </div>
	</nav>
	
    <div class="container">
    	<div class="row">
			<?php if($logged == true): ?>
    		<div class="col-lg-3">
    			<?php if($user_access_level >= 2): ?>
    			<div class="text-center pb-2">
					<div class="btn-group btn-group-sm">
						<button type="button" class="btn btn-light<?php if($activesection != "admin"): ?> active<?php endif; ?>" id="userNavModeBtn" onClick="setNavMode('user')">User</button>
						<button type="button" class="btn btn-light<?php if($activesection == "admin"): ?> active<?php endif; ?>" id="administratorNavModeBtn" onClick="setNavMode('administrator')">Administrator</button>
					</div>
    			</div>
    			<?php endif; ?> 
    			<div id="userNavMode"<?php if($activesection == "admin"): ?> style="display: none;"<?php endif; ?>>
					<h4 class="text-muted mt-3">Управление</h4>
					<div class="list-group">
					  <a href="/servers/index" class="list-group-item list-group-item-action<?php if($activesection == "servers" && $activeitem == "index"): ?> active<?php endif; ?>"><i class="icon ion-md-cloud"></i> Мои сервера</a>
					  <a href="/servers/order" class="list-group-item list-group-item-action<?php if($activesection == "servers" && $activeitem == "order"): ?> active<?php endif; ?>"><i class="icon ion-md-add"></i> Заказать сервер</a>
					</div>
					<h4 class="text-muted mt-3">Поддержка</h4>
					<div class="list-group">
					  <a href="/tickets/index" class="list-group-item list-group-item-action<?php if($activesection == "tickets" && $activeitem == "index"): ?> active<?php endif; ?>"><i class="icon ion-md-headset"></i> Мои запросы</a>
					  <a href="/tickets/create" class="list-group-item list-group-item-action<?php if($activesection == "tickets" && $activeitem == "create"): ?> active<?php endif; ?>"><i class="icon ion-md-add"></i> Тикет-запрос</a>
					</div>
					<h4 class="text-muted mt-3">Аккаунт</h4>
					<div class="list-group">
					  <a href="/account/pay" class="list-group-item list-group-item-action<?php if($activesection == "account" && $activeitem == "pay"): ?> active<?php endif; ?>"><i class="icon ion-md-card"></i> Пополнить баланс</a>
					  <a href="/account/invoices" class="list-group-item list-group-item-action<?php if($activesection == "account" && $activeitem == "invoices"): ?> active<?php endif; ?>"><i class="icon ion-md-list"></i> История платежей</a>
					  <a href="/account/edit" class="list-group-item list-group-item-action<?php if($activesection == "account" && $activeitem == "edit"): ?> active<?php endif; ?>"><i class="icon ion-md-settings"></i> Настройки</a>
					  <a href="/account/logout" class="list-group-item list-group-item-action"><i class="icon ion-md-log-out"></i> Выход</a>
					</div>
				</div>
				<?php if($user_access_level >= 2): ?>
    			<div id="administratorNavMode"<?php if($activesection != "admin"): ?> style="display: none;"<?php endif; ?>>
    				<?php if($user_access_level >= 2): ?> 
					<h4 class="text-muted mt-3">Поддержка</h4>
					<div class="list-group">
					  <a href="/admin/servers/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "servers"): ?> active<?php endif; ?>"><i class="icon ion-md-cloud"></i> Все сервера</a>
					  <a href="/admin/tickets/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "tickets"): ?> active<?php endif; ?>"><i class="icon ion-md-headset"></i> Все запросы</a>
					  <a href="/admin/users/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "users"): ?> active<?php endif; ?>"><i class="icon ion-md-people"></i> Все пользователи</a>
					  <a href="/admin/invoices/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "invoices"): ?> active<?php endif; ?>"><i class="icon ion-md-list"></i> Все счета</a>
					  <a href="/news/create" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "news"): ?> active<?php endif; ?>"><i class="icon ion-md-add"></i> Создать новость</a>
					</div>
					<?php endif; ?> 
					<?php if($user_access_level >= 3): ?> 
					<h4 class="text-muted mt-3">Управление</h4>
					<div class="list-group">
					  <a href="/admin/games/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "games"): ?> active<?php endif; ?>"><i class="icon ion-logo-game-controller-b"></i> Все игры</a>
					  <a href="/admin/locations/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "locations"): ?> active<?php endif; ?>"><i class="icon ion-md-globe"></i> Все локации</a>
					</div>
					<?php endif; ?> 
				</div>
				<?php endif; ?>
    		</div>
			<?php endif; ?>
    		<div id="content" class="col-lg-9 pt-2">
				<?php if(isset($error)): ?><div class="alert alert-danger"><strong>Ошибка!</strong> <?php echo $error ?></div><?php endif; ?> 
				<?php if(isset($warning)): ?><div class="alert alert-warning"><strong>Внимание!</strong> <?php echo $warning ?></div><?php endif; ?> 
				<?php if(isset($success)): ?><div class="alert alert-success"><strong>Выполнено!</strong> <?php echo $success ?></div><?php endif; ?> 

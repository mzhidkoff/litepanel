<?php
$mysqlExists = false;
if(function_exists("mysqli_connect"))
	$mysqlExists = true;

$ssh2Exists = false;
if(function_exists("ssh2_connect"))
	$ssh2Exists = true;

$gdExists = false;
if(function_exists("gd_info"))
	$gdExists = true;

$modRewriteExists = true;
?>
<html>
<head>
	<meta charset="utf8">
	<title>LitePanel - Проверка системы</title>
	<style>
	body {
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		font-size: 14px;
	}
	.block {
		width: 75%;
		margin: 0 auto;
	}
	.table {
		border-collapse: collapse;
		border-spacing: 0;
		width: 100%;
	}
	.table th,
	.table td {
		padding: 8px;
		line-height: 20px;
		text-align: left;
		border-top: 1px solid #dddddd;
	}
	.table th {
		font-weight: bold;
	}
	.table tr.success td {
		background-color: #dff0d8;
	}
	.table tr.error td {
		background-color: #f2dede;
	}
	.table tr.warning td {
		background-color: #fcf8e3;
	}
	.table tr.info td {
		background-color: #d9edf7;
	}
	</style>
</head>
<body>
<div class="block">
<h1>LitePanel - проверка системы</h1>
<table class="table">
	<tr>
		<th>Информация о системе</th>
		<td><?php echo php_uname() ?></td>
	</tr>
	<tr class="<?php if($mysqlExists): ?>success<?php else: ?>error<?php endif; ?>">
		<th>Наличие php_mysql</th>
		<td><?php if($mysqlExists): ?>Установлен<?php else: ?><i>Для установки используйте "apt-get install php5-mysql"</i><?php endif; ?></td>
	</tr>
	<tr class="<?php if($ssh2Exists): ?>success<?php else: ?>error<?php endif; ?>">
		<th>Наличие php_ssh2</th>
		<td><?php if($ssh2Exists): ?>Установлен<?php else: ?><i>Для установки используйте "apt-get install libssh2-php"</i><?php endif; ?></td>
	</tr>
	<tr class="<?php if($gdExists): ?>success<?php else: ?>error<?php endif; ?>">
		<th>Наличие php_gd</th>
		<td><?php if($gdExists): ?>Установлен<?php else: ?><i>Для установки используйте "apt-get install php5-gd"</i><?php endif; ?></td>
	</tr>
	<tr class="<?php if($modRewriteExists): ?>success<?php else: ?>error<?php endif; ?>">
		<th>Наличие mod_rewrite</th>
		<td><?php if($modRewriteExists): ?>Установлен<?php else: ?><i>Для установки используйте "a2enmod rewrite"</i><?php endif; ?></td>
	</tr>
</table>
</div>
</body>
</html>

<?php
$config = array(
	// Название компании.
	// Пример: ExampleHost
	'title'			=>		'LitePanel',
	
	// Описание компании (meta description).
	// Пример: Game Hosting ExampleHost
	'description'	=>		'Game Hosting',
	
	// Ключевые слова (meta keywords).
	// Пример: game hosting, game servers
	'keywords'		=>		'game hosting, game servers',
	
	// URL панели управления.
	// Обратите внимание на то, что панель управления должна располагаться в корне (под)домена.
	// http://example.com/, http://cp.example.com/, http://panel.example.com/ - правильно.
	// http://example.com/panel/ - неправильно.
	'url'			=>		'http://78.155.217.192/',
	
	// Токен.
	// Используется для запуска скриптов из Cron`а.
	'token'			=>		'MYTOKEN',
	
	// Тип СУБД.
	// По умолчанию поддерживается только СУБД MySQL (mysql).
	'db_type'		=>		'mysql',
	
	// Хост БД.
	// Пример: localhost, 127.0.0.1, db.example.com и пр.
	'db_hostname'	=>		'localhost',
	
	// Имя пользователя СУБД.
	'db_username'	=>		'admin',
	
	// Пароль пользователя СУБД.
	'db_password'	=>		'6247d164bd',
	
	// Название БД.
	'db_database'	=>		'panel',
	
	// E-Mail отправителя.
	// Используйте такой же Email как в файле /engine/libs/mail.php
	'mail_from'		=>		'yourmail@yandex.ru',
	
	// Имя отправителя.
	// Пример: ExampleHost Support
	'mail_sender'	=>		'ExampleHost Support',

	// Createhost Merchant
	'm_url'			=>		'https://cp.createhost.ru/invoices/merchant/pay', // URL мерчанта
	'm_secret'		=>		'2b7048a50b9e582cdcbfd2691c00a10b', // Секретное слово
	'm_account'		=>		'1', // ID пользователя
);
?>

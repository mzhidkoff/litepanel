<?php
mb_internal_encoding("UTF-8");
ini_set('display_errors', 0); // set 0 in production
error_reporting(E_ALL);

define('ENGINE_DIR', dirname(__FILE__) . '/engine/');
define('APPLICATION_DIR', dirname(__FILE__) . '/application/');

require_once(ENGINE_DIR . 'main/controller.php');
require_once(ENGINE_DIR . 'main/model.php');

require_once(ENGINE_DIR . 'main/registry.php');
require_once(ENGINE_DIR . 'main/config.php');
require_once(ENGINE_DIR . 'main/request.php');
require_once(ENGINE_DIR . 'main/session.php');
require_once(ENGINE_DIR . 'main/response.php');
require_once(ENGINE_DIR . 'main/document.php');
require_once(ENGINE_DIR . 'main/db.php');
require_once(ENGINE_DIR . 'main/user.php');
require_once(ENGINE_DIR . 'main/load.php');
require_once(ENGINE_DIR . 'main/action.php');

$registry = new Registry();

$config = new Config();
$registry->config = $config;

$request = new Request();
$registry->request = $request;

$session = new Session();
$registry->session = $session;

$response = new Response();
$registry->response = $response;

$document = new Document();
$registry->document = $document;

$db = new DB($config->db_type, $config->db_hostname, $config->db_username, $config->db_password, $config->db_database);
$registry->db = $db;

$user = new User($registry);
$registry->user = $user;

$load = new Load($registry);
$registry->load = $load;

$action = new Action($registry);
$registry->action = $action;

$path = substr(parse_url($request->server['REQUEST_URI'], PHP_URL_PATH), 1);
$action->make($path);

$response->output($action->go());
?>

<?php
// Including global autoloader
require_once dirname(__FILE__) . '/../vendor/autoload.php';

// Init config data
$config = array();

// Basic config for Slim Application
$config['app'] = array(
    'name' => 'My Awesome Webapp',
    'log.enabled' => true,
    'log.level' => Slim\Log::INFO,
    'log.writer' => new Slim\Extras\Log\DateTimeFileWriter(array(
        'path' => dirname(__FILE__) . '/../share/logs'
    )),
    'mode' => (!empty($_ENV['SLIM_MODE'])) ? $_ENV['SLIM_MODE']: 'production'
);

// Load config file
$configFile = dirname(__FILE__) . '/../share/config/default.php';

if (is_readable($configFile)) {
    require_once $configFile;
}

// Create application instance with config
$app = new Slim\Slim($config['app']);

// Get logger
$log = $app->getLog();

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.level' => Slim\Log::WARN,
        'debug' => false
    ));
});

// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.level' => Slim\Log::DEBUG,
        'debug' => true
    ));
});

// Other config here (i.e. database, mail system, etc)...

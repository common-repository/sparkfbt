<?php



if (!\defined('ABSPATH')) {
    exit;
}
require_once __DIR__ . '/vendor/autoload.php';
use Sparkfbt\Symfony\Component\Config\FileLocator;
use Sparkfbt\Symfony\Component\DependencyInjection\ContainerBuilder;
use Sparkfbt\Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
\defined('WP_UNINSTALL_PLUGIN') || exit;
$container = new ContainerBuilder();
$fileLocator = new FileLocator(__DIR__);
$loader = new YamlFileLoader($container, $fileLocator);
$loader->load('config/services-sparkfbt.yaml');
$container->compile();
$uninstaller = $container->get('uninstaller');
$uninstaller->uninstall();

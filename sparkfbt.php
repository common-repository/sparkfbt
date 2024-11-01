<?php



/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://sparkplugins.com
 *
 * @wordpress-plugin
 * Plugin Name:           SparkFBT - WooCommerce Frequently Bought Together
 * Plugin URI:            https://www.sparkplugins.com/sparkfbt
 * Description:           Boost WooCommerce Sales by showing your customers products they want!
 * Version:               1.2.4
 * Author:                SparkPlugins
 * Author URI:            https://sparkplugins.com/
 * License:               GPL-2.0+
 * License URI:           http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:           sparkfbt
 * Domain Path:           /languages
 * Requires at least:     6.4
 * Requires PHP:          7.4
 * Requires Plugins:      woocommerce
 * WC requires at least:  8.0
 * WC tested up to:       8.8
 */
if (!\defined('ABSPATH')) {
    exit;
}
require_once __DIR__ . '/vendor/autoload.php';
use Sparkfbt\Symfony\Component\Config\ConfigCache;
use Sparkfbt\Symfony\Component\Config\FileLocator;
use Sparkfbt\Symfony\Component\DependencyInjection\ContainerBuilder;
use Sparkfbt\Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Sparkfbt\Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
// If this file is called directly, abort.
if (!\defined('WPINC')) {
    die;
}
if (!\function_exists('Sparkfbt\\Sparkfbt_getSparkPluginsContainer')) {
    function Sparkfbt_getSparkPluginsContainer()
    {
        $file = __DIR__ . '/cache/container.php';
        $containerConfigCache = new ConfigCache($file, \SPARK_DEV_MODE);
        if (!$containerConfigCache->isFresh()) {
            $container = new ContainerBuilder();
            $fileLocator = new FileLocator(__DIR__);
            $loader = new YamlFileLoader($container, $fileLocator);
            $loader->load('config/services-sparkfbt.yaml');
            $container->compile();
            $dumper = new PhpDumper($container);
            $containerConfigCache->write($dumper->dump(['class' => 'Sparkfbt_Container']), $container->getResources());
        }
        require_once $file;
        return new Sparkfbt_Container();
    }
}
if (!\function_exists('Sparkfbt\\Sparkfbt_activateSparkPlugin')) {
    function Sparkfbt_activateSparkPlugin()
    {
        $container = Sparkfbt_getSparkPluginsContainer();
        $activator = $container->get('activator');
        $activator->activate();
    }
}
if (!\function_exists('Sparkfbt\\Sparkfbt_deactivateSparkPlugin')) {
    function Sparkfbt_deactivateSparkPlugin()
    {
        $container = Sparkfbt_getSparkPluginsContainer();
        $deactivator = $container->get('deactivator');
        $deactivator->deactivate();
    }
}
register_activation_hook(__FILE__, 'Sparkfbt_activateSparkPlugin');
register_deactivation_hook(__FILE__, 'Sparkfbt_deactivateSparkPlugin');
if (!\defined('SPARK_DEV_MODE')) {
    \define('SPARK_DEV_MODE', \false);
}
if (!\defined('SPARK_DEV_HOST_ADMIN')) {
    \define('SPARK_DEV_HOST_ADMIN', \false);
}
if (!\defined('SPARK_DEV_HOST_PUBLIC')) {
    \define('SPARK_DEV_HOST_PUBLIC', \false);
}
if (!\function_exists('Sparkfbt\\Sparkfbt_runSparkPlugin')) {
    function Sparkfbt_runSparkPlugin()
    {
        $container = Sparkfbt_getSparkPluginsContainer();
        $globalVariables = $container->get('global-variables');
        $globalVariables->setPluginDir(\dirname(plugin_basename(__FILE__)));
        $globalVariables->setPluginDirPath(plugin_dir_path(__FILE__));
        $globalVariables->setPluginUrl(plugin_dir_url(__FILE__));
        $globalVariables->setPluginFilePath(__FILE__);
        $plugin = $container->get('plugin');
        $plugin->run();
    }
}
Sparkfbt_runSparkPlugin();

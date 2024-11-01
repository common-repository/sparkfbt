<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\ProductPlacementHooks;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Loader;
use Sparkfbt\SparkPlugins\SparkWoo\Common\Plugins\GlobalVariables;
use Sparkfbt\SparkPlugins\SparkWoo\Common\Plugins\PluginMeta;
class DefaultWooCommercePlacementHook extends AbstractProductPlacementHook implements ProductPlacementHookInterface
{
    protected $actionName;
    public function __construct($key, $name, $description, $productsManager, $partial, PluginMeta $pluginMeta, GlobalVariables $globalVariables, $actionName)
    {
        parent::__construct($key, $name, $description, $productsManager, $partial, $pluginMeta, $globalVariables);
        $this->actionName = $actionName;
    }
    public function definePublicHooks(Loader $loader) : void
    {
        $loader->addAction($this->actionName, $this, 'render');
    }
}

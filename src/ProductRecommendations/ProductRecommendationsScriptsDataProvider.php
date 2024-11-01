<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Collections\CollectionInterface;
use Sparkfbt\SparkPlugins\SparkWoo\Common\StylesScripts\ScriptsDataProviderInterface;
class ProductRecommendationsScriptsDataProvider implements ScriptsDataProviderInterface
{
    private CollectionInterface $placementHooks;
    public function __construct(CollectionInterface $placementHooks)
    {
        $this->placementHooks = $placementHooks;
    }
    public function getScriptData() : array
    {
        return array('productRecommendations' => array('placementHooks' => $this->placementHooks));
    }
}
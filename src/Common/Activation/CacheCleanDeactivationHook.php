<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\Common\Activation;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Cache\CacheManager;
class CacheCleanDeactivationHook implements DeactivationHookInterface
{
    protected CacheManager $cacheManager;
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }
    public function run() : void
    {
        $this->cacheManager->clear();
        flush_rewrite_rules(\false);
    }
}

<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\Common\Modules;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Loader;
interface ModuleInterface
{
    public function defineAdminHooks(Loader $loader) : void;
    public function definePublicHooks(Loader $loader) : void;
}

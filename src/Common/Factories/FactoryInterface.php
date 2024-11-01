<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\Common\Factories;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Models\ModelInterface;
interface FactoryInterface
{
    public function default() : array;
    public function create(array $data) : ModelInterface;
}

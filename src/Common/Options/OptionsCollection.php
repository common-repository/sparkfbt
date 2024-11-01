<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\Common\Options;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Collections\AbstractCollection;
use Sparkfbt\SparkPlugins\SparkWoo\Common\Collections\CollectionInterface;
class OptionsCollection extends AbstractCollection implements CollectionInterface, \JsonSerializable
{
    /**
     * @var OptionInterface[]
     */
    protected array $items;
    public function jsonSerialize() : array
    {
        $json = array();
        foreach ($this->items as $item) {
            $json[$item->getNameWithoutPrefix()] = $item;
        }
        return $json;
    }
    public function getOptionKeyValue() : array
    {
        $items = array();
        foreach ($this->items as $item) {
            $items[$item->getName()] = $item->getValue();
        }
        return $items;
    }
}
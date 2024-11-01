<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sparkfbt\Symfony\Component\DependencyInjection\Compiler;

use Sparkfbt\Symfony\Component\DependencyInjection\Argument\ArgumentInterface;
use Sparkfbt\Symfony\Component\DependencyInjection\ContainerBuilder;
use Sparkfbt\Symfony\Component\DependencyInjection\ContainerInterface;
use Sparkfbt\Symfony\Component\DependencyInjection\Definition;
use Sparkfbt\Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Sparkfbt\Symfony\Component\DependencyInjection\Reference;
/**
 * Throws an exception for any Definitions that have errors and still exist.
 *
 * @author Ryan Weaver <ryan@knpuniversity.com>
 */
class DefinitionErrorExceptionPass extends AbstractRecursivePass
{
    private $erroredDefinitions = [];
    private $sourceReferences = [];
    /**
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        try {
            parent::process($container);
            $visitedIds = [];
            foreach ($this->erroredDefinitions as $id => $definition) {
                if ($this->isErrorForRuntime($id, $visitedIds)) {
                    continue;
                }
                // only show the first error so the user can focus on it
                $errors = $definition->getErrors();
                throw new RuntimeException(\reset($errors));
            }
        } finally {
            $this->erroredDefinitions = [];
            $this->sourceReferences = [];
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function processValue($value, bool $isRoot = \false)
    {
        if ($value instanceof ArgumentInterface) {
            parent::processValue($value->getValues());
            return $value;
        }
        if ($value instanceof Reference && $this->currentId !== ($targetId = (string) $value)) {
            if (ContainerInterface::RUNTIME_EXCEPTION_ON_INVALID_REFERENCE === $value->getInvalidBehavior()) {
                $this->sourceReferences[$targetId][$this->currentId] ?? ($this->sourceReferences[$targetId][$this->currentId] = \true);
            } else {
                $this->sourceReferences[$targetId][$this->currentId] = \false;
            }
            return $value;
        }
        if (!$value instanceof Definition || !$value->hasErrors()) {
            return parent::processValue($value, $isRoot);
        }
        $this->erroredDefinitions[$this->currentId] = $value;
        return parent::processValue($value);
    }
    private function isErrorForRuntime(string $id, array &$visitedIds) : bool
    {
        if (!isset($this->sourceReferences[$id])) {
            return \false;
        }
        if (isset($visitedIds[$id])) {
            return $visitedIds[$id];
        }
        $visitedIds[$id] = \true;
        foreach ($this->sourceReferences[$id] as $sourceId => $isRuntime) {
            if ($visitedIds[$sourceId] ?? ($visitedIds[$sourceId] = $this->isErrorForRuntime($sourceId, $visitedIds))) {
                continue;
            }
            if (!$isRuntime) {
                return \false;
            }
        }
        return \true;
    }
}
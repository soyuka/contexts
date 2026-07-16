<?php

namespace Behatch;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Extension implements ExtensionInterface
{
    public function getConfigKey(): string
    {
        return 'behatch';
    }

    public function initialize(ExtensionManager $extensionManager): void
    {
    }

    public function process(ContainerBuilder $container): void
    {
    }

    public function load(ContainerBuilder $container, array $config): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/Resources/services'));
        $loader->load('http_call.yml');

        $this->loadClassResolver($container);
        $this->loadHttpCallListener($container);
    }

    public function configure(ArrayNodeDefinition $builder): void
    {
    }

    private function loadClassResolver(ContainerBuilder $container): void
    {
        $definition = new Definition('Behatch\Context\ContextClass\ClassResolver');
        $definition->addTag(ContextExtension::CLASS_RESOLVER_TAG);
        $container->setDefinition('behatch.class_resolver', $definition);
    }

    private function loadHttpCallListener(ContainerBuilder $container): void
    {
        $processor = new \Behat\Testwork\ServiceContainer\ServiceProcessor();
        $references = $processor->findAndSortTaggedServices($container, 'behatch.context_voter');
        $definition = $container->getDefinition('behatch.context_supported.voter');

        foreach ($references as $reference) {
            $definition->addMethodCall('register', [$reference]);
        }
    }

    public function getCompilerPasses()
    {
        return [];
    }
}

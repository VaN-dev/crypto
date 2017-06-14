<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TickerApiClientCollectionPass
 * @package AppBundle\DependencyInjection\Compiler
 */
class TickerApiClientCollectionPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('app.market.api_client.ticker.collection')) {
            return;
        }

        $definition = $container->getDefinition('app.market.api_client.ticker.collection');
        $taggedServices = $container->findTaggedServiceIds('app.api_client.ticker');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addClient', array(
                    new Reference($id),
                    $attributes["alias"]
                ));
            }
        }
    }
}
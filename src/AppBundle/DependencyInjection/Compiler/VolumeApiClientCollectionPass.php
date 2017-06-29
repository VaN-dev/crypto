<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class VolumeApiClientCollectionPass
 * @package AppBundle\DependencyInjection\Compiler
 */
class VolumeApiClientCollectionPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('app.market.api_client.volume.collection')) {
            return;
        }

        $definition = $container->getDefinition('app.market.api_client.volume.collection');
        $taggedServices = $container->findTaggedServiceIds('app.api_client.volume');

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
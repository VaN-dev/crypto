<?php

namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\BalanceApiClientCollectionPass;
use AppBundle\DependencyInjection\Compiler\TickerApiClientCollectionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AppBundle
 * @package AppBundle
 */
class AppBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TickerApiClientCollectionPass());
        $container->addCompilerPass(new BalanceApiClientCollectionPass());
    }
}

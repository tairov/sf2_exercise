<?php
/**
 * Created by PhpStorm.
 * User: atairov
 * Date: 4/1/16
 * Time: 12:56 AM
 */

namespace AppBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class EarlyLoggingMessagePass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $def = $container->getAlias('logger');
        $def = $container->getDefinition($def);
        $def->addMethodCall('debug', ['Logger CREATED']);
    }
}
<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;


/**
 * minimum functional kernel!
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \AppBundle\AppBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle()
        ];

        if ($this->getEnvironment() == 'dev') {
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle();
        }
        return $bundles;
    }
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');

        $isDevEnv = $this->getEnvironment() == 'dev';
        $loader->load(function(ContainerBuilder $container) use ($isDevEnv) {
            if (!$isDevEnv) {
                return;
            }
            $container->loadFromExtension('web_profiler', [
                'toolbar' => true
            ]);

            $container->loadFromExtension('framework', [
                'router' => [
                    'resource' => '%kernel.root_dir%/config/routing_dev.yml'
                ]
            ]);
        });
    }
}
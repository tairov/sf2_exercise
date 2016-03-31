<?php
namespace Dino\Play;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

require __DIR__.'/../vendor/autoload.php';

$start = microtime(true);
$cachedContainer = '/tmp/container_cache.php';

if (!file_exists($cachedContainer)) {
    $container = new ContainerBuilder();
    $container->setParameter('root_dir', __DIR__);

    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
    $loader->load('services.yml');

    $container->compile();
    $dumper = new PhpDumper($container);
    file_put_contents($cachedContainer, $dumper->dump());
}

require $cachedContainer;
$container = new \ProjectServiceContainer();

runApp($container);

$elapsed = round( (microtime(true) - $start) * 1000 );

$container->get('logger')->debug('Elapsed Time: '.$elapsed.'ms');

function runApp(Container $container)
{
    $container->get('logger')->info('ROOOAAARR');
}
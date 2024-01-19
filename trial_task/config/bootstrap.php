// src/config/bootstrap.php

require dirname(__DIR__).'/vendor/autoload.php';

use App\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__).'/config'));
$loader->load('services.yaml');

$application = new Application();
$application->run();

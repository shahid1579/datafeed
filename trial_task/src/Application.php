// src/Application.php

namespace App;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('XML to DB Application', '1.0.0');

        $container = require __DIR__.'/config/bootstrap.php';
        $this->addCommands($container->get('App\Command\XmlDataFetchCommand'));
    }
}

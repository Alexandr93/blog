<?php

require_once(__DIR__.'/../framework/Loader.php');


$loader=Loader::getInstance();

$loader->addNamespacePath('Blog\\', __DIR__.'/../src/Blog');
$loader->addNamespacePath('Framework\\',__DIR__.'/../framework');


$app = new \Framework\Application(__DIR__.'/../app/config/config.php');


echo $app->run();

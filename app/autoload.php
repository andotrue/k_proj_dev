<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;

/*
require __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';//add
require __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/ClassLoader/ApcUniversalClassLoader.php';//add

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

use Symfony\Component\ClassLoader\ApcUniversalClassLoader;//add


$loader2 = require __DIR__.'/../vendor/autoload.php';
$loader = new ApcUniversalClassLoader('');//add

AnnotationRegistry::registerLoader(array($loader2, 'loadClass'));

return $loader;
*/
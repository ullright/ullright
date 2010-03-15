<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$_test_dir = realpath(dirname(__FILE__).'/..');

// configuration
require_once dirname(__FILE__).'/../../config/ProjectConfiguration.class.php';

$GLOBALS['time'] = microtime(true);

//function timelog($section)
//{
//  $interval = microtime(true) - $GLOBALS['time'];
//  $GLOBALS['time'] = microtime(true);
//   
//  var_dump($section . ': ' . $interval);    
//}

// Introduced by sf1.3 upgrade. 
// Deactivated and replaced with the call from sf1.2 because in the tests
//   sfContext::createInstance() needs an sfApplicationConfiguration and not
//   a ProjectConfiguration
//
//$configuration = ProjectConfiguration::hasActive() ? ProjectConfiguration::getActive() : new ProjectConfiguration(realpath($_test_dir.'/..'));
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', isset($debug) ? $debug : true);

//timelog('got config');


// autoloader - why do we need this?
//$autoload = sfSimpleAutoload::getInstance(sfConfig::get('sf_cache_dir').'/project_autoload.cache');
//$autoload->loadConfiguration(sfFinder::type('file')->name('autoload.yml')->in(array(
//  sfConfig::get('sf_symfony_lib_dir').'/config/config',
//  sfConfig::get('sf_config_dir'),
//)));
//$autoload->register();

// lime
include $configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

//timelog('end of bootstrap');


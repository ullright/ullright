<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$_test_dir = realpath(dirname(__FILE__).'/..');

require_once(dirname(__FILE__).'/../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', isset($debug) ? $debug : true);
include($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');

//following two params are necessary for unit tests which use sfWebController::genUrl()
sfConfig::set('sf_relative_url_root', '');
sfConfig::set('sf_no_script_name', true);
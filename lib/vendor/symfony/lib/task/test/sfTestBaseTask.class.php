<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Bernhard Schussek <bschussek@gmail.com> 
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base test task.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfTestBaseTask.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class sfTestBaseTask extends sfBaseTask
{
  /**
   * Filters tests through the "task.test.filter_test_files" event.
   * 
   * @param  array $tests     An array of absolute test file paths
   * @param  array $arguments Current task arguments
   * @param  array $options   Current task options
   * 
   * @return array The filtered array of test files
   */
  protected function filterTestFiles($tests, $arguments, $options)
  {
    $event = new sfEvent($this, 'task.test.filter_test_files', array('arguments' => $arguments, 'options' => $options));

    $this->dispatcher->filter($event, $tests);

    return $event->getReturnValue();
  }
  
  /**
   * Returns the test base directories of the project and plugins.
   * 
   * If the option 'no-plugins' is set, only the project test directory is
   * returned. If the option plugin is set, only the test directory of this
   * plugin is returned. Otherwise all test directories of the project and
   * all plugins, for which testing is enabled, are returned.
   * 
   * Optionally you can specify a subdirectory, which will be appended to
   * all paths.
   * 
   * @param array $options
   * @param string $subDirectory
   * @return array
   */
  protected function getBaseDirs(array $options, $subDirectory='')
  {
    $baseDirs = array();
    
    if ($subDirectory)
    {
      $subDirectory = DIRECTORY_SEPARATOR . $subDirectory;
    }

    if ($options['plugin'])
    {
      // test a single plugin
      foreach ($this->getPluginPaths(false) as $plugin => $path)
      {
        if ($options['plugin'] == $plugin)
        {
          $baseDirs[] = $path . $subDirectory;
        }
      }
    }
    else
    {
      // test the project
      $baseDirs[] = sfConfig::get('sf_test_dir') . $subDirectory;
        
      // test all plugins
      if (!$options['no-plugins'])
      {
        foreach ($this->getPluginPaths() as $plugin => $path)
        {
          $baseDirs[] = $path . $subDirectory;
        }
      }
    }
    
    return $baseDirs;
  }
  
  /**
   * Returns the paths of all plugins for which testing is enabled.
   * 
   * @param bool $enabledOnly Specifies whether only enabled plugins should be
   *        returned
   * @return array
   */
  private function getPluginPaths($enabledOnly=true)
  {
    $pluginPaths = $this->configuration->getPluginPaths();
    
    if ($enabledOnly)
    {
      $enabledPlugins = $this->configuration->getTestedPlugins();
    
      foreach ($pluginPaths as $plugin => &$path)
      {
        if (!in_array($plugin, $enabledPlugins))
        {
          unset($pluginPaths[$plugin]);
        }
      }
    }
    
    foreach ($pluginPaths as &$path)
    {
      $path .= DIRECTORY_SEPARATOR.'test';
    }
    
    return $pluginPaths;
  }
  
}

<?php

abstract class ullBaseTask extends sfBaseTask
{
  
  protected
    $recordNamespace = 'test',
    $configuration,
    $debug = false,
    
    $isDryRun = false,
    /* Be less noisy, output only interesting stuff (for cron)*/
    $isLessNoisy = false,
    
    $camelcase_name = '',
    $underscore_name = '',
    $humanized_name = '',
    $hyphen_name = ''    
  ;
  
  /**
   * Gets a sfConfig option and check's that it is set
   * 
   * @param string $option
   * @return string
   * @throws InvalidArgumentException
   */
  protected function getRequiredSfConfigOption($option)
  {
    if (sfConfig::has($option))
    {
      return sfConfig::get($option);
    }
    else
    {
      throw new InvalidArgumentException('Required sfConfig option not set: ' . $option);
    }
  }
  
  
  /**
   * Initialize database connection
   * 
   * @param array $arguments
   * @param array $options
   * @return none
   */
  protected function initializeDatabaseConnection($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Initializing database connection');
    
    $this->configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], $this->debug);
    
    $databaseManager = new sfDatabaseManager($this->configuration);
  }
  
  
  /**
   * Deletes a file using svn
   * 
   * @param  string $path path relative to symfony base dir
   */   
  protected function svnDelete($path) 
  {
    $fullPath = sfConfig::get('sf_root_dir') . '/' . $path;
    
    if (file_exists($fullPath))
    {
      $command = 'svn --force delete ' . $fullPath;
      try 
      {
        $this->log($this->getFilesystem()->execute($command));
      }
      catch (Exception $e)
      {
        $this->log('Cannot svn delete - try file system delete');
        $command = 'rm ' . $fullPath;
        $this->log($this->getFilesystem()->execute($command));
      }
      
    }
    else
    {
      $this->logBlock('File not found: ' . $fullPath, 'INFO');
    }
  } 

    /**
     * exports a file form the ullright repository and saves it to local working copy
     * 
     * @param  string $path path relative to symfony base dir
     */     
//    protected function svnExport($path) 
//    {
//      $absolute_path = $this->absolutePath($path);
//
//      $this->log('svn export: ' 
//            . shell_exec("svn export {$this->ullright_repo_url}/{$path} {$absolute_path}"));
//    }
  

  /**
   * Issue a delete warning question
   * 
   * @return unknown_type
   */
  protected function printDeleteWarning($arguments = array(), $options = array())
  {
    if (!$options['no-confirmation'])
    {
      $this->askConfirmation('This task is gonna svn delete some files. Are you sure you want to continue?');
    }
  }
  
  protected function deleteModelClasses($modelName, $pluginName = null)
  {
    $this->logSection($this->name, 'Deleting model classes');
    $path = 'lib/model/doctrine/' .
      (($pluginName) ? $pluginName . '/' : '');

    $currentPath = $path . 'base/Base' . $modelName . '.class.php'; 
    $this->svnDelete($currentPath);    
    
    $currentPath = $path . $modelName . '.class.php'; 
    $this->svnDelete($currentPath);

    $currentPath = $path . $modelName . 'Table.class.php'; 
    $this->svnDelete($currentPath);
    
    
    $this->logSection($this->name, 'Deleting form classes');
    $path = 'lib/form/doctrine/' .
      (($pluginName) ? $pluginName . '/' : '');

    $currentPath = $path . 'base/Base' . $modelName . 'Form.class.php'; 
    $this->svnDelete($currentPath);      
      
    $currentPath = $path . $modelName . 'Form.class.php'; 
    $this->svnDelete($currentPath);

    
    $this->logSection($this->name, 'Deleting filter form classes');
    $path = 'lib/filter/doctrine/' .
      (($pluginName) ? $pluginName . '/' : '');

    $currentPath = $path . 'base/Base' . $modelName . 'FormFilter.class.php'; 
    $this->svnDelete($currentPath);      
      
    $currentPath = $path . $modelName . 'FormFilter.class.php'; 
    $this->svnDelete($currentPath);
    

    // check for versionable behaviour
    $path = 'lib/form/doctrine/' .
      (($pluginName) ? $pluginName . '/' : '');
    $currentPath = $path . 'base/Base' . $modelName . 'VersionForm.class.php';
    
    if (file_exists($currentPath))
    {
      $this->logSection($this->name, 'Deleting versionable behavior filter and form classes');
      $this->svnDelete($currentPath);
      
      $currentPath = $path . $modelName . 'VersionForm.class.php'; 
      $this->svnDelete($currentPath);  
      
      $path = 'lib/filter/doctrine/' .
        (($pluginName) ? $pluginName . '/' : '');
  
      $currentPath = $path . 'base/Base' . $modelName . 'VersionFormFilter.class.php'; 
      $this->svnDelete($currentPath);      
        
      $currentPath = $path . $modelName . 'VersionFormFilter.class.php'; 
      $this->svnDelete($currentPath);      
    }
    
    /* Delete plugin classes */
    if ($pluginName)
    {
      $this->logSection($this->name, 'Deleting plugin classes');
      
      $path = 'plugins/' . $pluginName . '/lib';
      
      $currentPath = $path . '/model/doctrine/Plugin' . $modelName . '.class.php';
      $this->svnDelete($currentPath);
      
      $currentPath = $path . '/model/doctrine/Plugin' . $modelName . 'Table.class.php';
      $this->svnDelete($currentPath);      
      
      $currentPath = $path . '/form/doctrine/Plugin' . $modelName . 'Form.class.php';
      $this->svnDelete($currentPath);      
      
      $currentPath = $path . '/filter/doctrine/Plugin' . $modelName . 'FormFilter.class.php';
      $this->svnDelete($currentPath);            
    }
  }

  
  /**
   * Set a default namespace which is used for all new UllRecord Doctrine_Records
   * created by the createRecord() method
   * 
   * @param string $name
   * @return self
   */
  protected function setRecordNamespace($name)
  {
    $this->recordNamespace = $name;
    
    return $this;
  }
  
  
  /**
   * Get the default namespace name for UllRecord Doctrine_RecordsS
   * 
   * @return string
   */
  protected function getRecordNamespace()
  {
    return $this->recordNamespace;
  }
  
  
  /**
   * Create an UllRecord Doctrine_Record with support for namespace
   * 
   * By default the namespace set by setRecordNamespace() is used
   * 
   * @param string $name
   * @param string $namespace
   * @return unknown_type
   */
  protected function createRecord($name, $namespace = null)
  {
    $object = new $name;
    
    if ($namespace === null)
    {
      $namespace = $this->getRecordNamespace();
    }

    $object['namespace'] = $namespace;

    return $object;
  }
  
  
  /**
   * Gets the current ullright svn revision number
   * 
   * Preferable from data/ullright_svn_revision.txt
   * Or via svn info as fallback
   * 
   * @return integer
   */
  protected function getUllrightCurrentRevision()
  {
    // TODO: implement setting somehow in the upgrade/refresh procedure
    $path = sfConfig::get('sf_data_dir') .
      DIRECTORY_SEPARATOR .
      'ullright_svn_revision.txt';
      
    if (file_exists($path))
    {
      return file_get_contents($path);
    }  
    else
    {
      $command = 'svn info plugins/ullCorePlugin';
      $output = reset($this->getFilesystem()->execute($command));
      preg_match('#Revision: ([\d]+)#', $output, $matches);
      
      return $matches[1];
    }
  }
  
  
  /**
   * Gets the current ullright HEAD revision number
   * 
   * @return integer
   */
  protected function getUllrightHeadRevision()
  {
    $command = 'svn info http://bigfish.ull.at/svn/ullright/trunk';
    $output = reset($this->getFilesystem()->execute($command));
    preg_match('#Revision: ([\d]+)#', $output, $matches);
      
    return $matches[1];
  }

  /**
   * Set dry run option
   * 
   * This means that no data is modified in the database etc.
   * 
   * @param boolean $boolean
   */
  public function setIsDryRun($boolean)
  {
    $this->isDryRun = (bool) $boolean;
    
    return $this;
  }  
  
  /**
   * Check for dry run option
   * 
   * @return boolean
   */
  public function isDryRun()
  {
    return (bool) $this->isDryRun;
  }

  
  /**
   * Set "less noisy" flag.
   * 
   * This means that only uncommon or events of special interest are
   * outputted. Used mainly for cron jobs
   * 
   * @param boolean $boolean
   * @return self
   */
  public function setIsLessNoisy($boolean)
  {
    $this->isLessNoisy = (bool) $boolean;
    
    return $this;
  }
  
  
  /**
   * Get "less noisy" flag
   * 
   * @return boolean
   */
  public function isLessNoisy()
  {
    return (bool) $this->isLessNoisy;
  }
  
  
  /**
   * Adds "less noisy" mode
   * 
   * @see sfTask 
   * 
   */
  public function logSection($section, $message, $size = null, $style = 'INFO', $beNoisy = false)
  {
    if ($this->isLessNoisy() && !$beNoisy)
    {
      return;
    }
    
    return parent::logSection($section, $message, $size, $style);
  }

  /**
   * Log section even in "less noisy" mode
   * 
   * @param boolean $condition
   * @see sfTask 
   * 
   */
  public function logNoisySection($section, $message, $size = null, $style = 'INFO')
  {
    return $this->logSection($section, $message, $size, $style, true);
  }
  
  /**
   * Log section with a given condition even in "less noisy" mode
   * 
   * @param boolean $condition
   * @see sfTask 
   * 
   */
  public function logNoisySectionIf($condition, $section, $message, $size = null, $style = 'INFO')
  {
    $condition = (boolean) $condition;
    
    return $this->logSection($section, $message, $size, $style, $condition);
  }  
  
  
}
<?php

abstract class ullBaseTask extends sfBaseTask
{
  
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
   * Deletes a file using svn
   * 
   * @param  string $path path relative to symfony base dir
   */   
  protected function svnDelete($path) 
  {
    $fullPath = sfConfig::get('sf_root_dir') . '/' . $path;
    
    $command = 'svn delete ' . $fullPath;
    $this->log($this->getFilesystem()->execute($command));
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

}
<?php

class PinPluginRevisionsTask extends ullBaseTask
{
  
  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'pin-plugin-revisions';
    $this->briefDescription = 'Pins the subversion revision number';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] pins the subversion revision number of 
    the ullright plugins to a certain revision
    
    When no revision number is given, the HEAD revision is used
    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

    $this->addArgument('revision', sfCommandArgument::OPTIONAL,
      'The svn revision number', null);
    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Initializing');
    
    if ($arguments['revision'])
    {
      $this->pinToRevision($arguments['revision']);
      $this->log('Pinned svn:externals to ' . $arguments['revision']);
    }
    else
    {
      $this->pinToHeadRevision();
      $this->log('Pinned svn:externals to HEAD revision');
    }
        
  }
  
  
  /**
   * Pins the ullright externals to a revision nr.
   * 
   * @param unknown_type $revision
   */
  protected function pinToRevision($revision)
  {
    $externals = $this->getSvnExternalsProperty(sfConfig::get('sf_plugins_dir'));
    
    $externals = preg_replace('#( -r[\d]+)#', '', $externals);
//    var_dump($externals);
    
//    $externals = preg_replace('#(^[\S]+)#m', '$1 -r'. $revision,  $externals);
//    $externals = preg_match_all('#(http://bigfish.ull.at/svn/ullright/[\S]+)#m', $externals, $matches);
    $externals = preg_replace('#(http://bigfish.ull.at/svn/ullright/[\S]+)#m', '-r' . $revision . ' $1', $externals);
//    var_dump($matches);

    $this->putSvnExternalsProperty($externals, sfConfig::get('sf_plugins_dir'));
  }
  
  
  protected function pinToHeadRevision()
  {
    $externals = $this->getSvnExternalsProperty(sfConfig::get('sf_plugins_dir'));
    
    $externals = preg_replace('#( -r[\d]+)#', '', $externals);

    $this->putSvnExternalsProperty($externals, sfConfig::get('sf_plugins_dir'));    
  }
  
  
  protected function getSvnExternalsProperty($path)
  {
    $command = 'svn propget svn:externals ' . $path;
    
    $output = $this->getFilesystem()->execute($command);
    
    return $output[0];    
  }
  
  
  protected function putSvnExternalsProperty($content, $path)
  {
    $command = 'svn propset svn:externals "' . $content . '" ' . $path;
    
    return $this->getFilesystem()->execute($command);   
  }  
  
}
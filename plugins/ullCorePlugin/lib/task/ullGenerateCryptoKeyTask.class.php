<?php

/**
 * See task description
 */
class ullGenerateCryptoKeyTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
    ));

    $this->namespace = 'ullright';
    $this->name = 'generate-crypto-key';
    $this->briefDescription = 'Generates a new cryptographic key';

    $this->detailedDescription = <<<EOF
The [ullright:generate-crypto-key|INFO] task generates a new cryptographic key
and stores it in a file called security.key in the application config directory.
If this file exists, it is renamed to security.key.<timestamp>.backup

This key is used to secure url parameters (HMAC)

  [./symfony generate-crypto-key|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    //note: some magic happens before this method: the application
    //argument is checked for validity, configuration is loaded

    //check if we have write access to the app config dir
    if (!is_writable(sfConfig::get('sf_app_config_dir')))
    {
      $this->logBlock('Write access to application config directory denied, aborting.', 'ERROR_LARGE');
      return;
    }
    
    //generate a new key
    $this->logSection($this->name, 'attempting to generate key ...');
    
    try
    {
      $command = 'openssl rand -base64 2048';
      $commandOutput = $this->getFilesystem()->execute($command);
      $newKey = $commandOutput[0];
    }
    catch (RuntimeException $e)
    {
      $this->logBlock('Unable to execute command for key generation, aborting.', 'ERROR_LARGE');
      return;
    }
    
    //safety check
    if (strlen(base64_decode($newKey)) !== 2048)
    {
       $this->logBlock('Generated key has incorrect size, aborting.', 'ERROR_LARGE');
    }
    
    $keyFilePath = sfConfig::get('sf_app_config_dir') . '/security.key';
    $keyFileExists = file_exists($keyFilePath);
    
    //if there is an existing key file, rename it as backup
    //it does not matter if we have write access to the key
    //file as long as we have write access to the config
    //directory, which we already checked above
    if ($keyFileExists)
    {
      $answer = $this->askConfirmation(array('Existing key file found. ' .
        'Are you sure you want to proceed? The old key will be ' .
        'moved to a backup file. (y/N)'), 'QUESTION_LARGE', false);

      if (!$answer)
      {
        $this->logSection($this->name, 'aborting.');
        return;
      }
      
      $backupFileName = 'security.key.' . time() . '.backup';
      $this->logSection($this->name, 'key file exists, moving to: ' . $backupFileName);
      rename($keyFilePath, sfConfig::get('sf_app_config_dir') . '/' . $backupFileName);
    }
    
    //now that the file is out of the way, we can create a new one
    $this->logSection($this->name, 'opening new key file for writing ...');
    $keyFileHandle = fopen($keyFilePath, 'w');
    fwrite($keyFileHandle, $newKey);
    fclose($keyFileHandle);
    
    $this->logSection($this->name, 'successfully wrote new key file.');
    
    //change permission of newly created file to read for everyone
    $this->getFilesystem()->chmod($keyFilePath, 0444);
    $this->logSection($this->name, 'successfully changed permission of new key file.');
  }
}

<?php 

class PreBuildModel_Doctrine_Migration extends Doctrine_Migration
{
  
  /**
   * Get the path for the version store file
   */
  public function getVersionFile()
  {
    return sfConfig::get('sf_data_dir') . '/pre_build_model_version.txt';
  }
   
  /**
   * Set the current version of the database
   *
   * @param integer $number
   * @return void
   */
  public function setCurrentVersion($number)
  {
    file_put_contents($this->getVersionFile(), $number);
  }

  /**
   * Get the current version of the database
   *
   * @return integer $version
   */
  public function getCurrentVersion()
  {
    $result = null;
    
    if (file_exists($this->getVersionFile()))
    {
      $result = (integer) file_get_contents($this->getVersionFile());
    }

    return ($result) ? $result : 0;
  }

  /**
   * Returns true/false for whether or not this database has been migrated in the past
   *
   * @return boolean $migrated
   */
  public function hasMigrated()
  {
    $result = null;
    
    if (file_exists($this->getVersionFile()))
    {
      $result = (integer) file_get_contents($this->getVersionFile());
    }

    return ($result) ? true : false;
  }
}

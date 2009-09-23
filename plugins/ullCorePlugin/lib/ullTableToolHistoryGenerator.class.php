<?php

/**
 * ullTableToolHistoryGenerator
 * 
 * Extends the ullTableToolGenerator to provide a "diff"-functionality
 * for two Doctrine_Records.
 * This is intended to be used only with SuperVersionable-objects or
 * their *Versions, e.g. ullGroupPermission and ullGroupPermissionVersion.
 * 
 * The buildHistoryForm does the actual work, it adds columns without
 * differences to the generator's blacklist.
 * 
 * @author martin
 */
class ullTableToolHistoryGenerator extends ullTableToolGenerator
{
  protected $updator;
  protected $updated_at;
  protected $scheduled_update_date;
  protected $id;
  protected $wasScheduledUpdate;
  protected $scheduledUpdater;
  
  /**
   * Compares two Doctrine_Records and adds columns with
   * different content to the internal blacklist.
   * 
   * @param Doctrine_Record $curRow the current record
   * @param Doctrine_Record $revRow the revision record
   * @return void
   */
  public function buildHistoryForm(Doctrine_Record $curRow, Doctrine_Record $revRow, $enableFutureVersions) {
    $changes = array_diff_assoc($curRow->toArray(), $revRow->toArray());

    foreach ($curRow->toArray() as $key => $value)
    {
      //HACK
      //Our change detection algorithm doesn't handle I18n
      //fields correctly yet.
      if ($key == 'Translation')
      {
        continue;
      }
      
      if (!array_key_exists($key, $changes))
      {
        $this->columnsConfig->disable(array($key), true);
      }
    }

    $this->columnsConfig->disable(array(
      'version',
      'updated_at',
      'updator_user_id',
      'created_at',
      'creator_user_id',   
    ));

    if ($enableFutureVersions)
    {
      $this->columnsConfig->disable(array('scheduled_update_date'));
    }
    
    if (in_array('UllEntity', class_parents($curRow)))
    {
      $this->columnsConfig->disable(array('type'));
    }
    
    //->Updator is available in Version as well
    $this->updator = $curRow->Updator;
    $this->id = $curRow->identifier();

    if ($curRow->contains('scheduled_update_date'))
    {
      $this->scheduled_update_date = $curRow->scheduled_update_date;
    }

    $this->updated_at = $curRow->updated_at;

    if ($curRow->getTable()->hasTemplate('Doctrine_Template_SuperVersionable'))
    {
      $versionRecord = $curRow->getAuditLog()->getVersionRecord($curRow, $curRow->version);
      if ($versionRecord->contains('reference_version') && ($versionRecord->reference_version != NULL))
      {
        //var_dump($versionRecord->reference_version);
        $this->wasScheduledUpdate = true;
        $this->scheduledUpdater = $versionRecord->Updator;
      }
    }
    parent::buildForm($curRow);
  }
  
  /**
   * Internal function which checks if the generator's build was called.
   * 
   * @return void
   */
  public function _checkBuildRequirement()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildHistoryForm() first');
    }
  }
  
  /**
   * Gets the Updator
   * 
   * @return the updator
   */
  public function getUpdator()
  {
    $this->_checkBuildRequirement();
    return $this->updator;
  }
  
  /**
   * Gets the updated_at timestamp
   * 
   * @return updated_at timestamp
   */
  public function getUpdatedAt()
  {
    $this->_checkBuildRequirement();
    return $this->updated_at;
  }
  
  /**
   * Gets the scheduled update datestamp
   * 
   * @return scheduled update datestamp
   */
  public function getScheduledUpdateDate()
  {
    $this->_checkBuildRequirement();
    return $this->scheduled_update_date;
  }
  
  /**
   * Gets the Identifiers as an array
   * 
   * @return array identifiers
   */
  public function getIdentifierArray()
  {
    $this->_checkBuildRequirement();
    return $this->id;
  }
  
  /**
   * Gets: Was a scheduled update?
   * 
   * @return boolean was a scheduled update?
   */
  public function wasScheduledUpdate()
  {
    $this->_checkBuildRequirement();
    return $this->wasScheduledUpdate;
  }

  /**
   * Gets the scheduled updator
   * 
   * @return the scheduled updator
   */
  public function getScheduledUpdator()
  {
    $this->_checkBuildRequirement();
    return $this->scheduledUpdater;
  }
}
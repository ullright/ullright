<?php

class ullTableToolHistoryGenerator extends ullTableToolGenerator
{
  protected $columnsBlacklist = array();
  protected $updator;
  protected $updated_at;

  public function buildHistoryForm(Doctrine_Record $curRow, Doctrine_Record $revRow) {
    $changes = array_diff_assoc($curRow->toArray(), $revRow->toArray());

    foreach ($curRow->toArray() as $key => $value)
    {
      if (!array_key_exists($key, $changes))
      $this->columnsBlacklist[] = $key;
    }
    $this->columnsBlacklist[] = 'version';
    $this->columnsBlacklist[] = 'updated_at';
    $this->columnsBlacklist[] = 'updator_user_id';
    $this->columnsBlacklist[] = 'created_at';
    $this->columnsBlacklist[] = 'creator_user_id';
    
    $this->updator = $curRow->Updator;
    $this->updated_at = $curRow->updated_at;

    parent::removeBlacklistColumns();
    parent::buildForm($curRow);
  }

  public function getUpdator()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildHistoryForm() first');
    }
    return $this->updator;
  }

  public function getUpdatedAt()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildHistoryForm() first');
    }
    return $this->updated_at;
  }
}
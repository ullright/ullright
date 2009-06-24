<?php
/**
 * ullMetaWidgetUllUser
 *
 *
 * todo available options:
 * todo  'ull_select' => string, slug of a UllSelect
 * todo  'add_empty'  => boolean, true to include an empty entry
 */
class ullMetaWidgetUllUser extends ullMetaWidget
{

  protected function configureReadMode()
  {
    $this->columnConfig->removeWidgetOption('add_empty');
    $this->columnConfig->removeWidgetOption('group');

    $this->addWidget(new ullWidgetUllUserRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureWriteMode()
  {
    // generic query -> get all users
    $q = new Doctrine_Query;
    $q
      ->select('u.id, u.first_name, u.last_name')
      ->from('UllUser u')
      ->orderBy('u.last_name, u.first_name')
    ;

    //filter users by group
    if ($this->columnConfig->getWidgetOption('group') != null)
    {
      $groupName = $this->columnConfig->getWidgetOption('group');
      $this->columnConfig->removeWidgetOption('group');
      
      $group = Doctrine::getTable('UllGroup')->findOneByDisplayName($groupName);
      
      if (!$group) 
      {
        throw new InvalidArgumentException('Invalid UllGroup display_name given: ' . $groupName);
      }
      
      $q->addWhere('u.UllGroup.display_name = ?', $groupName);
    }
//      var_dump($q->execute(array(), DOCTRINE::HYDRATE_ARRAY));
    
    $this->columnConfig->setWidgetOption('model', 'UllEntity');
    $this->columnConfig->setWidgetOption('query', $q);
    $this->columnConfig->setWidgetOption('method', 'getLastNameFirst');
    
    $this->addWidget(new ullWidgetUllUser(
      $this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    
    $this->columnConfig->setValidatorOption('model', 'UllEntity');
    $this->columnConfig->setValidatorOption('query', $q);      
    $this->columnConfig->setValidatorOption('alias', 'u');
    
    $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig->getValidatorOptions()));
  }
  
  public function getSearchType()
  {
    return 'foreign';
  }
}
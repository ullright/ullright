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
  public function __construct($columnConfig, sfForm $form)
  {
    parent::__construct($columnConfig, $form);
  }

  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      // generic query -> get all users
      $q = new Doctrine_Query;
      $q
        ->select('u.id, u.first_name, u.last_name')
        ->from('UllUser u')
        ->orderBy('u.last_name, u.first_name')
      ;

      //filter users by group
      if (isset($this->columnConfig['widgetOptions']['group']))
      {
        $groupName = $this->columnConfig['widgetOptions']['group'];
        unset($this->columnConfig['widgetOptions']['group']);
        
        $group = Doctrine::getTable('UllGroup')->findOneByDisplayName($groupName);
        
        if (!$group) 
        {
          throw new InvalidArgumentException('Invalid UllGroup display_name given: ' . $groupName);
        }
        
        $q->addWhere('u.UllGroup.display_name = ?', $groupName);
      }
//      var_dump($q->execute(array(), DOCTRINE::HYDRATE_ARRAY));
      
      $this->columnConfig['widgetOptions']['model'] = 'UllEntity';
      $this->columnConfig['widgetOptions']['query'] = $q;
      
      $this->addWidget(new sfWidgetFormDoctrineSelect(
        $this->columnConfig['widgetOptions'],
        $this->columnConfig['widgetAttributes']
      ));
      
      $this->columnConfig['validatorOptions']['model'] = 'UllEntity';
      $this->columnConfig['validatorOptions']['query'] = $q;      
      
      $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig['validatorOptions']));
    }
    else
    {
      unset($this->columnConfig['widgetOptions']['add_empty']);
      unset($this->columnConfig['widgetOptions']['group']);

      $this->addWidget(new ullWidgetUllUser($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }

    //    var_dump($columnConfig);die;
  }
}

?>
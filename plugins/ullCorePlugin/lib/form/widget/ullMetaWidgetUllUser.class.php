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

      //filter users by group
      if (isset($this->columnConfig['widgetOptions']['group']))
      {
        $option_ullGroup = $this->columnConfig['widgetOptions']['group'];
        unset($this->columnConfig['widgetOptions']['group']);

        //get group_id by display_name
        $q = new Doctrine_Query;
        $q
          ->from('UllEntity')
          ->where('type = ?', 'group')
          ->addWhere('display_name = ?', $option_ullGroup)
          ->limit(1)
        ;
        $ullGroup = $q->execute()->getFirst();

        //get entity_ids by group_id
        $q = new Doctrine_Query;
        $q
          ->from('UllEntityGroup')
          ->where('ull_group_id = ?', $ullGroup->id)
        ;
        $ullEntityGroups = $q->execute();

        $ullUsersIds = array();
        foreach ($ullEntityGroups as $ullEntityGroup)
        {
          $ullUsersIds[] = $ullEntityGroup->ull_entity_id;
        }
      }

      $q = new Doctrine_Query;
      $q
        ->from('UllEntity a')
        ->where('a.type = ?', 'user')
      ;


      //only load entities assigned to group above
      //PROBLEM here!!
      if (isset($ullUsersIds))
      {
        $q
          ->addWhere('a.id IN (?)', implode(',',$ullUsersIds));
      }


      $this->columnConfig['widgetOptions']['model'] = 'UllEntity';
      $this->columnConfig['widgetOptions']['order_by'] = array('last_name, first_name', 'asc');
      $this->columnConfig['widgetOptions']['query'] = $q;

      $this->columnConfig['validatorOptions']['model'] = 'UllEntity';
      $this->columnConfig['validatorOptions']['query'] = $q;


      $this->addWidget(new sfWidgetFormDoctrineSelect(
        $this->columnConfig['widgetOptions'],
        $this->columnConfig['widgetAttributes']
      ));
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
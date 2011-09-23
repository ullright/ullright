<?php

/**
 * Validator for UllEntity select
 * 
 * @author klemens
 *
 */
class ullValidatorUllEntity extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * entity_classes:         An array of UllEntity child classes (e.g. UllUser, UllGroup, ...)
   *  * hide_choices:           An array of UllEntity ids to be not included (Blacklist) 
   *  * filter_users_by_group:  An UllGroup::display_name. Only members of the given group are included
   *                            This option is only valid for entity_class "UllUser"
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('entity_classes');
    $this->addOption('hide_choices');
    $this->addOption('filter_users_by_group');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $entityClasses = $this->transformEntityClasses();
    
    $model = 'UllEntity';
    if ($entityClasses == array('user'))
    {
      $model = 'UllUser';
    }
    
    if ('UllEntity' == $model && $this->getOption('filter_users_by_group'))
    {
      throw new RuntimeException('Option "filter_users_by_group" is only valid for entity_class "UllUser". Given: ' . print_r($this->getOption('entity_classes'), true));
    }
    
    $q = new Doctrine_Query;
    
    $q
      ->select('x.id')
      ->from($model . ' x')
      ->where('x.id = ?', $value)
    ;
    
    if ('UllEntity' == $model)
    {
      $q->whereIn('x.type', $entityClasses);
    }
    
    if ($hide = $this->getOption('hide_choices'))
    {
      $q->andWhereIn('x.id', $hide, true);
    }
    
    if ($group = $this->getOption('filter_users_by_group'))
    {
      $q->addWhere('x.UllGroup.display_name = ?', $group);
    }
    
    if (!$q->count())
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }
    
    return $value;
  }
  
  
  /**
   * Transform the entity classes from model name to entity type 
   * 
   * @return: array
   */
  protected function transformEntityClasses()
  {
    $classes = array();

    foreach ($this->getOption('entity_classes') as $class)
    {
      $table = Doctrine::getTable($class);
      $map = $table->getOption('inheritanceMap');
      $classes[] = $map['type'];
    }
    
    return $classes;
  }

}

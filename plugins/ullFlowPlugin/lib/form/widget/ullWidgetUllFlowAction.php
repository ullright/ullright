<?php

class ullWidgetUllFlowAction extends sfWidgetFormSelect
{

  public function __construct($options = array(), $attributes = array())
  {
    $options['choices'] = new sfCallable(array($this, 'getChoices'));

    parent::__construct($options, $attributes);
  }  
  

  /**
   * Returns the choices associated to the model.
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    $choices = array();
    
    $choices[''] = __('All active');
    $choices['all'] = __('All');
    
    $q = new UllQuery('UllFlowAction');
    $q
      ->addSelect('label')
      ->addWhere('is_status_only = ?', false)
      ->addOrderBy('label')
    ;
    
    if ($appSlug = sfContext::getInstance()->getRequest()->getParameter('app'))
    {
      $q->addWhere('UllFlowStepActions->UllFlowStep->UllFlowApp->slug = ?', $appSlug);
    }
      
    $objects = $q->execute();
    
    foreach ($objects as $object)
    {
      $choices[$object->slug] = $object->__toString();
    }  
    
    return $choices;
  }  
  
}

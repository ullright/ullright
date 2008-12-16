<?php

class ullWidgetUllFlowAction extends sfWidgetFormSelect
{

  public function __construct($options = array(), $attributes = array())
  {
    $options['choices'] = new sfCallable(array($this, 'getChoices'));

    parent::__construct($options, $attributes);
  }  
  
  protected function configure($options = array(), $attributes = array())
  {
//    $this->addOption('app_slug', false);
//    $this->addOption('add_empty', false);
    $this->addOption('method', '__toString');

    parent::configure($options, $attributes);
  }

  /**
   * Returns the choices associated to the model.
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    $choices = array();
//    if (false !== $this->getOption('add_empty'))
//    {
//      $choices[''] = true === $this->getOption('add_empty') ? '' : $this->getOption('add_empty');
//    }
    
    $choices[''] = __('All active');
    $choices['all'] = __('All');
    
//    $a = $this->getOption('alias');
//    $q = is_null($this->getOption('query')) ? Doctrine_Query::create()->from($this->getOption('model')." $a") : $this->getOption('query');
//
//    if ($order = $this->getOption('order_by'))
//    {
//      $q->orderBy("$a." . $order[0] . ' ' . $order[1]);
//    }

    $q = new Doctrine_Query;
    $q
      ->from('UllFlowAction a')
      ->where('a.is_status_only = ?', false)
    ;
    
//    printQuery($q->getQuery());
//    var_dump($q->getParams());
//    die;

    $objects = $q->execute();
    $method = $this->getOption('method');
    foreach ($objects as $object)
    {
        $choices[$object->slug] = $object->$method();
    }

    return $choices;
  }  
  
}

?>
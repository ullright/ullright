<?php

class ullWidgetUllSelect extends sfWidgetForm
{

  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('ull_select');
    $this->addOption('method', '__toString');

    parent::configure($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (empty($value))
    {
      return '';
    }

    $q = new Doctrine_Query;
    $q
      ->from('UllSelectChild a')
      ->where('a.id = ?', $value)
    ;

//    var_dump($q->getQuery());die;

    return $q->execute()->getFirst()->label;
  }

}

?>
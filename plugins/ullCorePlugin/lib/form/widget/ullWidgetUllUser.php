<?php

class ullWidgetUllUser extends sfWidgetForm
{

  protected function configure($options = array(), $attributes = array())
  {
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
      ->from('UllEntity a')
      ->where('a.id = ?', $value)
    ;

//    var_dump($q->getQuery());die;

    $result = $q->execute()->getFirst();

    #return $result->last_name.' '.$result->first_name;
    return $result;
  }

}

?>
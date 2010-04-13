<?php
class ullWidgetRatingRead extends ullWidget
{
 public function __construct($options = array(), $attributes = array())
  {
    //push this option into superclass?
    $this->addOption('add_random_identifier', true);
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (empty($value))
    {
      return __('Not yet rated', null, 'ullCoreMessages');
    }
    
    $html = '';
    $roundedAvg = round($value, 1);
    $checkedStar = $roundedAvg / 0.5;
    
    $name = ($name) ? $name : 'avg_rating';
    $this->setAttribute('name', $this->getOption('add_random_identifier') ? $name . '_' . uniqid() : $name);

    $attributeArray = array(
      'class' => 'star {split:2}',
      'type' => 'radio',
      'disabled' => 'disabled'
    );
    
    for($i = 1; $i <= 10; $i++)
    {
      if ($i == $checkedStar)
      {
        $html .= $this->renderTag('input', $attributeArray + array('checked' => 'checked'));
      }
      else
      {
        $html .= $this->renderTag('input', $attributeArray);
      }
    }

    return $html;
  }
}
<?php

class ullWidgetCmsElementsWrite extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addRequiredOption('elements');
    
    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $elements = $this->getOption('elements');
    
    if (!is_array($elements))
    {
      throw new InvalidArgumentException('List of elements must be given as array');
    }
    
    $qp = new QueryPath($value);
    $dataFields = $qp->find('input[type="hidden"]');
    
    foreach($dataFields as $input)
    {
      $data = json_decode($input->attr('value'), true);
      var_dump($data);
    }
    
    
//     var_dump($x);
    
//     $dom = new DomDocument('1.0', 'utf-8');
//     $dom->validateOnParse = true;
//     $dom->loadHTML($value);
//     $c = new sfDomCssSelector($dom);
//     var_dump($c->matchAll('input[type="hidden"]')->getValues());
    
        
        
    return $value . '<textarea cols="80" rows="20">' . $value . '</textarea>';
  }
}

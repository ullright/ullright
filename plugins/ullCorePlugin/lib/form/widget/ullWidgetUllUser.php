<?php

class ullWidgetUllUser extends sfWidgetFormDoctrineSelect
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    $return = javascript_tag("
function filtery_" . $id . "(pattern, list){
    pattern = new RegExp('^'+pattern,\"i\");
    i = 0;
    sel = 0;
    while(i < list.options.length) {
      if (pattern.test(list.options[i].text)) {
            sel = i;
            break
        }
        i++;
    }
    list.options.selectedIndex = sel;
}
");

    $return .= input_tag($id . '_filter', null, array(
      'size' => '1',
      'id' => $id . '_filter',
      'onkeyup' => 'filtery_' .  $id . '(this.value, document.getElementById("' . $id . '"))'
    ));
    
    $return .= ' ';    
    
    $return .= parent::render($name, $value, $attributes, $errors);
    
    return $return;
  }

}

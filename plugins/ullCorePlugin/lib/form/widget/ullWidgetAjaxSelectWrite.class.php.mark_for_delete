<?php

class ullWidgetAjaxSelectWrite extends sfWidgetFormDoctrineSelect
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');        
    $name = $this->getAttribute('name');
    
    $output = '';    
//    $output .= '<div id="'. $id . '_flexbox"/>';
    $output = '<div id="' . $id . '_normal_select">';

    $output .= javascript_tag("
function filtery_" . $id . "(pattern, list)
{
    pattern = new RegExp(pattern,\"i\");
    i = 0;
    sel = 0;
    while(i < list.options.length) 
    {
      if (pattern.test(list.options[i].text)) 
      {
        sel = i;
        break
      }
      i++;
    }
    list.options.selectedIndex = sel;
}
");

    $output .= input_tag($id . '_filter', null, array(
      'size'    => '8',
      'id'      => $id . '_filter',
      'onkeyup' => 'filtery_' .  $id . '(this.value, document.getElementById("' . $id . '"))',
      'style'   => 'padding-left: 1.6em; background-image: url(' . ull_image_path('search') . '.png); background-repeat: no-repeat; background-position: 0.2em center;'
    ));
    
    $output .= ' ';    
    
    $output .= parent::render($name, $value, $attributes = array(), $errors = array());
    
    $output .= ' ';
    
    $output .= link_to_function(__('new', null, 'common'), $id . '_replace()');
    
    $output .= '</div>';
    
    $output .= javascript_tag('
function ' . $id . '_replace()
{
  $("#' . $id . '_normal_select")
    .empty()
    .append("<h1>Foo</h1>")
    .load("/")
  ;
//  replaceWith("<h1>Foo</h1>");
}
    ');
    
    

    
//    $output .= javascript_tag("
//var converted = new Ext.form.ComboBox({
//    typeAhead: true,
//    triggerAction: 'all',
//    transform: '$id',
//    forceSelection: true, 
//    emptyText: 'Please select...'
//    
//});

//    ");
    
    
//    $output .= javascript_tag('
//$("#' . $id . '").quickselect(
////  {
////    triggerSelected: true
////  }
//);
//    ');    
    
//    $output .= javascript_tag('
//$("#' . $id . '").sexyCombo(
//  {
////    triggerSelected: true
//  }
//);
//    ');
    

    
//    $output .= javascript_tag('
//$("#' . $id . '_flexbox").flexbox("/ullVentory/itemModels",
//  {
//    hiddenFieldId: "' . $id . '",
//    hiddenFieldName: "' . $name . '",
//    initialValue: ' . $value . '
//  }
//);
//
////$("#' . $id . '_flexbox_hidden").attr("id", "' . $id . '");
//    ');
       
    
    return $output;
  }
  
}

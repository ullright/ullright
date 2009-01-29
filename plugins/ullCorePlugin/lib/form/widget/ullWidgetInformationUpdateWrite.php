<?php

class ullWidgetInformationUpdateWrite extends ullWidget
{
  /* @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->setAttribute('rows', 5);
    $this->setAttribute('cols', 58);
  }
	
	public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '';
    
    if ($value) 
    {
      $return .= '<div class="ull_flow_fieldtype_information_update">' . nl2br($value) . '</div>';
    }    

    $return .= $this->renderContentTag('textarea', '', array_merge(array('name' => $name), $attributes));
    
    return $return; 
  }
  
  public function updateObject(Doctrine_Record $object, $values, $fieldName)
  {
  	$oldtext = $object->exists() ? $object->$fieldName : '';
    
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    $user = Doctrine::getTable('UllUser')->find($userId);
    $now = ull_format_datetime();
    
    if ($values[$fieldName]) {
	     $returnValue = "--------------------------------\n$user ($now):\n{$values[$fieldName]}\n";
	  
        // new line handling improvement
        if (substr($values[$fieldName], -1) <> "\n") {
          $returnValue .= "\n";
        }
       
        $returnValue .= $oldtext;
        $values[$fieldName] = $returnValue;
    }
    else
    {
      $values[$fieldName] = $oldtext;
    }
    
    return $values;
  }
}

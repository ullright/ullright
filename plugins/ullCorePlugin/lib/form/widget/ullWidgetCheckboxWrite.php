<?php

/**
 * Special checkbox widget
 * 
 * It always sets a <input type=hidden /> field with the same name before
 * the actual checkbox input field.
 * 
 * Reason: Unchecked checkboxes don't result in a (empty) post request key/value
 * pair. UllGenerator for example updates only columns for which a post request
 * parameter exists. Therefore it needs a param also for unchecked checkboxes,
 * otherwise unchecking wouldn't work.
 * 
 * @author klemens.ullmann@ull.at
 *
 */
class ullWidgetCheckboxWrite extends sfWidgetFormInputCheckbox
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $checkbox = parent::render($name, $value, $attributes, $errors);
    
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id') . '_hidden';
    
    $hidden = $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'id' => $id));
    
    return $hidden . $checkbox;
  }

}

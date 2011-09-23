<?php

/** 
 * User select box with additional links to user popup and inventory
 * 
 * @author klemens.ullmann-marx@ull.at
 * 
 * @deprecated: Use ullWidgetFormChoiceUllEntity with option show_user_link,
 *   show_inventory link instead.
 *
 */
class ullWidgetCaller extends sfWidgetFormSelectWithOptionAttributes
{

  /**
   * Constructor.
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('show_inventory_link', true);
  }   
  
  
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = parent::render($name, $value, $attributes, $errors);

    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    
    $id = $this->getAttribute('id');
    
    $user = Doctrine::getTable('UllUser')->find($value);
    
    $return .= ' &nbsp; ';
    $return .= link_to_function(__('Details', null, 'ullCoreMessages')
      , "caller_user_link(\"$id\")"      
    );
    
    $verticalSize = sfConfig::get('app_ull_user_user_popup_vertical_size', 720);
    
    if (!is_int($verticalSize))
    {
      throw new UnexpectedValueException('user_popup_vertical_size in app.yml must be an integer.');
    }
    
    $return .= javascript_tag('
      function caller_user_link(id) {
        var value = document.getElementById(id).value;
        var link = "' . url_for('ullUser/show') . '/" + value;
        var w=window.open(link,"User Popup","width=720,height=' . $verticalSize . '");
        w.focus();
      }
    ');

    
    // Inventory link
    if ($this->getOption('show_inventory_link'))
    {
      $return .= ' &nbsp; ';
      
      $return .= link_to_function(__('Inventory', null, 'ullVentoryMessages')
        , "caller_inv_link(\"$id\")"      
      );
      
      
      
      $return .= javascript_tag('
        function caller_inv_link(id) {
          var value = document.getElementById(id).value;
          var link = "' . url_for('ullVentory/list') . '/filter[ull_entity_id]/" + value;
          var w=window.open(link, "Inv Popup");
          w.focus();
        }
      ');  
    }
    
    return $return;
  }

}

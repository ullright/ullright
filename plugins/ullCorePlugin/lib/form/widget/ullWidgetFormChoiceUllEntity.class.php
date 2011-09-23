<?php 

/**
 * Edit widget for UllEntity selection
 * 
 * Extents doctrine choice widget even if we already supply the choices to
 * make use the inline functionalty from ullWidgetFormDoctrine
 *    
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullWidgetFormChoiceUllEntity extends ullWidgetFormDoctrineChoice
{
  
  /**
   * @see sfWidget
   */
  public function __construct($options = array(), $attributes = array())
  {
    // "Re"-support choices because sfWidgetFormDoctrineChoice removes them
    $choices = array();
    if (isset($options['choices']))
    {
      $choices = $options['choices'];
    }
    
    parent::__construct($options, $attributes);
    
    if ($choices)
    {
      $this->setOption('choices', $choices);
    }
  }
  
  
  /**
   * Constructor.
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('show_user_link', false);
    $this->addOption('show_inventory_link', false);
  }    
  
  /**
   * Render code
   * 
   * @see plugins/ullCorePlugin/lib/form/widget/ullWidgetFormDoctrineChoice::render()
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = parent::render($name, $value, $attributes, $errors);

    // Generate html_id
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    // User details link
    if ($this->getOption('show_user_link'))
    {
    
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
    }

    
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
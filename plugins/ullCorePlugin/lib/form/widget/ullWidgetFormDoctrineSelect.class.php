<?php 

/**
 * Enhancement to sfWidgetFormDoctrineSelect
 * 
 * Adds support for js search box which filters the select options
 *    
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullWidgetFormDoctrineSelect extends sfWidgetFormDoctrineSelect
{
  
  /**
   * Constructor.
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('show_search_box', false);
  }  

  /**
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/widget/sfWidgetFormSelect#render()
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '';
    
    if ($this->getOption('show_search_box') == true)
    {
      if (!$this->getAttribute('name'))
      {
        $this->setAttribute('name', $name);
      }
      
      $this->setAttributes($this->fixFormId($this->getAttributes()));
      $id = $this->getAttribute('id');
      
      $return .= javascript_tag('
$(document).ready(function()
{
  $("#' . $id . '").addSelectFilter();
});
      ');      
    }

    $return .= parent::render($name, $value, $attributes, $errors);
    
    return $return;
  }  
  
  
  /**
   * Natsort the choices
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    $choices = parent::getChoices();
    
    if (!$this->getOption('order_by'))
    {
      natsort($choices);
    }

    return $choices;
  }
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array(
      '/ullCorePlugin/js/jq/jquery-min.js', 
    );   
  }
  
  
}

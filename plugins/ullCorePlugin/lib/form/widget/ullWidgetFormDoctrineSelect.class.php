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

    //retrieve choices and sort them
    $choices = $this->getOption('choices')->call();
    natsort($choices);
    $this->setOption('choices', $choices);
    
    $return .= parent::render($name, $value, $attributes, $errors);
    
    return $return;
  }  
  
}

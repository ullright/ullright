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
    // Remember optional choices because sfWidgetFormDoctrineChoice deletes them
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
    
    $this->addOption('renderer_class', 'sfWidgetFormSelectWithOptionAttributes');
  }    
  
}
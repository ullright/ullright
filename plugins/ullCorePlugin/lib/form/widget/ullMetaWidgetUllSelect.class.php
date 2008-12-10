<?php
/**
 * ullMetaWidgetUllSelect 
 * 
 * 
 * available options:
 *  'ull_select' => string, slug of a UllSelect 
 *  'add_empty'  => boolean, true to include an empty entry
 */
class ullMetaWidgetUllSelect extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    if (!isset($columnConfig['widgetOptions']['ull_select']))
    {
      throw new InvalidArgumentException('option "ull_select" is required');
    }
    
    if ($columnConfig['access'] == 'w')
    {
      // query only children for the given ull select box
      $ullSelect = $columnConfig['widgetOptions']['ull_select'];
      unset($columnConfig['widgetOptions']['ull_select']);
      $q = new Doctrine_Query;
      $q
        ->from('UllSelectChild a')
        ->where('a.UllSelect.slug = ?', $ullSelect)
      ;
      
      $columnConfig['widgetOptions']['model'] = 'UllSelectChild';
      $columnConfig['widgetOptions']['order_by'] = array('sequence', 'asc');
      $columnConfig['widgetOptions']['query'] = $q;
      
      $columnConfig['validatorOptions']['model'] = 'UllSelectChild';
      $columnConfig['validatorOptions']['query'] = $q;      
      
      
      $this->sfWidget = new sfWidgetFormDoctrineSelect(
        $columnConfig['widgetOptions'],
        $columnConfig['widgetAttributes']
      );
      $this->sfValidator = new sfValidatorDoctrineChoice($columnConfig['validatorOptions']);
    }
    else
    {
      unset($columnConfig['widgetOptions']['add_empty']);
      
      $this->sfWidget = new ullWidgetUllSelect($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }
    
    //    var_dump($columnConfig);die;
  }  
}

?>
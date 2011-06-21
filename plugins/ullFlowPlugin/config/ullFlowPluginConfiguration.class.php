<?php

/**
 * Configures the ullFlow plugin.
 */
class ullFlowPluginConfiguration extends sfPluginConfiguration
{
  /**
   * Initialization
   */
  public function initialize()
  {
    $this->dispatcher->connect('ull_photo.get_columns_config', array($this, 'supplyUllPhotoColumnsConfig'));
  }
  
  public static function supplyUllPhotoColumnsConfig(sfEvent $event, $values)
  {
    $columnName = $values['column'];
    
    
    $column = Doctrine::getTable('UllFlowColumnConfig')->findOneBySlug($columnName);
    $app = $column->UllFlowApp;
    
    $columnsConfig = UllFlowDocColumnConfigCollection::build($app, new UllFlowDoc, 'w', 'edit');
    
    $values['columnsConfig'] = $columnsConfig;
    
    return $values;
  }

}

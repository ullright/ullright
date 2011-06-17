<?php

/**
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullMetaWidgetUllFlowAppLink extends ullMetaWidget
{

  protected
    $dispatcher
  ;
  
  protected static
    $columnConfigs = array()
  ;

  /**
   * Connect to form.update_object event
   * 
   * @param $columnConfig
   * @param sfForm $form
   * @return none
   */
  public function __construct($columnConfig, sfForm $form)
  {
    $this->dispatcher = sfContext::getInstance()->getEventDispatcher();

    $this->dispatcher->connect('form.update_object', array('ullMetaWidgetUllFlowAppLink', 'listenToUpdateObjectEvent'));
    
    self::$columnConfigs[$columnConfig->getColumnName()] = $columnConfig;

    parent::__construct($columnConfig, $form);
  }  
  
  protected function configure()
  {
  }
  
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetUllFlowAppLinkWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));
  }  
  
  /**
   * 
   * 
   * @param sfEvent $event
   * @param array $values
   * @return array
   */
  public static function listenToUpdateObjectEvent(sfEvent $event, $values)
  {
//    var_dump($values);
//    var_dump(get_class($event->getSubject()));
//    var_dump(self::$columnConfigs);
//    die;
    
    foreach ($values as $columnName => $value)
    {
      if (isset(self::$columnConfigs[$columnName]))
      {
        if (in_array($value, array('create_save', 'create_send')))
        {
          $columnConfig = self::$columnConfigs[$columnName];
          $doc = new UllFlowDoc();
          $doc->ull_flow_app_id = Doctrine::getTable('UllFlowApp')->findOneBySlug(
            $columnConfig->getWidgetOption('app')
          )->id;
            
          //copy the subject
          $parentDocAppId = $event->getSubject()->getObject()->ull_flow_app_id;
          $parentDocSubjectSlug = UllFlowColumnConfigTable::findSubjectColumnSlug($parentDocAppId);
          
          $docSubjectSlug = UllFlowColumnConfigTable::findSubjectColumnSlug($doc->ull_flow_app_id);
          
          $doc->$docSubjectSlug = $values[$parentDocSubjectSlug];
          
          if ('create_save' == $value)
          {
            $doc->save();
          }
          elseif ('create_send' == $value)
          {
            $action = Doctrine::getTable('UllFlowAction')->findOneBySlug('send');
            $doc->performActionAndSave($action);
          }
          
          $values[$columnName] = $doc->id;
        }
      }
    }
    
//    var_dump($values);
//    die;
    
    return $values;
  }  
}
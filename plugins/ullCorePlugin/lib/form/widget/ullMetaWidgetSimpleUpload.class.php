<?php
/**
 * ullMetaWidgetSimpleUpload
 *
 * Used for uploads for ullTableTool module
 */
class ullMetaWidgetSimpleUpload extends ullMetaWidget
{
  protected $path;

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

    $this->dispatcher->connect('form.update_object', array('ullMetaWidgetSimpleUpload', 'listenToUpdateObjectEvent'));

    parent::__construct($columnConfig, $form);
  }
  
  /**
   * deletes a file
   * 
   * @param sfEvent $event
   * @param array $values
   * @return array
   */
  public static function listenToUpdateObjectEvent(sfEvent $event, $values)
  {
    $object = $event->getSubject()->getObject();
    
    foreach ($values as $key => $value)
    {
      if (strstr($key, 'simple_upload_delete_')){
        if($value == true){
          $field = str_replace('simple_upload_delete_', '', $key);
          
          $validatorOptions = $event->getSubject()->getColumnsConfig()->offsetGet($field)->getValidatorOptions();
          
          unlink($validatorOptions['path'] . '/' . $object[$field]);
          
          $object[$field] = null;
          $object->save();
        }
      }
    }
    
    return $values;
  }
  
  
  protected function configure()
  {
    $this->columnConfig->removeValidatorOption('max_length');

    if (!$this->columnConfig->getValidatorOption('path'))
    {
      $uploadPath = sfConfig::get('sf_upload_dir') . '/tableTool/' . get_class($this->getForm()->getObject());
      $this->columnConfig->setValidatorOption('path', $uploadPath);
    }
    $this->path = $this->columnConfig->getValidatorOption('path');
    
    if (!$this->columnConfig->getValidatorOption('mime_types'))
    {
      $this->columnConfig->setValidatorOption('mime_types', 'web_images');
    }
    if (!$this->columnConfig->getValidatorOption('imageWidth'))
    {
      $this->columnConfig->setValidatorOption('imageWidth', 1000);
    }
    if (!$this->columnConfig->getValidatorOption('imageHeight'))
    {
      $this->columnConfig->setValidatorOption('imageHeight', 1000);
    }

  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureReadMode()
   */
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetSimpleUploadRead(
     array_merge($this->columnConfig->getWidgetOptions(), array('path' => $this->path)), 
     $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new sfValidatorPass());    
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureWriteMode()
   */
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetSimpleUploadWrite(
      array_merge($this->columnConfig->getWidgetOptions(), array('path' => $this->path)), 
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new ullValidatorFile($this->columnConfig->getValidatorOptions()));
    
    if($this->columnConfig->getOption('allow_delete')) 
    {
      $simpleUploadDeleteFieldName = 'simple_upload_delete_' . $this->columnConfig->getColumnName();
      
      $simpleUploadDeleteWidget = new ullWidgetCheckboxWrite();
      $simpleUploadDeleteWidget->setLabel($this->columnConfig->getOption('delete_label'));
      
      $this->addWidget(
        $simpleUploadDeleteWidget, 
        $simpleUploadDeleteFieldName
      );
      
      $this->addValidator(
        new sfValidatorBoolean(),
        $simpleUploadDeleteFieldName
      );
    }
  }
}

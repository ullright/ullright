<?php
/**
 * ullMetaWidgetSimpleUpload
 *
 * Used for uploads for ullTableTool module
 */
class ullMetaWidgetSimpleUpload extends ullMetaWidget
{
  protected
    $dispatcher
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

    $this->dispatcher->connect('form.update_object', array('ullMetaWidgetSimpleUpload', 'listenToUpdateObjectEvent'));

    parent::__construct($columnConfig, $form);
  }
  
  protected function configure()
  {
    $upload_path = $this->columnConfig->getOption('upload_path');
    $filetypes = $this->columnConfig->getOption('filetypes');
    $this->columnConfig->removeValidatorOption('max_length');

    if (!$upload_path)
    {
      $upload_path = 'tableTool/ullNews';
      // TODO: make upload_path dynamic
    }
    $this->columnConfig->setValidatorOption('path', $upload_path);
    
    if ($filetypes)
    {
      $this->columnConfig->setValidatorOption('filetypes', $filetypes);
    }
  }

  
  /**
   * Handle unchanged and empty (=removal) of password
   * 
   * @param sfEvent $event
   * @param array $values
   * @return array
   */
  public static function listenToUpdateObjectEvent(sfEvent $event, $values)
  {
    //$values['image_upload']->save('web/images/tableTool/ullNews/slug.jpg');
  }

  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureReadMode()
   */
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidget($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());    
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureWriteMode()
   */
  protected function configureWriteMode()
  {
   $this->addWidget(new ullWidgetSimpleUpload($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
   $this->addValidator(new sfValidatorFile($this->columnConfig->getValidatorOptions()));
   
  }
}

<?php
/**
 * form for ullPhoto
 *
 */
class ullPhotoEditForm extends sfForm
{

  
  /**
   * Configures the form
   *
   */
  public function configure()
  {
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
    
    $coordinates = array('x1', 'y1', 'width', 'height');
    foreach ($coordinates as $coordinate)
    {
      $this->widgetSchema[$coordinate] = new sfWidgetFormInputHidden();
      
      $this->validatorSchema[$coordinate] = new sfValidatorInteger(array(
        'required'   => false,
      ));
    }
    
    $this->widgetSchema['photo'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['photo'] = new sfValidatorString(array(
      'required'   => true,
    ));
    
    $columnConfig = new ullColumnConfiguration;
    $columnConfig
      ->setOption('entity_classes', array('UllUser'))
      ->setWidgetOption('add_empty', true)
      ->setValidatorOption('required', true)
    ;
    $metaWidget = new ullMetaWidgetUllEntity($columnConfig, $this);
    $metaWidget->addToFormAs('ull_user_id');
    $this->widgetSchema['ull_user_id']->setLabel(__('User', null, 'common'));    
  }
  
}

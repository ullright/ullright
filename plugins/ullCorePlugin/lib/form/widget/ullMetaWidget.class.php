<?php
/**
 * Base class for ullMetaWidgets
 *
 */
abstract class ullMetaWidget
{
  protected
    $columnConfig,
    $columnName,
    $form
  ;
  
  /**
   * constructor
   *
   * @param array $columnConfig
   * @param sfForm $form
   */
  public function __construct($columnConfig, sfForm $form)
  {
    $this->columnConfig = $columnConfig;
    $this->form = $form;
  }
  
  /**
   * Returns the form
   *
   * @return unknown
   */
  public function getForm()
  {
    return $this->form;
  }

  /**
   * Configures the form
   *
   * @param: string $columnName
   * 
   */
  public function addToFormAs($columnName)
  {
    $this->columnName = $columnName;
    $this->addToForm();
  }
  
  /**
   * Internal method to configure the form
   *
   */
  abstract protected function addToForm();
  
  /**
   * Add a widget to the form
   *
   * @param sfWidget $widget
   * @param string $columnName
   */
  protected function addWidget(sfWidget $widget, $columnName = null)
  {
    if ($columnName === null)
    {
      $columnName = $this->columnName;
    }
    $this->form->getWidgetSchema()->offsetSet($columnName, $widget);
  }
  
  /**
   * Add a validator to the form
   *
   * @param sfValidatorBase $validator
   * @param string $columnName
   */
  protected function addValidator(sfValidatorBase $validator, $columnName = null)
  {
    if ($columnName === null)
    {
      $columnName = $this->columnName;
    }
    $this->form->getValidatorSchema()->offsetSet($columnName, $validator);
  }
  
  protected function isWriteMode()
  {
    if ($this->columnConfig['access'] == 'w')
    {
      return true;
    }
  }

}

?>
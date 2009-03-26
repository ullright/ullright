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

    if (isset($this->columnConfig['unique']) &&
      $this->columnConfig['unique'] == true &&
      $this->isWriteMode())
    {
      $this->form->mergePostValidator(
      new sfValidatorDoctrineUnique(
        array(
          'model' => $this->form->getModelName(),
          'column' => $columnName,
          'required' => true
      )));
      
      $validator->setOption('required', true);
    }
    
    $this->form->getValidatorSchema()->offsetSet($columnName, $validator);
    
    
    /*

    if (!($this->form instanceof sfFormDoctrine))
    {
    throw new Exception("Unique validation only works with sfFormDoctrine forms.");
    }

    $validatorArray = new sfValidatorAnd(array($validator,
    new sfValidatorDoctrineUnique(array('model' => $this->form->getModelName(), 'column' => $columnName))));

    $this->form->getValidatorSchema()->offsetSet($columnName, $validatorArray);
    }
    else
    {*/
    //
    //}
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
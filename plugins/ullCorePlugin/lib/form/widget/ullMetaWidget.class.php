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
  protected function addToForm()
  {
    $this->configure();
    
    switch ($this->columnConfig['access'])
    {
      case 'w':
        $this->configureWriteMode();
        break;
      case 'r':
        $this->configureReadMode();
        break;
      case 's':
        $this->configureSearchMode();
        break;  
    }
  }
  
  /**
   * Generic configuration for all modes
   * @return none
   */
  protected function configure()
  {
  }
  
  
  /**
   * Configure form with default write mode (input text field)
   * @return none
   */
  protected function configureWriteMode()
  {
    if (!isset($this->columnConfig['widgetAttributes']['size']))
    {
      $this->columnConfig['widgetAttributes']['size'] = '50';
    }
    
    $this->addWidget(new sfWidgetFormInput($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']));
  }
  
  
  /**
   * Configure form with default read mode widget (display value)
   * @return none
   */  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidget($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorPass());    
  }
  
  
  /**
   * Configure form with default search mode (use write mode)
   * @return none
   */
  protected function configureSearchMode()
  {
    $this->configureWriteMode();
  }  
  
  
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

//    var_dump($this->getForm()->getObject()->toArray());
    
    // set unique validator and required for unique fields
    if (isset($this->columnConfig['unique']) &&
      $this->columnConfig['unique'] == true &&
      $this->columnConfig['access'] == 'w')
    {
      $this->form->mergePostValidator(
      new sfValidatorDoctrineUnique(
        array(
          'model' => $this->form->getModelName(),
          'column' => array($columnName),
          'throw_global_error' => false,
//          'id'  => $this->getForm()->getObject()->id
        ),
        array(
          'invalid' => __('Duplicate. Please use another value', null, 'common'). '.'
        )
      ));

      // handle "required" - don't set it for the primary key of a new object 
      if ($columnName == 'id' && !$this->getForm()->getObject()->exists())
      {
        $validator->setOption('required', false);
      }
      else
      {
        $validator->setOption('required', true);
      }
    }
    
    $this->form->getValidatorSchema()->offsetSet($columnName, $validator);
  }
  
  /**
   * deprecated
   * 
   * @return boolean
   */
  protected function isWriteMode()
  {
    if ($this->columnConfig['access'] == 'w' || $this->columnConfig['access'] == 's')
    {
      return true;
    }
  }

  public function getSearchPrefix()
  {
    return 'standard';
  }
}

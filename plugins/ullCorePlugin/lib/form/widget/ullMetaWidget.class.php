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
  public function __construct(ullColumnConfiguration $columnConfig, sfForm $form)
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
   * Get column config
   * 
   * @return array
   */
  public function getColumnConfig()
  {
    return $this->columnConfig;
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
    
    switch ($this->columnConfig->getAccess())
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
    $widgetAttributes = $this->columnConfig->getWidgetAttributes();
    if (!isset($widgetAttributes['size']))
    {
      $this->columnConfig->setWidgetAttribute('size', '50');
    }
    
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));
  }
  
  
  /**
   * Configure form with default read mode widget (display value)
   * @return none
   */  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidget($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
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
    
    if ($this->columnConfig->getWidgetOption('is_hidden'))
    {
      $widget = new sfWidgetFormInputHidden();
    }

    $widget = $this->handleAddEmpty($widget);
    
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
    
    // set unique validator and required for unique fields
    if (($this->columnConfig->getUnique() == true) && ($this->columnConfig->getAccess() == 'w'))
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
    
    
    // why isn't it necesarry to add "empty" also to the validators?
    
//    if ($widget instanceof sfWidgetFormDoctrineSelect && $this->getColumnConfig()->getAccess() == 's')
//    {
//      $widget->setOption('add_empty', true); 
//    }    
    
    
    $this->form->getValidatorSchema()->offsetSet($columnName, $validator);
  }
  
  /**
   * deprecated
   * 
   * @return boolean
   */
  protected function isWriteMode()
  {
    if ($this->columnConfig->getAccess() == 'w' || $this->columnConfig->getAccess() == 's')
    {
      return true;
    }
  }
  
  /**
   * This function is used by the ull search framework to
   * determine the search behaviour of the type wrapped by
   * a meta widget.
   * 
   * @return One of the following: 'range', 'foreign',
   *         'boolean', 'standard'.
   */
  public function getSearchType()
  {
    return 'standard';
  }
  
  /**
   * Returns the fitting meta widget class name for a given
   * database type. Fallback to type "string" if a unknown type is given
   * 
   * @param $type the database type.
   * @return string the meta widget class name
   */
  public static function getMetaWidgetClassName($type)
  {
    switch ($type)
    {
      case 'clob':
        return 'ullMetaWidgetTextarea';

      case 'integer':
        return 'ullMetaWidgetInteger';
        
      case 'float':
        return 'ullMetaWidgetFloat';        

      case 'timestamp':
        return 'ullMetaWidgetDateTime';
        
      case 'date':
        return 'ullMetaWidgetDate';        

      case 'boolean':
        return 'ullMetaWidgetCheckbox';
        
      default:
        return 'ullMetaWidgetString';
    }
  }
  
  /**
   * Forces a empty entry for select boxes in search mode
   * 
   * @param $widget
   * @return sfWidgetForm
   */
  protected function handleAddEmpty(sfWidgetForm $widget)
  {
    if ($this->getColumnConfig()->getAccess() == 's')
    {
      // Set the add_empty option if available. Mainly for sfWidgetFormDoctrineSelect
      if ($widget->hasOption('add_empty'))
      {
        $widget->setOption('add_empty', true); 
      }
      
      // Also add an empty first entry to a normal sfWidgetFormSelect
      if ($widget instanceof sfWidgetFormSelect)
      {
        // sfWidgetFormDoctrineSelect is also an instance of sfWidgetFormSelect,
        //   so we check if the choices are an array (sfCallable for Doctrine)
        if (is_array($choices = $widget->getOption('choices')))
        {
          if (!in_array('', $choices))
          {
            //don't use array_merge here, would reindex the array
            $choices = array('' => '') + $choices;
          }
          
          $widget->setOption('choices', $choices);
        }
      }
    }
    return $widget;
  }
  
}

<?php

/**
 * This class represents a form which provides a list
 * of all searchable columns in an ullSearch.
 * 
 * Works in combination with the *SearchConfig classes.
 * Inherits form sfForm.
 */
class ullSearchAddCriteriaForm extends sfForm
{
  private $searchConfig;
  private $searchGenerator;
  
  /**
   * Returns a new instance of the ullSearchAddCriteriaForm
   * class. Needs ullSearchConfig and ullSearchGenerator instances.
   * 
   * @param $searchConfig The search config
   * @param $searchGenerator The search generator
   * @return ullSearchAddCriteriaForm The new instance
   */
  public function __construct(ullSearchConfig $searchConfig, ullSearchGenerator $searchGenerator)
  {
    $this->searchConfig = $searchConfig;
    $this->searchGenerator = $searchGenerator;
    parent::__construct();
  }

  /**
   * This function is responsible for form configuration.
   * The search configuration provides a list of all searchable
   * columns which is in turn converted to a drop-down box.
   */
  public function configure()
  {
    $choices = array();

    $searchFormEntries = $this->searchConfig->getAllSearchableColumns();
    
    foreach($searchFormEntries as $sfe)
    {
      $label = $this->searchGenerator->getColumnLabel($sfe->__toString());
      
      $choices[$sfe->__toString()] = ($label != null) ? $label : $sfe->__toString();
      
    }
    
    natsort($choices);

    $this->setWidget('columnSelect', new sfWidgetFormSelect(array('choices' => $choices), array('onchange'  => 'document.getElementById(\'addSubmit\').click()')));
    $this->widgetSchema->setLabel('columnSelect', __('Criterion', null, 'common'));

    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
  }
}
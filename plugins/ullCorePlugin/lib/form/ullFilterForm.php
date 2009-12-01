<?php

class ullFilterForm extends sfForm
{
  protected
    $bindBlacklist = array(
      'search',
    )
  ;
  
  public function configure()
  {
    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array('size' => '15',
                                                        'title' => __('Search', null, 'common'))),
    ));

    $this->widgetSchema->setLabels(array(
      'search'  => __('Search', null, 'common')
    ));
    
    $this->setValidators(array(
      'search'  => new sfValidatorPass(),
    ));
    
    $this->getWidgetSchema()->setNameFormat('filter[%s]');
    
    $this->getWidgetSchema()->setFormFormatterName('ullUnorderedList');
  }
  
  
  /**
   * Blacklist some values
   * 
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/form/sfForm#bind($taintedValues, $taintedFiles)
   */
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if (isset($taintedValues))
    {
      foreach ($taintedValues as $key => $taintedValue)
      {
        if (in_array($key, $this->bindBlacklist))
        {
          unset($taintedValues[$key]);
        }
      }
    }
    
    parent::bind($taintedValues, $taintedFiles);
  }
  
  
}

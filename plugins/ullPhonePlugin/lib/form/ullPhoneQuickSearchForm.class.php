<?php
/**
 * This class configures a sfForm object for usage
 * as a small quicksearch box in the sidebar.
 *
 */
class ullPhoneQuickSearchForm extends sfForm
{
  public function configure()
  {
//    $searchWidget = new sfWidgetFormJQueryAutocompleter(array(
//        'url' => sfContext::getInstance()->getController()->genUrl('ullUser/userSearchAutocomplete'),
//        'config' => '{ minChars:2, highlight:false }',
//    ), array('size' => 14)); 

    $searchWidget = new sfWidgetFormInput(array(), array('size' => 11));
    
    $this->widgetSchema['search'] = $searchWidget;
    
    $this->validatorSchema['search'] = new sfValidatorString(array('required' => false));
    
    $this->widgetSchema->setNameFormat('filter[%s]');
    
    $this->widgetSchema->setLabels(array(
      'search' => 'Search: ' ));
  }
}
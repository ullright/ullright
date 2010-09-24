<?php

/**
 * ullMetaWidgetUllUserStatus
 *
 *
 */
class ullMetaWidgetUllUserStatus extends ullMetaWidgetForeignKey
{

  protected function configureWriteMode()
  {
    $this->parseOptions();
    
    $this->columnConfig->setWidgetOption('choices', $this->findChoices());
    $this->columnConfig->removeWidgetOption('model');
    $this->columnConfig->removeWidgetOption('enable_inline_adding');
    $this->addWidget(new sfWidgetFormSelectWithOptionAttributes($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    
    //this has to be set, always.
    //see #1147 for details
    $this->columnConfig->setValidatorOption('model', 'UllUserStatus');
    $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig->getValidatorOptions()));
    
    $this->handleAllowCreate();
  }
  

  /**
   * To create an array with UllUserStatus and the costumize color to each Status
   * 
   * @return array Array with status-name and attributes 
   */
  protected function findChoices()
  {
  $q = new Doctrine_Query;
    $q
      ->from('UllUserStatus')
    ;
    $results = $q->execute();
    $choices = array();
    
    foreach($results as $result)
    {
      $choices[$result->id]['name'] = $result->name;
      if(!$result->is_active)
      {
        $choices[$result->id]['attributes']['class'] = 'color_inactiv_bg_ull_entity_widget';
      }
      if($result->is_absent)
      {
        $choices[$result->id]['attributes']['class'] = 'color_absent_bg_ull_entity_widget';
      } 
      
    }
   
    return $choices;
  }
}
<?php
/**
 * ullMetaWidgetUllVentorySoftware
 *
 */
class ullMetaWidgetUllVentorySoftware extends ullMetaWidget
{
  
  protected function configureWriteMode()
  {
    $software = UllVentorySoftwareTable::findOrderedByName();
    
    $choices = array('' => '');
    foreach($software as $singleSoftware)
    {
      $choices[$singleSoftware->id] = $singleSoftware->name;
    } 
    
    $this->addWidget(new sfWidgetFormSelect(
      array_merge(array('choices' => $choices), $this->columnConfig->getWidgetOptions()),
      $this->columnConfig->getWidgetAttributes()
    ));

    $this->addValidator(new sfValidatorChoice(
      array_merge(array('choices' => array_keys($choices)), $this->columnConfig->getValidatorOptions()))
    );
  }
  
}

<?php

class BaseUllWidgetComponents extends sfComponents
{
  
  /**
   * For ullWidgetContentElements
   */
  public function executeUllContentElementForm()
  {
    $generator = new ullContentElementGenerator(
      $this->element_data['type'],
      $this->element_data['id']
    );
    $generator->buildForm(new UllContentElement());
    
    $form = $generator->getForm();
    $form->setDefaults($this->element_data['values']);  

    $this->generator = $generator;
  }
  
}

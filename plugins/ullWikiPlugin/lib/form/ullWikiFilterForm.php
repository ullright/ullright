<?php

class ullWikiFilterForm extends ullFilterForm
{
  public function configure()
  {
    parent::configure();
    
    $this->getWidget('search')->setAttribute('size', 14);
    $this->getWidget('search')->setAttribute('title', 
      __('Searches for ID, subject and tags', null, 'common')
    );
        
    $this->getWidgetSchema()->offsetSet(
      'fulltext', new sfWidgetFormInputCheckbox(array(), array('value' => '1'))
    );
    
    $this->getValidatorSchema()->offsetSet(
      'fulltext', new sfValidatorBoolean()
    );
    
    $this->getWidgetSchema()->setLabel(
      'fulltext', __('Full text', null, 'common')
    );
    
    $this->getWidgetSchema()->offsetSet(
      'outdated', new sfWidgetFormInputCheckbox(array(), array('value' => '1'))
    );
    
    $this->getValidatorSchema()->offsetSet(
      'outdated', new sfValidatorBoolean()
    );
    
    $this->getWidgetSchema()->setLabel(
      'outdated', __('Include outdated', null, 'common')
    );
  }
}

<?php

class ullCourseEmailForm extends sfForm
{

  public function configure()
  {
    $this->setWidgets(array(
      'recipients'  => new ullWidget(),
      'subject'    => new sfWidgetFormInput(array(), array('size' => 60)),
      'body'    => new sfWidgetFormTextarea(array(), array('cols' => 80, 'rows' => 15)),
    ));
   
    $this->setValidators(array(
      'recipients' => new sfValidatorPass(),
      'subject'  => new sfValidatorString(array('required' => true)),
      'body'    => new sfValidatorString(array('required' => true)),
    ));
    
    $this->getWidgetSchema()->setLabels(array(
      'recipients'  => __('Recipients', null, 'common'),
      'subject'    => __('Subject', null, 'common') . ' *',
      'body'    => __('Body', null, 'common'). ' *',
    ));
   
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
  }
  
}

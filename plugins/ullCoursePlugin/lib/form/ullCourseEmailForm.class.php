<?php

class ullCourseEmailForm extends sfForm
{

  public function configure()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
    
    $this->setWidgets(array(
      'recipients'  => new ullWidget(),
      'subject'    => new sfWidgetFormInput(array(), array('size' => 60)),
      'body'    => new sfWidgetFormTextarea(array(), array('cols' => 80, 'rows' => 15)),
      'sms'    => new sfWidgetFormTextarea(array(), array('cols' => 80, 'rows' => 2)),
    ));
   
    $this->setValidators(array(
      'recipients' => new sfValidatorPass(),
      'subject'  => new sfValidatorString(array('required' => true)),
      'body'    => new sfValidatorString(array('required' => true)),
      'sms'    => new sfValidatorString(),
    ));
    
    $this->getWidgetSchema()->setLabels(array(
      'recipients'  => __('Recipients', null, 'common'),
      'subject'    => __('Subject', null, 'common') . ' *',
      'body'    => __('Body', null, 'common'). ' *',
      'sms'    => __('SMS text', null, 'ullCourseMessages'),
    ));
    
    $this->getWidgetSchema()->setHelps(array(
      'sms'    => __('Leave empty if you do not want to send SMS', null, 'ullCourseMessages'),
    ));
    
    $this->getWidgetSchema()->setDefaults(array(
      'sms'    => get_partial('ullCourse/smsCancel') 
    ));    
    
   
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
  }
  
}

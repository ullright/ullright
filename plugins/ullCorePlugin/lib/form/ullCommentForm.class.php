<?php

/**
 * This class represents a simple form for users to
 * add a comment to an object, usually used in
 * combination with the ullCommentable behavior
 */
class ullCommentForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'comment_text'   => new sfWidgetFormTextarea(array(), array(
        'cols'  => 50,
        'rows'  => 6,
      )),
    ));
    
    $this->getWidgetSchema()->setLabels(
      array('comment_text' => __('My comment', null, 'ullCoreMessages'))
    );

    $this->setValidators(array(
      //this value should be retrieved from ullCommentable,
      //since it's configurable there
      'comment_text' => new sfValidatorString(
        array('max_length' => 1000),
        array('max_length' => 'Your input is too long (%max_length% characters max)'))
    ));
    
    //if ullCommentForm is used in conjunction with other forms,
    //we have to allow extra form fields
    $this->getValidatorSchema()->setOption('allow_extra_fields', true);
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
  }
}
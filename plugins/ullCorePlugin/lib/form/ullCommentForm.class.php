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
      'comment_text'   => new sfWidgetFormTextarea(),
    ));
    
    $this->getWidgetSchema()->setLabels(
      array('comment_text' => __('Your comment', null, 'ullCoreMessages'))
    );

    $this->setValidators(array(
      //this value should be retrieved from ullCommentable,
      //since it's configurable there
      'comment_text' => new sfValidatorString(
        array('max_length' => 1000),
        array('max_length' => 'Your input is too long (%max_length% characters max)'))
    ));
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
  }
}
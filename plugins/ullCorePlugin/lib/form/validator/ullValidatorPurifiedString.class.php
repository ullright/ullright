<?php

/**
 *
 */
class ullValidatorPurifiedString extends sfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    
    $this->addMessage('invalid_character', 'Contains invalid characters.');
  }
  
  protected function doClean($value)
  {
    //do we really need to use the HTML purifier here?
    //isn't checking for a character set (<, >, ", ...)
    //enough? potential speed issues?
 
    if (ullHTMLPurifier::purifyForSecurity($value) != $value)
    {
      throw new sfValidatorError($this, 'invalid_character');
    }
    
    return parent::doClean($value);
  }
}

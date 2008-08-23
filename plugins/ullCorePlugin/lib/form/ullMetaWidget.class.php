<?php

abstract class ullMetaWidget
{
  //TODO: extend sfWidget because of handy options checking system?
  
  protected
    $sfWidget,
    $sfValidator
  ;
  
  abstract public function __construct($options = array());
  
  public function getSfWidget()
  {
    return $this->sfWidget;
  }

  public function getSfValidator()
  {
    return $this->sfValidator;
  }
}

?>
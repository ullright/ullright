<?php

abstract class ullMetaWidget
{
  protected
    $sfWidget,
    $sfValidator
  ;
  
  abstract public function __construct($parameter = array());
  
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
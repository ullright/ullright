<?php
/**
 * Base class for ullMetaWidgets
 *
 */
abstract class ullMetaWidget
{
  protected
    $sfWidget,
    $sfValidator
  ;
  
  /**
   * constructor
   *
   * @param array $columnConfig
   */
  abstract public function __construct($columnConfig = array());
  
  /**
   * get widget
   *
   * @return object a widget
   */
  public function getSfWidget()
  {
    return $this->sfWidget;
  }

  /**
   * get validator
   *
   * @return object a validator
   */
  public function getSfValidator()
  {
    return $this->sfValidator;
  }
}

?>
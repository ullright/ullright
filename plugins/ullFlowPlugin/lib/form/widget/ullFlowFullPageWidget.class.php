<?php

/**
 * Base class for all full page widgets
 * 
 */

abstract class ullFlowFullPageWidget
{
  
  protected
    $doc,
    $column
  ;

  public function __construct(UllFlowDoc $doc, $column)
  {
    $this->doc = $doc;
    $this->column = $column;
  }

  /**
   * Returns a valid internal symfony uri
   * 
   * Example: ullFlow/upload 
   */
  abstract public function getInternalUri(); 
  
}
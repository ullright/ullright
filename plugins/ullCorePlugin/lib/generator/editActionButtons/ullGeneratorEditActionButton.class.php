<?php

/**
 * Represents an edit action button
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
abstract class ullGeneratorEditActionButton
{
  protected
    $action
  ;

  /**
   * Constructor
   * 
   * @param sfRequest $request
   * @return none
   */
  public function __construct(sfAction $action)
  {
    $this->action = $action;
  }
  
  /**
   * Render the button in the edit action template
   * 
   * @return string
   */
  abstract public function render();
  
  
  /**
   * Execute logic
   * @return unknown_type
   */
  abstract public function executePostFormBindAndSave();
  
}
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
    $action, 
    
    // primary = main action on the left side, otherwise render as secondory
    // action on the right side
    $isPrimary = true    
  ;

  /**
   * Constructor
   * 
   * @param sfRequest $request
   * @return none
   */
  public function __construct(sfAction $action, $isPrimary = true)
  {
    $this->action = $action;
    $this->isPrimary = (boolean) $isPrimary;
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
  

  /**
   * To choose the side on witch the button will be rendered
   */
  public function isPrimary(){
    return (boolean) $this->isPrimary;
  }
  
}
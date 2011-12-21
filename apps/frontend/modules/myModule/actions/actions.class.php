<?php

/**
 * myModule actions.
 *
 * @package    myProject
 * @subpackage myModule
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class myModuleActions extends ullsfActions
{
  
  /**
   * Executes index action
   *
   */
  public function executeIndex($request)
  {
    $this->redirect('ullCms/show?slug=homepage');
  }
  
  
  /**
   * Toogle between webapp and website example layouts
   * 
   * @param sfRequest $request
   */
  public function executeToggleLayout($request)
  {
    $layout = $this->getUser()->getAttribute('layout');
    
    if ($layout == 'layout_webapp')
    {
      $layout = 'layout_website';
    }
    else
    {
      $layout = 'layout_webapp';
    }
    
    $this->getUser()->setAttribute('layout', $layout);
    
    $this->redirect('@homepage');
  }
  
  
}

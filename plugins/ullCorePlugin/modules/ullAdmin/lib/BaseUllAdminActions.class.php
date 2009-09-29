<?php

/**
 * user actions.
 *
 * @package    ull_at
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseUllAdminActions extends ullsfActions
{

  public function executeIndex() 
  {
    $this->checkAccess('Masteradmins');
    
    $this->breadcrumbTree = new ullAdminBreadcrumbTree;
    
    $this->form = new ullTableToolFilterForm;
  }
  
}

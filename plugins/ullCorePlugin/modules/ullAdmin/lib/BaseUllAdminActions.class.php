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
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add('UllAdmin', 'UllAdmin/index');    
    
    $this->checkAccess('Masteradmins');
  }
  
}

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
    $this->checkAccess(array('MasterAdmins', 'UserAdmins'));
    
    $this->breadcrumbTree = new ullAdminBreadcrumbTree;
    
    $this->form = new ullUserFilterForm;
    
    $this->setVar('named_queries', new ullNamedQueriesUllUser, true);
    
    $this->is_master_admin = UllUserTable::hasGroup('Masteradmins');
  }
  
  public function executeAbout()
  {
    $text = file_get_contents(sfConfig::get('sf_plugins_dir') . '/ullCorePlugin/config/ullCorePluginConfiguration.class.php');
    preg_match('/\$Rev: (.*) \$/', $text, $matches);
    $this->revision = $matches[1];
    
    $this->breadcrumbForAbout();
  }

  protected function breadcrumbForAbout()
  {
    $breadcrumbTree = new ullAdminBreadcrumbTree;
    $breadcrumbTree->add(__('About', null, 'ullCoreMessages'));
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
}

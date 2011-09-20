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
    $this->checkAccess('ull_admin_index');
    
    $this->breadcrumbForIndex();
    
    $this->form = new ullUserFilterForm;
    
    $this->loadPopularTags();
    
    $this->loadNamedQueries();
    
    $this->is_master_admin = UllUserTable::hasGroup('Masteradmins');
    
    $this->loadModuleAdminLinks();
  }
  
  public function executeAbout()
  {
    $text = file_get_contents(sfConfig::get('sf_plugins_dir') . '/ullCorePlugin/config/ullCorePluginConfiguration.class.php');
    preg_match('/\$Rev: (.*) \$/', $text, $matches);
    $this->revision = $matches[1];
    
    $this->breadcrumbForAbout();
  }
  
  protected function loadModuleAdminLinks()
  {
    $modules = sfConfig::get('sf_enabled_modules');
    ksort($modules);
    $modules = array_flip($modules);
    
    $modules = ullCoreTools::orderArrayByArray($modules, sfConfig::get('app_admin_module_links_order', array()), false);
    
    $this->modules = array_keys($modules);
    
  }

  protected function breadcrumbForAbout()
  {
    $breadcrumbTree = new ullAdminBreadcrumbTree;
    $breadcrumbTree->add(__('About', null, 'ullCoreMessages'));
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
  
  protected function breadcrumbForIndex()
  {
    $breadcrumbTree = new ullAdminBreadcrumbTree;
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
  
  /**
   * Load named queries for index and list action
   * 
   * @return none
   */
  protected function loadNamedQueries()
  {
    $this->setVar('named_queries', new ullNamedQueriesUllUser, true);
    
    if (class_exists('ullNamedQueriesUllUserCustom'))
    {
      $this->setVar('named_queries_custom', new ullNamedQueriesUllUserCustom(), true);
    }
    else
    {
      $this->named_queries_custom = null;
    }
  }  
  
  /**
   * Query popular tags for the index action
   */
  protected function loadPopularTags()
  {
    $q = new Doctrine_Query;
    $q->from('Tagging tg, tg.Tag t, tg.UllUser x');
    $q->limit(sfConfig::get('app_sfDoctrineActAsTaggablePlugin_limit', 100));
    $this->tags_pop = TagTable::getPopulars($q, array('model' => 'UllUser'));
    $this->tagurl = str_replace('%25', '%', ull_url_for(array('module' => 'ullUser', 'action' => 'list', 'filter[search]' => '%s')));
  }  
}

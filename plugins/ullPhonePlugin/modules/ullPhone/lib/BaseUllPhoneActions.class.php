<?php

/**
 * ullPhone actions.
 *
 * @package    ullright
 * @subpackage ullPhone
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllPhoneActions extends ullsfActions
{

  /**
   * Execute  before each action
   *
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function ullpreExecute()
  {
    $defaultUri = $this->getModuleName() . '/list';
    $this->getUriMemory()->setDefault($defaultUri);

    //Add ullPhone stylsheet for all actions
    $path = '/ullPhoneTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }


  /**
   * Execute index action
   *
   */
  public function executeIndex()
  {
    $this->checkAccess('LoggedIn');

    $this->breadcrumbForIndex();
  }


  /**
   * Execute list action
   *
   * @param $request
   * @return unknown_type
   */
  public function executeList(sfRequest $request)
  {
    $this->checkAccess('LoggedIn');

    $this->breadcrumbForList();

    $this->generator = new ullPhoneGenerator();
    
    //do we have a POST from the sidebar's quick search box?
    if ($request->isMethod('post'))
    {
      $this->phoneSearchFilter = $request->getParameter('autocomplete_sidebarPhoneSearch');
    }
    
    $this->isLocationView = $this->getRequestParameter('locationView');
    
    if ($this->isLocationView)
    {
      $this->generator->getColumnsConfig()->offsetGet('ull_location_id')->setAutoRender(false);
      
      if ($this->filter_location_id = $this->getRequestParameter('ull_location_id'))
      {
        $this->location = Doctrine::getTable('UllLocation')
          ->findOneById($this->filter_location_id,  Doctrine::HYDRATE_ARRAY);

        $this->forward404Unless($this->location != null);
      }
    }

    $rows = $this->getFilterFromRequest();

    $this->generator->buildForm($rows);
  }

  protected function getFilterFromRequest()
  {

    $q = new Doctrine_Query();
    $q
      ->from('UllUser x, x.UllLocation l')
      //the following query includes phone and fax extensions, but overrides
      //the columns with a dash if the matching boolean is false
      ->select('x.first_name, x.last_name, x.email,
          l.name,
          if(x.is_show_extension_in_phonebook is not false, x.phone_extension, \'-\') as phone_extension,
          if(x.is_show_fax_extension_in_phonebook is not false, x.fax_extension, \'-\') as fax_extension')
    ; 
    
    if (!empty($this->filter_location_id))
    {
      $q->addWhere('x.ull_location_id = ?', $this->filter_location_id);
      //$this->ull_filter->add('ull_location_id', 'Location: ' . $this->location['name']);
    }
    
    if (!empty($this->phoneSearchFilter))
    {
      $searchColumns = array('first_name', 'last_name', 'phone_extension', 'fax_extension',
        'UllLocation.name', 'UllLocation.short_name', 'UllLocation.phone_base_no',
        'UllDepartment.name', 'UllJobTitle.name');
      
      ullCoreTools::doctrineSearch($q, $this->phoneSearchFilter, $searchColumns); 
      
      //      //is concat mysql only?
//      $q
//        ->where('concat(x.last_name, \' \', x.first_name) like ?', '%' . $this->phoneSearchFilter . '%')
//        ->orWhere('concat(x.first_name, \' \', x.last_name) like ?', '%' . $this->phoneSearchFilter . '%')
//      ;
    }
    
    $defaultOrder = 'last_name';

    if ($this->isLocationView)
    {
      $q->orderBy('l.name');
    }
      
    $this->order = $this->getRequestParameter('order', $defaultOrder);
    $this->order_dir = $this->getRequestParameter('order_dir', 'asc');

    $orderDir = ($this->order_dir == 'desc') ? 'DESC' : 'ASC';

    switch ($this->order)
    {
      case 'creator_user_id':
        $q->addOrderBy('x.Creator.display_name ' . $orderDir);
        break;
      case 'updator_user_id':
        $q->addOrderBy('x.Updator.display_name ' . $orderDir);
        break;
      default:
        if (strpos($this->order, '_translation_'))
        {
          $a = explode('_', $this->order);
          $q->addOrderBy('x.Translation.' . $a[0] . ' ' . $orderDir);
        }
        else
        {
          $q->addOrderBy($this->order . ' ' . $orderDir);
        }
    }
    
    //printQuery($q->getSql());
    
    $this->pager = new Doctrine_Pager(
    $q,
    $this->getRequestParameter('page', 1),
    sfConfig::get('app_pager_max_per_page')
    );
    $rows = $this->pager->execute();
    
    //var_dump($rows->toArray());
    
    return ($rows->count()) ? $rows : new UllUser();
  }

  /**
   * Create breadcrumbs for index action
   *
   */
  protected function breadcrumbForIndex()
  {
    $this->breadcrumbTree = new ullPhoneBreadcrumbTree();
  }

  /**
   * Create breadcrumbs for list action
   *
   */
  protected function breadcrumbForList()
  {
    $this->breadcrumbTree = new ullPhoneBreadcrumbTree();
    $this->breadcrumbTree->addDefaultListEntry();
  }
}
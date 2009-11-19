<?php

/**
 * ullPhone actions.
 *
 * @package    ullright
 * @subpackage ullPhone
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllPhoneActions extends BaseUllGeneratorActions
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
  public function executeIndex(sfRequest $request)
  {
    $this->redirect('ullPhone/list');
  }


  /**
   * Execute list action
   *
   * @param $request
   * @return unknown_type
   */
  public function executeList(sfRequest $request)
  {
    $this->checkPermission('ull_phone_list');

    $this->breadcrumbForList();

    $this->generator = new ullPhoneGenerator();

    //search term set?
    $this->phoneSearchFilter = $request->getParameter('filter[search]');

    //shall we render location headers?
    $this->isLocationView = $this->getRequestParameter('locationView');

    if ($this->isLocationView)
    {
      //the view needs the location column's content but we don't want
      //the generator to render it since the data is already in the location header
      $this->generator->getColumnsConfig()->offsetGet('UllLocation->name')->setAutoRender(false);

      if ($this->filter_location_id = $this->getRequestParameter('ull_location_id'))
      {
        $this
          ->location = Doctrine::getTable('UllLocation')
          ->findOneById($this->filter_location_id,  Doctrine::HYDRATE_ARRAY)
        ;

        $this->forward404Unless($this->location != null);
      }
    }

    $rows = $this->getFilterFromRequest();

    $this->generator->buildForm($rows);
  }
  
  
  /**
   * Execute show action
   *
   */
  public function executeShow(sfRequest $request)
  {
    $this->redirect('ullPhone/list');
  }

  
  /**
   * Execute create action
   *
   */
  public function executeCreate(sfRequest $request)
  {
    $this->redirect('ullPhone/list');
  }    
  
  
  /**
   * Execute edit action
   *
   */
  public function executeEdit(sfRequest $request)
  {
    $this->redirect('ullPhone/list');
  }    
  
  
  /**
   * Execute delete action
   *
   */
  public function executeDelete(sfRequest $request)
  {
    $this->redirect('ullPhone/list');
  }    
  

  
  /**
   * Apply custom modifications to the query
   *
   * This function builds a query selecting UllUsers for the phone book;
   * see inline comments for further details.
   */
  protected function modifyQueryForFilter()
  {
    // _                    _
    //| |                  | |
    //| |__   _____   ____ | |  _
    //|  _ \ (____ | / ___)| |_/ )
    //| | | |/ ___ |( (___ |  _ (
    //|_| |_|\_____| \____)|_| \_)
    //
    // why do we need to add this here?
    //
    // problem query like:
    // ->from('UllUser u, u.UllLocation l, u.UllCompany c)
    // ->select('u.last_name, l.name, c.name)
    // will throw exception
    // adding u.ull_location_id and u.ull_company_id does not help
    //
    // why does adding u.* resolve this?

    //the following select includes phone and fax extensions, but overrides
    //the columns with a dash if the matching boolean is false
    $this->q->getDoctrineQuery()->addSelect('x.*, ' .
      'if(x.is_show_extension_in_phonebook is not false, x.phone_extension, \'-\') as phone_extension, ' .
      'if(x.is_show_mobile_number_in_phonebook is not false, x.mobile_number, \'-\') as mobile_number');
    
    if ($this->isLocationView)
    {
      //order results by location name first
      $this->q->addOrderByPrefix('UllLocation->name');
    }

    if (!empty($this->filter_location_id))
    {
      $this->q->addWhere('ull_location_id = ?', $this->filter_location_id);
    }

    if (!empty($this->phoneSearchFilter))
    {
      //doctrineSearch was here
      //BaseUllGeneratorActions handles this now
      
      //we need special handling here because we don't want hidden
      //numbers to be searchable
      $this->q->orWhere('is_show_extension_in_phonebook is not FALSE ' .
        'AND phone_extension LIKE ?', '%' . $this->phoneSearchFilter . '%');

      $this->q->orWhere('is_show_mobile_number_in_phonebook is not FALSE ' .
        'AND mobile_number LIKE ?', '%' . $this->phoneSearchFilter . '%');
    }
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

  /**
   * Configure the ullFilter class name
   *
   * @return string
   */
  public function getUllFilterClassName()
  {
    return 'ullPhoneQuickSearchForm';
  }

  /**
   * Get array of columns for the quicksearch
   * We do not use UllUser's table config here,
   * but a user configurable setting.
   *
   * @return array
   */
  protected function getSearchColumnsForFilter()
  {
    //return array('first_name, last_name');
    //doctrinesearch is not ready for this
    return array(
      'first_name',
      'last_name',
      'UllLocation->name',
      'UllLocation->short_name',
      'UllLocation->phone_base_no',
      'UllDepartment->name'
    );
  }
}
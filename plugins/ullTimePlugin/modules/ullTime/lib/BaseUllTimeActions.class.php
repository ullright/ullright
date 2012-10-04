<?php

/**
 * ullTime actions.
 *
 * @package    ullright
 * @subpackage ullTime
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllTimeActions extends BaseUllGeneratorActions
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

    $this->addModuleStylesheet();
  }  
  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex(sfRequest $request) 
  {
    $this->checkPermission('ull_time_index');
    
    // when loading the index action let's clean potentially saved list view uri 
    $this->getUriMemory()->delete('list');
    
    $this->handleIndexActionActAsUser($request);
    
    $this->breadcrumbForIndex();
    
    $this->loadPeriodsForIndexAction();
  }
  
  
  /**
   * Act as user functionality
   * 
   * @param sfRequest $request
   */
  protected function handleIndexActionActAsUser(sfRequest $request)
  {
    $this->act_as_user_form = new ullTimeActAsUserForm();
    
    if ($request->isMethod('post'))
    {
      $this->act_as_user_form->bind($request->getParameter('fields'));
      
      if ($this->act_as_user_form->isValid())
      {
        $username = UllUserTable::findUsernameById($this->act_as_user_form->getValue('ull_user_id'));
        // TODO: why is the new url displayed with "?" and "=" instead of the
        // usual symfony style with "/" ?
        $uri = 'ullTime/index' . (($username) ? '?username=' . $username : '');
        
        $this->redirect($uri);
      }
    }
    else
    {
      if ($username = $request->getParameter('username'))
      {
        $this->act_as_user_form->setDefault('ull_user_id', UllUserTable::findIdByUsername($username));
      }
    }     
  }
  
  
  /**
   * Load time periods for index action
   * Enter description here ...
   */
  protected function loadPeriodsForIndexAction()
  {
    $this->periods = UllTimePeriodTable::findCurrentAndPast();
    
    if (UllUserTable::hasPermission('ull_time_enter_future_periods'))
    {
      $futurePeriods = UllTimePeriodTable::findOneYearInFuture();
      if (count($futurePeriods))
      {
        $this->future_periods = $futurePeriods;
      }
    }    
  }
  
  
  /**
   * Execute list action
   * 
   * @param $request
   * @return unknown_type
   */
  public function executeList(sfRequest $request)
  {
    $this->checkPermission('ull_time_list');
    
    $this->getPeriodFromRequest();
    $this->getUserFromRequest();
    
    $this->getUriMemory()->setUri();
    
    $this->prepareListData();
    
    $this->breadcrumbForList();
  }
  
  
  /**
   * Execute reporting for project efforts
   * @param $request
   * @return unknown_type
   */
  public function executeReportProject(sfRequest $request)
  {
    $this->checkPermission('ull_time_report');
    
    $this->breadcrumbForReportProject();
    
    if ($request->isMethod('post'))
    {
      $this->ull_reqpass_redirect();
    }
    
    //save uri so that the user gets returned to this list
    //instead of the defautl overview if he chooses to edit
    //a specific project report
    $this->getUriMemory()->setUri('list');
    
    //"false" must be a string as request params come as strings
    $request->setParameter('paging', 'false');
    
    $this->report = $request->getParameter('report');
    
    $this->handleReportProjectDefaultOrder($request);
    
    $filterParams = $request->getParameter('filter');
    $this->show_edit_action = ($this->report == 'details') ? true : false;
    
    $this->generator = new ullTimeReportGenerator($this->report, $filterParams);
    $this->generator->setCalculateSums(true);
    
    $rows = $this->getFilterFromRequest();
    
    $this->generator->buildForm($rows);

    $this->setVar('generator', $this->generator, true);
    
    $this->handleCsvExport();
  }
  
  
  /**
   * Execute create action
   * 
   */  
  public function executeCreate(sfRequest $request)
  {
    $this->forward('ullTime', 'edit');
  }
  
  
  /**
   * Execute edit action
   * 
   */
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_time_edit');
    
    //ull_url_for() uses reqpassing, we have to remove the form fields
    //here to fix #948. Why is this workaround necessary? Do we
    //even need reqpassing here?
    $this->form_uri = ull_url_for(array('fields' => null));
    
    $this->getDocFromRequestOrCreate();
    
    $this->generator = new ullTableToolGenerator('UllTimeReporting', $this->getAccessForEdit());
    $this->generator->buildForm($this->doc);
    $this->addGlobalValidators();
    
    $this->break_1_duration = $this->doc->getBreakDuration(1);
    $this->break_2_duration = $this->doc->getBreakDuration(2);
    $this->break_3_duration = $this->doc->getBreakDuration(3);
    
    $this->calculateUpToNowTime($request);

    $this->breadcrumbForEdit();
    
    if ($request->isMethod('post'))
    {
//      var_dump($_REQUEST);
//      var_dump($this->getRequest()->getParameterHolder()->getAll());
//      die;
      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        $this->redirect($this->getUriMemory()->getAndDelete('list'));
      }
      else
      {
//        var_dump($this->generator->getForm()->getErrorSchema());
      }
    }
//    echo $this->generator->getForm()->debug();
  }
  
  
  /** 
   * Get time report according to request params
   * 
   * Used by executeEdit()
   */
  protected function getDocFromRequestOrCreate()
  {
    $this->getDateFromRequest();
    $this->getUserFromRequest();
    
    $this->doc = UllTimeReportingTable::findByDateAndUserId($this->date, $this->user->id);
    
    if ($this->getRequestParameter('action') == 'create')
    {
      if ($this->doc)
      {
        $this->redirect('ullTime/edit?date=' . $this->date . '&username=' . $this->user->username);
      }
      else
      {
        $this->doc = new UllTimeReporting;
        $this->doc->ull_user_id = $this->user->id;
        $this->doc->date = $this->date;      
      }
    }
  }  
  
  
  /**
   * Calculate the up to now time for today.
   * 
   * Example: If I checked in this morning at 8am, and now it is 9:30am
   * then I already worked (up to now) 1:30h
   * 
   * @param sfRequest $request
   */
  protected function calculateUpToNowTime(sfRequest $request)
  {
    $this->up_to_now = strtotime(date("H:i")) - strtotime($this->doc->begin_work_at) - $this->doc->total_break_seconds;
    
    $sumTime = UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId($request->getParameter('date'), $this->user_id);
    
    if (($request->getParameter('date') != date('Y-m-d')) || !$this->doc->begin_work_at)
    {
      $this->up_to_now = 0;
    }
    
    if ($sumTime > 0)
    {
      $this->up_to_now = $sumTime;
    }    
  }
  
    
  /**
   * Execute create action
   * 
   * @param sfRequest $request
   */
  public function executeCreateProject(sfRequest $request) 
  {
    //previously we just forwarded to editProject,
    //but now that we have create-only fields (recurring
    //until) we need to preserve that information
    $return = $this->handleProjectEffort($request);
    
    $this->setTemplate('editProject');
    
    return $return;
  }
  
  
  /**
   * Execute edit project effort action
   * 
   * @param sfRequest $request
   */
  public function executeEditProject(sfRequest $request) 
  {
    return $this->handleProjectEffort($request);
  } 

  
  /**
   * Handles create and edit action for project efforts
   * 
   * Supports differen request modes:
   * 
   *   1) Give a date and optional a user
   *      - Display already entered efforts for the given day (= $docs / $list_generator)
   *      - Allow to create or edit project efforts (= $doc / $edit_generator)
   *      
   *   2) Give only a project effort id (UllProjectReporting::id)
   *      - Edit a project effort. Usually coming from reporting (= $doc / $edit_generator)
   *      
   *   3) A mixture of 1) and 2) occurs when editing a project effort from the dayly list
   * 
   * @param sfRequest $request
   */
  protected function handleProjectEffort(sfRequest $request)
  {
    $this->checkPermission('ull_time_edit_project');
    
    //See executeEdit above why we do this (#948)
    $this->form_uri = ull_url_for(array('fields' => null));
    
    $this->getProjectReportingFromRequestOrCreate();
    
    $this->list_generator = new ullTableToolGenerator('UllProjectReporting', 'r', 'list');
    $this->list_generator->setCalculateSums(true);
    $this->list_generator->buildForm($this->docs);
    
    $this->sum_time = UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId($request->getParameter('date'), $this->user_id);
    $this->diff_time = $this->sum_time - UllProjectReportingTable::findSumByDateAndUserId($request->getParameter('date'), $this->user_id);
    
    if ($this->sum_time && !$this->doc->getDurationSeconds() && $this->diff_time > 0)
    {
      $this->doc->setDurationSeconds($this->diff_time);
    }

    $this->edit_generator = new ullTimeProjectEffortGenerator($this->getAccessForEdit(), $this->date);
    $this->disableCommentForLinkedProjectEfforts();
    $this->edit_generator->buildForm($this->doc);
     
    $this->breadcrumbForEditProject();
    
    //if there is no saved list url available, use the default one but
    //append the name of the period which the date being edited belongs to
    $cancelLinkParams = ($this->getUriMemory()->has('list')) ? '' :
      '?period=' . UllTimePeriodTable::findSlugByDate($this->date);
    $this->cancel_link = $this->getUriMemory()->get('list') . $cancelLinkParams;
    
    $this->setVar('user_widget', new ullWidgetForeignKey(
      array('show_ull_entity_popup' => true, 'model' => 'UllEntity')), true);
    
    if ($request->isMethod('post'))
    {
      $this->edit_generator->getForm()->bind($request->getParameter('fields'));  
      if ($this->edit_generator->getForm()->isValid())
      {
        //save original record ...
        $this->edit_generator->getForm()->save();
        //... but also check if the recurring option was set
        $recurringUntil = $this->edit_generator->getForm()->getValue('recurring_until');
        
        if (!empty($recurringUntil))
        {
          $period = new UllTimePeriod();
          $period['from_date'] = $this->date;
          $period['to_date'] = $recurringUntil;
          $dayList = $period->getDateList();
          //remove first element because it was already saved above
          array_shift($dayList);
          foreach ($dayList as $day)
          {
            //only add new efforts for weekdays
            if (!$day['weekend'])
            {
              $clonedDoc = $this->doc->copy();
              $clonedDoc['date'] = $day['date'];
              $clonedDoc->save();
            }
          }
        }
        
        if ($request->getParameter('action_slug') == 'save_new') 
        {
          $this->redirect('ullTime/createProject?date=' . $request->getParameter('date') . '&username=' . $request->getParameter('username'));
        } 
        // use the default referer
        else
        {
          $this->redirect($this->getUriMemory()->getAndDelete('list'));
        }
      }
    }

    return $this->enableAjaxSingleWidgetRendering($this->edit_generator);
  }


  /**
   * Delete a project effort
   * 
   * @return none
   */
  public function executeDeleteProject(sfRequest $request)
  {
    $this->checkPermission('ull_time_delete_project'); 
    
    $this->getProjectReportingFromRequestOrCreate();
    
    $this->forward404Unless($this->getLockingStatus() == 'w');
    
    $this->doc->delete();
    
    $this->redirect('ullTime/createProject?date=' . $request->getParameter('date') . '&username=' . $request->getParameter('username'));
  }
  
  
  /**
   * Disable the "comment" field for linked project efforts
   * 
   * @return none
   */
  protected function disableCommentForLinkedProjectEfforts()
  {
    if ($this->doc->linked_model)
    {
      $this->edit_generator->getColumnsConfig()->offsetGet('comment')->disable();
    }    
  }
  
  
  /**
   * Gets projectReports according to request params
   * 
   */
  protected function getProjectReportingFromRequestOrCreate()
  {
    /* Load doc for edit by id */
    if ($id = $this->getRequestParameter('id'))
    {
      $this->doc = Doctrine::getTable('UllProjectReporting')->findOneById($id);
      
      $this->forward404Unless($this->doc, 'Invalid project effort id given: ' . $id);
      
      // Force request username when giving a project effort id
      $this->getRequest()->setParameter('username', $this->doc->UllUser->username);
    }    
    
    $this->getDateFromRequest();
    
    $this->getUserFromRequest();
    
    $this->docs = UllProjectReportingTable::findByDateAndUserId($this->date, $this->user->id);
    
    // Create a new doc for create
    if (!$this->doc)
    {
      $this->doc = new UllProjectReporting;
      $this->doc->ull_user_id = $this->user->id;
      $this->doc->date = $this->date;      
    }
  }
  
  
  /**
   * Get date from Request
   * 
   */
  protected function getDateFromRequest()
  {
    $this->date = $this->getRequestParameter('date');
    
    if (!$this->date)
    {
      $this->date = date('Y-m-d');
      $this->getRequest()->setParameter('date', $this->date);
    }
  }  
    
  /**
   * Get user from request
   * 
   * @return none
   */
  protected function getUserFromRequest()
  {
    $username = $this->getRequestParameter('username');
    if (!$username)
    {
      $username = UllUserTable::findLoggedInUsername();
      $this->getRequest()->setParameter('username', $username);
    }
    
    $this->user = Doctrine::getTable('UllUser')->findOneByUsername($username);
    $this->user_id = $this->user->id;
    
    // Access check
    if (!UllUserTable::hasGroup('MasterAdmins'))
    {
      $this->redirectToNoAccessUnless($this->user->id == $this->getUser()->getAttribute('user_id'));
    }
  }
  
  
  /**
   * Get the period from request
   * 
   * @return unknown_type
   */
  protected function getPeriodFromRequest()
  {
    $slug = $this->getRequestParameter('period');
    if (!$slug)
    {
      $slug = UllTimePeriodTable::findSlugByDate(date('Y-m-d'));
    }
    
    $this->period = null;
    
    if ($slug)
    {
      $this->period = Doctrine::getTable('UllTimePeriod')->findOneBySlug($slug);
    }  
    
    if (!$this->period)
    {    
      $this->showError('No period for date ' . date('Y-m-d') . ' found. Please create a period on the administration page.');
    }
  }
  
  
  /**
   * Set global time compare validators for ullTimeReporting
   * 
   * @return none
   */
  protected function addGlobalValidators()
  {
    $this->generator->getForm()->addGlobalCompareTimeValidator('begin_work_at', 'end_work_at');
    $this->generator->getForm()->addGlobalCompareTimeValidator('begin_break1_at', 'end_break1_at');
    $this->generator->getForm()->addGlobalCompareTimeValidator('begin_break2_at', 'end_break2_at');
    $this->generator->getForm()->addGlobalCompareTimeValidator('begin_break3_at', 'end_break3_at');
  }
  
  
  
  /**
   * Create breadcrumbs for index action
   * 
   */
  protected function breadcrumbForIndex() 
  {
    $breadcrumbTree = new ullTimeBreadcrumbTree();
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
  
  /**
   * Create breadcrumbs for list action
   * 
   */  
  protected function breadcrumbForList() 
  {
    $breadcrumbTree = new ullTimeBreadcrumbTree;
    $breadcrumbTree->addDefaultListEntry();
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }  

  /**
   * Create breadcrumbs for reportProject action
   * 
   */
  protected function breadcrumbForReportProject() 
  {
    $breadcrumbTree = new ullTimeBreadcrumbTree;
    $breadcrumbTree->add(__('Reporting', null, 'ullTimeMessages'));
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }   
  
  
  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEdit() 
  {
    $breadcrumbTree = new ullTimeBreadcrumbTree;
    $breadcrumbTree->setEditFlag(true);
    $breadcrumbTree->addDefaultListEntry();

    if ($this->doc->exists()) 
    {
      $breadcrumbTree->add(__('Edit time report', null, 'ullTimeMessages'));
    } 
    else 
    {
      $breadcrumbTree->add(__('Create time report', null, 'ullTimeMessages'));
    }

    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  } 

  
  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEditProject() 
  {
    $breadcrumbTree = new ullTimeBreadcrumbTree;
    $breadcrumbTree->setEditFlag(true);
    $breadcrumbTree->addDefaultListEntry();

    if ($this->doc->exists()) 
    {
      $breadcrumbTree->add(__('Edit project effort', null, 'ullTimeMessages'));
    } 
    else 
    {
      $breadcrumbTree->add(__('Create project effort', null, 'ullTimeMessages'));
    } 
    
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }

  /**
   * Get generator access. Handles future dates depending on the matching
   * access right and further calls the locking handling (see getLockingStatus())
   * 
   * @return string
   */
  protected function getAccessForEdit()
  {
    if ($this->date <= date('Y-m-d') || UllUserTable::hasPermission('ull_time_enter_future_periods'))
    {
      return $this->getLockingStatus();
    }
    else
    {
      $this->getUser()->setFlash('message', 
        __('Read only access. Date is in the future. Please contact your superior if you need to edit this data.',
        null, 'ullTimeMessages'), false);
      return 'r';
    }
  }
  
  /**
   * Set generator global access depending on the locking status.
   * Normal users may only change data $lockingTimespanDays ago
   * Older entries are displayed read-only
   * 
   * @return string
   */
  protected function getLockingStatus()
  {
    if (UllUserTable::hasPermission('ull_time_ignore_locking'))
    {
      return 'w';
    }
    
    $this->lockingTimespanDays = sfConfig::get('app_ull_time_locking_timespan_days', 30);
    
    if ($this->doc->date > (date('Y-m-d', time() - $this->lockingTimespanDays * 24 * 60 * 60)))
    {
      return 'w';
    }
    
    $this->getUser()->setFlash(
      'message', 
      __('Read only access. Entries are locked after %days% days. Please contact your superior if you need to edit your data.', 
        array('%days%' => $this->lockingTimespanDays), 
        'ullTimeMessages'
      ),
      false
    );
    
    return 'r';
  } 
  
  
  /**
   * Configure the ullFilter class name
   * 
   * @return string
   */
  public function getUllFilterClassName()
  {
    return 'ullTimeProjectFilterForm';
  }  
  
  
  /**
   * Apply custom modifications to the query
   *
   * This function builds a query selecting UllUsers for the phone book;
   * see inline comments for further details.
   */
  protected function modifyQueryForFilter()
  {
    
    //filter per user
    if ($ullUserId = $this->filter_form->getValue('ull_user_id'))
    {
      $this->q->addWhere('ull_user_id = ?', $ullUserId);
      $this->user = Doctrine::getTable('UllEntity')->findOneById($ullUserId);
      
      $this->ull_filter->add('filter[ull_user_id]', __('User', null, 'common') . ': ' . $this->user);
      
      // both do not work because the form is already bound
//      $this->filter_form->setDefault('ull_user_id', null);
//      $this->filter_form->setValue('ull_user_id', null);
    }
    else
    {
      $this->user = null;
    }

    //filter per project
    if ($projectId = $this->filter_form->getValue('ull_project_id'))
    {
      $this->q->addWhere('ull_project_id = ?', $projectId);
      $this->project = Doctrine::getTable('UllProject')->findOneById($projectId);
      
      $this->ull_filter->add('filter[ull_project_id]', __('Project', null, 'ullTimeMessages') . ': ' . $this->project);
      
      // both do not work
//      $this->filter_form->setDefault('ull_user_id', null);
//      $this->filter_form->setValue('ull_user_id', null);
    }
    else
    {
      $this->project = null;
    }     

    // filter per date
    
    // filter from date
    $dateWidget = new ullWidgetDateRead();
    if ($fromDate = $this->filter_form->getValue('from_date'))
    {
      $this->q->addWhere('date >= ?', $fromDate);
      $this->ull_filter->add('filter[from_date]', __('Begindate', null, 'common') . ': ' . $dateWidget->render(null, $fromDate));
    }
    
    // filter to date
    if ($toDate = $this->filter_form->getValue('to_date'))
    {
      $this->q->addWhere('date <= ?', $toDate);
      $this->ull_filter->add('filter[to_date]', __('Enddate', null, 'common') . ': ' . $dateWidget->render(null, $toDate));
    }
    
    if ($this->report != 'details')
    {
      // Add artificial sum field
      $this->q->getDoctrineQuery()
        ->addSelect('SUM(x.duration_seconds) as duration_seconds_sum')
      ;
    }
    
    switch ($this->report)
    {
      case 'by_project':      
        $this->q->getDoctrineQuery()
          ->addGroupBy('x.ull_project_id')
        ;
        break;
        
      case 'by_user':
        $this->q->getDoctrineQuery()
          ->addGroupBy('x.ull_user_id')
        ;
        break;
    }    

    
    //access check
    $this->q = UllProjectReportingTable::queryAccess($this->q);
    
  }
  
  
  /**
   * Prepare data for the list (period overview)
   * 
   * @return none
   */
  protected function prepareListData()
  {
    //$rawDates is sorted by date, ascending, but we need descending
    $rawDates = $this->period->getDateList();
    krsort($rawDates);
    
    $periodTable = array();
    $calendarWeek = null;
    $week = null;
    $this->totals = array('time' => 0, 'project' => 0, 'delta' => 0);
    $this->all_days_are_future = true;
    
    foreach($rawDates as $date => $day)
    {
      //check if we have a calendar week switch
      if ($calendarWeek !== $day['calendarWeek'])
      {
        //if this is not the first switch of a month,
        //save the created week in the period table
        if ($week != null)
        {
          $periodTable[$calendarWeek] = $week;
        }
        
        $calendarWeek = $day['calendarWeek'];

        //create an empty week
        $week = array();
        $week['sum_project'] = 0;
        $week['sum_time'] = 0;
        $week['sum_delta'] = 0;
        $week['future'] = true;
      }
      
      //update the week with its days and add sums
      
      $week['dates'][$date] = $day;

      $sumTime = UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId($date, $this->user_id);
      $week['dates'][$date]['sum_time'] = $sumTime; 
      $week['sum_time'] += $sumTime;
      $this->totals['time'] += $sumTime;
      
      $sumProject = UllProjectReportingTable::findSumByDateAndUserId($date, $this->user_id);  
      $week['dates'][$date]['sum_project'] = $sumProject;
      $week['sum_project'] += $sumProject;
      $this->totals['project'] += $sumProject;
      
      $sumDelta = $sumTime - $sumProject;
      $week['dates'][$date]['sum_delta'] = $sumDelta;
      $week['sum_delta'] += $sumDelta;
      $this->totals['delta'] += $sumDelta;
      
      // mark future dates
      if ($date > date('Y-m-d'))
      {
        $week['dates'][$date]['future'] = true;
      }
      else
      {
        $week['dates'][$date]['future'] = false;
        $week['future'] = false;
        $this->all_days_are_future = false;
      }
    }
    
    //Let us not forget the last week
    $periodTable[$calendarWeek] = $week;
    
    $this->period_table = $periodTable;    
  }
  
  
  /**
   * Handle default order for reportProject action
   * 
   */
  protected function handleReportProjectDefaultOrder(sfRequest $request)
  {
    if (!$request->getParameter('order'))
    {
      switch ($this->report)
      {
        case 'details':
          $request->setParameter('order', 'date');
          break;
          
        default:
          $request->setParameter('order', 'UllProject->name');
      }
    }    
  }
}
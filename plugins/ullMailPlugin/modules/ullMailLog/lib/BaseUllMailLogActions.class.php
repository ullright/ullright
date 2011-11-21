<?php

/**
 * ullMailLog actions.
 *
 * @package    ullright
 * @subpackage ullMail
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllMailLogActions extends BaseUllGeneratorActions
{
  
  /**
   * Everything here is executed before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();

    //add module stylsheet for all actions but show
    if ($this->getActionName() !== 'show')
    {
      $path =  '/ullMailTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
      $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
    }   
  }  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex(sfRequest $request) 
  {
    $this->redirect('ullMailLog/list');
  }
  
  /**
   * Execute list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_mail_log_list');
    
    $this->named_queries = new ullNamedQueriesUllMailLog();
    
    parent::executeList($request);
    
    $this->chart = null;
    
    if ($request->getParameter('query') == 'read')
    {
      $this->renderReadChart();
    }
    
    $this->breadcrumb_tree->add($this->generator->getTableConfig()->getName());
  }
  
  /**
   * Apply custom modifications to the query
   *  
   * @return none
   */
  protected function modifyQueryForFilter()
  {
    $ullNewsletterEditionId = $this->getRequest()->getParameter('ull_newsletter_edition_id');
    
    if (!$ullNewsletterEditionId)
    {
      $this->redirect404();
    }    
    
    $ullNewsletterEdition = Doctrine::getTable('UllNewsletterEdition')
      ->findOneById($ullNewsletterEditionId);
      
    if (!$ullNewsletterEdition)
    {
      $this->redirect404();
    }
    
    $this->q->addWhere('ull_newsletter_edition_id = ?', $ullNewsletterEditionId);
    
    $this->setVar('ull_newsletter_edition', $ullNewsletterEdition, true);
  }   
  
  /**
   * Delete is not allowed here
   */
  public function executeDelete(sfRequest $request)
  {
    $this->redirect404();
  }
  
  
  /**
   * Execute edit action
   * 
   * @param sfWebRequest $request
   */  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_mail_log_edit');
    
    parent::executeEdit($request);
  }
  
  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullMailLogGenerator('r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getEditGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullMailLogGenerator('r');
  }

  /**
   * Create chart for readers
   */
  protected function renderReadChart()
  {
    /**
     * Minimal example
     *
     * Taken from the pChart example library
     *
     * @link http://pchart.sourceforge.net/screenshots.php
     * @link http://code.google.com/p/mtchart/
     */
    
    $this->dir = sfConfig::get('sf_upload_dir'). '/charts/ullMailLog';
    
    if (!file_exists($this->dir))
    {
      mkdir($this->dir, '0777', true);
    }
    
    $this->chart = ullCoreTools::absoluteToWebPath($this->dir . '/read.png');
    
    $data = array(
      'hour' => array(),
      'per_hour' => array(),
    );
    
    // Calculate end date (one week)
    $sentAt = $this->ull_newsletter_edition->submitted_at;
    $sentAtStamp = strtotime($sentAt);
    $endDateStamp = strtotime('+5 days', $sentAtStamp);
    $endDate = date('Y-m-d H:i:s', $endDateStamp);
    
    $q = new ullQuery('UllMailLoggedMessage');
    $q
      ->addSelect('COUNT(*) as sum, SUBSTR(first_read_at, 1, 13) as hour')
      ->addWhere('ull_newsletter_edition_id = ?', $this->ull_newsletter_edition->id)
      ->addWhere('first_read_at IS NOT NULL')
      ->addWhere('first_read_at < ?', $endDate)
      ->addGroupBy('hour') 
      ->orderBy('first_read_at')
    ;    
    $result = $q->execute(array(), Doctrine::HYDRATE_SCALAR);
    
    foreach ($result as $value)
    {
      $date = $value['x_hour'] . ':00:00';
      $num = $value['x_sum'];
      
      $data['hour'][] = format_datetime($date, 'EEE ') . 
        format_datetime($value['x_hour']. ':00:00', 
          ull_date_pattern(false) . ' HH') . 'h';        
      
      $data['per_hour'][] = $num;
    }
    
    // Dataset definition
    $Test = new mtChart(720,300);
    
    $Test->enableCaching(null, $this->dir . '/');
    
    $Test->addPoint($data['per_hour'], 'per_hour');
    $Test->addPoint($data['hour'], 'legend');
    
    $Test->AddSerie('per_hour');

    $Test->SetAbsciseLabelSerie('legend'); 
    $Test->setSerieName(__('Readers per hour', null, 'ullMailMessages'), 'per_hour');
    $Test->setYAxisName(__('Readers per hour', null, 'ullMailMessages'));
    
    if ($Test->isInCache())
    {
      return;
    }
    
    // Initialise the graph
    $Test->setFontProperties('DejaVuSansCondensed',10);
    $Test->setGraphArea(90,8,712,200);
    $Test->setInterval(4);
    // 6th param = angle of x-axis labels
    $Test->drawScale(SCALE_NORMAL,150,150,150,TRUE,45,1);
    $Test->drawGrid(4,true,230,230,230,70);
    
    // Draw the line graph
    $Test->drawLineGraph();
    $Test->drawPlotGraph(3,2,255,255,255, true);
    
    // Finish the graph
    $Test->drawLegend(570,10,255,255,255);
    
    $Test->render($this->dir . '/read.png');
  }    
  
  /**
   * Handles breadcrumb for list action
   *
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new ullMailLogBreadcrumbTree();
    
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
  /**
   * Handles breadcrumb for list action
   *
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumb_tree = new ullMailLogBreadcrumbTree();
    $breadcrumb_tree->add(__('Report', null, 'common'), $this->uriMemory->get('list', 'ullMailLog'));
    $breadcrumb_tree->add(__('Show', null, 'common'));
    
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }    
}
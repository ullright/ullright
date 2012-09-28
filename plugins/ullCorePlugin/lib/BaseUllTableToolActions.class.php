<?php

/**
 * ullTableTool actions.
 *
 * @package    ullright
 * @subpackage ullTableTool
 * @author     Klemens Ullmann <klemens.ullmann@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseUllTableToolActions extends BaseUllGeneratorActions
{

  /**
   * Everything here is executed before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();

    $tableParam = '?table=' . $this->getRequestParameter('table');
    
    $this->list_base_uri    = $this->getModuleName() . '/list' . $tableParam;
    $this->create_base_uri  = $this->getModuleName() . '/create' . $tableParam;
    $this->edit_base_uri    = $this->getModuleName() . '/edit' . $tableParam;
    $this->show_base_uri    = $this->getModuleName() . '/show' . $tableParam;
    $this->delete_base_uri  = $this->getModuleName() . '/delete' . $tableParam;
    $this->delete_future_version_base_uri = $this->getModuleName() . '/deleteFutureVersion' . $tableParam;
    
    $this->getUriMemory()->setDefault($this->list_base_uri);
    
    $this->getTablefromRequest();
    
    $this->addPluginStylesheet();
    $this->getBreadcrumbBase();
  }
  

  /**
   * Added plugin stylesheet accorting to table config
   */
  protected function addPluginStylesheet()
  {
    if (($plugin = $this->table_config->getPlugin()) &&
      ($this->getActionName() !== 'show')) 
    {
      $plugin = str_replace('Plugin', '', $plugin);
      $path =  '/' . $plugin . 'Theme' .  sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
      
      $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
    }    
  }
  
  
  /**
   * Get the breadcrumb base class
   */
  protected function getBreadcrumbBase()
  {
    if ($breadcrumbClass = $this->table_config->getBreadcrumbClass())
    {
      $this->breadcrumb_base = new $breadcrumbClass;
    }
    else 
    {
      $this->breadcrumb_base = new ullTableToolBreadcrumbTree();      
    }
  }
  
  
  /**
   * Executes index action
   *
   */
  public function executeIndex(sfRequest $request)
  {    
    $this->redirect('ullAdmin/index');
  }

  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {    
    $this->checkPermission($this->getPermissionName());
    
    $return = parent::executeList($request);
    
    $this->setCommonTitle();
    
    return $return;
  }  

  
  /**
   * Setup ullGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullTableToolGenerator($this->table_name, 'r', 'list', $this->columns);
  }  

  
  /**
   * Executes show action
   *
   */
  public function executeShow(sfRequest $request) 
  {
    $this->forward($this->getModuleName(), 'edit');
  }

  /**
   * Handles a state-change request for a flag for
   * the currently logged-in user. This requires a
   * model to adopt the ullFlaggable behavior.
   * 
   * Needs four parameters:
   * table - the model
   * id - the record id
   * flag - the flag name
   * value - 'true' => true, otherwise false; null is valid
   * 
   * @param sfRequest $request
   * @return string view rendered by sfView:NONE
   */
  public function executeSetUserFlag(sfRequest $request)
  {
    //parse model
    //(done by preExecute() into $this->table_name)
    
    //parse record id
    $recordId = $request->getParameter('id');
    $record = ($recordId) ? Doctrine::getTable($this->table_name)->findOneById($recordId) : false;
    $this->forward404If(!$record, 'Record id not specified or invalid');
    
    //TODO: include check here if model adopts UllFlaggable behavior
    //should not be a problem if it does not (hasFlag call simply fails),
    //but should be done anyway
    
    //parse flag name
    $flagName = $request->getParameter('flag');
    $this->forward404If(!$record->hasFlag($flagName), 'Flag not specified or invalid');
    
    //parse flag value (null is ok, will remove the flag)
    $flagValue = $request->getParameter('value');
    if ($flagValue !== null)
    {
      $flagValue = ($flagValue == 'true') ? true : false;
    }
        
    //set flag for currently logged in user
    $record->setFlag($flagName, $flagValue);
    
    if ($request->isXmlHttpRequest())
    {
      return sfView::NONE;
    }
    else
    {
      $referer = $request->getReferer();
      $this->redirect($referer ? $referer : '@homepage');
    }
  }
  
  
  /**
   * Executes create action
   *
   */
  public function executeCreate(sfRequest $request) 
  {
    $this->forward($this->getModuleName(), 'edit');
  }  

  
  
//                           __
//                   _.--""  |
//    .----.     _.-'   |/\| |.--.
//    |jrei|__.-'   _________|  |_)  _______________  
//    |  .-""-.""""" ___,    `----'"))   __   .-""-.""""--._  
//    '-' ,--. `    |tic|   .---.       |:.| ' ,--. `      _`.
//     ( (    ) ) __|tac|__ \\|// _..--  \/ ( (    ) )--._".-.
//      . `--' ;\__________________..--------. `--' ;--------'
//       `-..-'                               `-..-'
  
  /**
   * Executes edit action
   *
   * @param sfWebRequest $request
   */
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission($this->getPermissionName());
    
    $return = parent::executeEdit($request);
    
    $this->setCommonTitle();
    $this->appendToTitle($this->generator->getRow());
    
    return $return;
  }  
  
  
  /**
   * Update a single field, usually called via ajax
   * 
   * Call syntax: ullTableTool/updateSingleColumn?table=UllUser&id=2&column=street&value=streetname
   * 
   * This is e.g. used by ullWidgetCheckbox for direct ajax updates in the list view 
   *
   * @param sfWebRequest $request
   */
  public function executeUpdateSingleColumn(sfRequest $request)
  {
    $table = $request->getParameter('table');
    $column = $request->getParameter('column');
    
    // check for dynamic permission
    // example: ull_tabletool_write_ull_user_street
    if (!UllUsertable::hasPermission(
      'ull_tabletool_write_' .
      sfInflector::underscore($table) . '_' .
      $column
    ))
    {
      throw new InvalidArgumentException('Access denied');
    }
    
    $this->generator = new ullTableToolGenerator($table, 'w', null, array('id', $column));
    $row = $this->getRowFromRequest();
    $this->id = $row->id;
    
    $this->generator->buildForm($row);
    
    if ($this->generator->getForm()->bindAndSave(
      array_merge(
//        $this->prepareDefaultsForUpdateSingleColumn($row),
        array($column => $request->getParameter('value')), 
        array('id' => $row->id) ) 
    ))
    {
      // Notify event      
      $this->dispatcher->notify(new sfEvent($this, 'ull_table_tool.update_single_column', array(
        'column'        => $column,
        'object'        => $row,
      )));
      
      // Everything's fine, no validation error occured      
      return $this->renderText(json_encode(array('id' => $row->id, $column => $row[$column]))); 
    }
    else
    {
//       ullCoreTools::debugFormError($this->generator->getForm());
      throw new Exception('Validation failed');
    }
  }  
  
  /**
   * Render a single widget for ajax
   * 
   * Call syntax: ullTableTool/renderSingleWidget?table=UllUser&field=street&id=2
   * 
   * This is e.g. used by ullWidgetFormDoctrineChoice for inline editing via overlay 
   *
   * @param sfWebRequest $request
   */  
  public function executeRenderSingleWidget(sfRequest $request)
  {
     $table = $request->getParameter('table');
     // in ullright the fields are grouped into field[xxx] 
     $field = $request->getParameter('field');
     $field = str_replace('fields[', '', $field);
     $field = str_replace(']', '', $field);
    
    // TODO: find suitable permission checking mechanismn
    // Basically, the permission check must be done as or by the appropriate
    // edit action. A generic check as used below is not enough, as it allows
    // to retreive all data of one model/column and also additional information
    // like select box entries
     
    // check for dynamic permission
    // example: ull_tabletool_read_ull_user_street
    if (!UllUsertable::hasPermission(
      'ull_tabletool_read_' .
      sfInflector::underscore($table) . '_' .
      $field
    ))
    {
      throw new InvalidArgumentException('Access denied');
    }
    
    $this->generator = new ullTableToolGenerator($table, 'w', null, array('id', $field));
    $row = $this->getRowFromRequestOrCreate();
    $this->id = $row->id;
    
    $this->generator->buildForm($row);
    
    $return = $this->renderText($this->generator->getForm()->offsetGet($field)->render());
    
    return  $return; 
  }
  
  /**
   * Called from the ullManytoManyWrite widget (in AJAX mode).
   * Takes a table + column, creates the widget as would the generator
   * but with an additional filter applied. Results are returned
   * in JSON.
   */
  public function executeManyToManyFilter(sfRequest $request)
  {
    $this->checkPermission($this->getPermissionName());
    
    $this->forward404If(!$request->hasParameter('table'));
    $this->forward404If(!$request->hasParameter('column'));
    $this->forward404If(!$request->hasParameter('filter'));
    
    $table = $request->getParameter('table');
    $columnName = $request->getParameter('column');
    $filter = trim($request->getParameter('filter'));
    
    $columnConfig = ullColumnConfigCollection::buildFor($table, 'w', 'edit');
    $column = $columnConfig[$columnName];
    
    $widget = new ullWidgetManyToManyWrite(
      array_merge($column->getWidgetOptions(), array('filter_results' => $filter)),
      $column->getWidgetAttributes());
    
    $this->getResponse()->setContentType('application/json');
    
    $choices = $widget->getChoices();
    
    if (count($choices) >= 100)
    {
      $resultText = __('Too many results found, limiting to first 100', null, 'common');
    }
    else
    {
      $resultText = format_number_choice('[0]No results found|[1]1 result found|(1,+Inf]%1% results found',
        array('%1%' => count($choices)), count($choices), 'common');
    }
    $wrapper = array('choices' => $choices, 'resultText' => $resultText);
    
    return $this->renderText(json_encode($wrapper));
  }

//  /**
//   * Does what the method name suggests
//   * (Did you have a bad day?)
//   * 
//   * @param unknown_type $row
//   */
//  protected function prepareDefaultsForUpdateSingleColumn($row)
//  {
//    $defaultsForValidation = array_intersect_key(
//      $row->toArray(), 
//      array_flip($this->generator->getForm()->getListOfFields())
//    ); 
//    
//    // TODO: remove hardcoded fix for UllUser
//    unset($defaultsForValidation['password']);
//    
//    return $defaultsForValidation;
//  }

  public function executeContentElement(sfRequest $request)
  {
//     var_dump($request->getParameterHolder()->getAll());die;
    
    $elementTypes  = json_decode($request->getParameter('element_types'), true);
    $elementType   = $request->getParameter('element_type');
    $elementId     = $request->getParameter('element_id');
    $fieldId       = $request->getParameter('field_id');
    $values        = $request->getParameter($elementType . '_fields');
    
    
    $elementData = array(
      'type'    => $elementType,
      'id'      => $elementId,    
    );    
       
    $generator = new ullContentElementGenerator($elementType);
    $generator->buildForm(new UllContentElement());
    
    $form = $generator->getForm();
    $form->bind($values);
    
    $return = array();
    
    if ($form->isValid())
    {
      $elementData['values'] = $form->getValues();
      
      $return['markup'] = $this->getPartial('ullTableTool/ullContentElement', array(
        'element_data'    => $elementData,
        'element_types'   => $elementTypes,          
        'field_id'        => $fieldId,
      ));      
      
      $return['status'] = 'valid';
      
      return $this->renderText(json_encode($return));
      
    }
    
    // Invalid: return form only
//       $elementData['values'] = $values;
    $return['markup'] = $this->getPartial('ullTableTool/contentElementForm', array(
      'element_data'    => $elementData,
      'field_id'        => $fieldId,
      'generator'       => $generator,
    ));
    
    $return['status'] = 'invalid';      
    
    return $this->renderText(json_encode($return));

  }  

  public function executeContentElementAdd(sfRequest $request)
  {
    $element     = $request->getParameter('element');
    $elements    = json_decode($request->getParameter('elements'), true);
    $field_id    = $request->getParameter('field_id');
    $element_id  = uniqid();
    
    $generator = new ullContentElementGenerator($element);
    $generator->buildForm(new UllContentElement());
    
    $return = $this->getPartial('ullTableTool/contentElementAddMarkup', array(
      'element'    => $element,
      'element_id' => $element_id,
      'elements'   => $elements,
      'field_id'   => $field_id,
      'generator'  => $generator,
    ));

    return $this->renderText($return);

  }
  
  /**
   * Setup ullGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullTableToolGenerator($this->table_name, 'w');
  }  

  
  /**
   * Executes delete action
   *
   * @param sfWebRequest $request
   */
  public function executeDelete(sfRequest $request)
  { 
    $this->checkPermission($this->getPermissionName());
    
    parent::executeDelete($request);
  }
  
  
  /**
   * Setup ullGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */  
  protected function getDeleteGenerator()
  {
    return new ullTableToolGenerator($this->table_name);
  }
  
  
  /**
   * Executes delete action
   *
   * @param sfWebRequest $request
   */
  public function executeDeleteFutureVersion(sfRequest $request)
  { 
    $this->checkPermission($this->getPermissionName());
    
    parent::executeDeleteFutureVersion($request);
  }    
  
  /**
   * Gets a table object according to request param
   *
   * @return Doctrine_Record
   */
  protected function getTablefromRequest() 
  {
    $this->forward404Unless(
        $this->hasRequestParameter('table'), 
        'Please specify a database table'
    );
    
    $this->table_name = $this->getRequestParameter('table');

    $this->forward404Unless(
        class_exists($this->table_name),
        'Database table not found: ' . $this->table_name
    );
    
    $this->table_config = ullTableConfiguration::buildFor($this->table_name);

    return true;
  }
  
  
  /**
   * Dynamically create the UllPermission name
   * 
   * @return string
   */
  protected function getPermissionName()
  {
    $permission = 'ull_tabletool_' . sfInflector::underscore($this->getRequest()->getParameter('table'));
    
    return $permission;
  }
  
  
  protected function setCommonTitle()
  {
    $this->getResponse()->setTitle(
      $this->getModuleName() . 
      ' - ' . 
      $this->generator->getTableConfig()->getName() .
      ' - ' .
      __(ucfirst($this->getRequestParameter('action')), null, 'common')
    );
  }
  
  /**
   * Handles breadcrumb for list action
   *
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = $this->breadcrumb_base;
//    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add($this->generator->getTableConfig()->getName());
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullTableTool/list?table=' . $this->table_name);
    
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }
  
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumb_tree = new $this->breadcrumb_base;
    $breadcrumb_tree->setEditFlag(true);
//    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add($this->generator->getTableConfig()->getName());
    $breadcrumb_tree->add(__('Result list', null, 'common'), $this->getUriMemory()->get('list'));    
    if ($this->id) 
    {
      $breadcrumb_tree->add(__('Edit', null, 'common'));
    }
    else
    {
      $breadcrumb_tree->add(__('Create', null, 'common'));
    }
    
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
}
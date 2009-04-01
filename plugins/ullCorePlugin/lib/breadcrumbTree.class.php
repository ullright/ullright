<?php

/**
 * Managing breadcrumb tree
 *
 * @package    weflow
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class breadcrumbTree
{
  protected
    $action
    , $context
    , $edit = false
    , $breadcrumbTree = array()
  ;
  
/**
 * gets symfony context objects
 * @param none
 * @return none
 */ 
  public function __construct() {    
    $this->context      = sfContext::getInstance();
    $this->action       = $this->context->getActionName(); 
  }  
  
/**
 * Set 'edit' flag 
 * 
 * This flag switches the breadcrumb tree to the 'form' mode
 * It then uses ull_js_observer to detect changes in the html form
 * 
 * @param edit boolean  edit flag
 * @return none
 */ 
  public function setEditFlag($edit) {
    $this->edit = $edit;
  }  

/**
 * Add an element to the breadcrumb tree
 * @param name          name of the link to be displayed
 * @param internal_uri  optional, internal symfony url
 * @return none
 */ 
  public function add($name, $internal_uri = '') {
//    $name = __($name);
    $this->breadcrumbTree[] = array(
      'name' => $name,
      'internal_uri' => $internal_uri
    );
  }
  
/**
 * Adds the last (=active) element to the breadcrumb tree
 * @param name          name of the item to be displayed
 * @return none
 */ 
  
  // TODO: remove, is unnecessary
  public function addFinal($name) {
    $this->breadcrumbTree[] = array(
      'name' => $name,
      'final' => true
    );
  }  

/**
 * Return the breadcrumb array
 * @return array
 */ 
  public function getBreadcrumbTree() {
    return $this->breadcrumTree;
  }
  
/**
 * Return html breadcrumb tree
 * @return string
 */
  public function getHtml() {
    foreach ($this->breadcrumbTree as $breadcrumb) {
      if (@$breadcrumb['internal_uri']) {
        if ($this->edit) {
          $options = 'ull_js_observer_confirm=true';
        } else {
          $options = '';
        }
        $breadcrumbTreeReturn[] = 
          ull_link_to(htmlspecialchars($breadcrumb['name']),
                      $breadcrumb['internal_uri'],
                      $options);
      
      } else {
//        if (@$breadcrumb['final']) {
//          $breadcrumbTreeReturn[] = '<b>'.$breadcrumb['name'].'</b>';
//        } else {
          $breadcrumbTreeReturn[] = htmlspecialchars($breadcrumb['name']);
//        }
      }      
    }

    $return = "<ul id='breadcrumbs'>\n";
    $return .= "<li class='first'>";
    $return .= ull_link_to(
                  ull_image_tag('home', array(), 10, 10),
                  '@homepage', 'ull_js_observer_confirm=true'
                ); 
    $return .= "</li>\n";                
    
//    $first = ' class="first"';

    $first = '';
    
    foreach ($breadcrumbTreeReturn as $breadcrumbItem) 
    {
        $return .= "<li$first>$breadcrumbItem</li>\n";
//        $first = ''; 
    }
    $return .= "</ul>\n\n";
    return $return;
    
//    return '<div class="breadcrumb">'.implode(' » ', $breadcrumbTreeReturn).'</div>';
    
  }   
  
}

?>
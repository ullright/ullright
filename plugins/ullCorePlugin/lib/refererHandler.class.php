<?php

/**
 * referer handler class
 *
 * @package    weflow
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 * 
 * requires refererFilter.class or any other mechanismn which sets the
 *   referer to the user's attribute 'referer'
 */

class refererHandler
{
  protected $context;
  protected $user;
  
  protected $action;
//  protected $default_action;
  protected $module;
//  protected $default_module;  
  
  protected $referer_base_name;
  protected $referer_name;
  
  protected $blacklist = array (
    '/update'     // to prevent looping 
    , '/login'    // login screen can never be a referer (session expire)
  ); // non-allowed referer actions
    
    
/**
 * gets symfony context objects
 * @param none
 * @return none
 */ 
  public function __construct() {    
    $this->context            = sfContext::getInstance();
    $this->user               = $this->context->getUser();
    $this->action             = $this->context->getActionName();
    $this->module             = $this->context->getModuleName();
    $this->referer_base_name  = 'referer';     
  }
  
  
/**
 * build the referer name
 */ 
  public function buildRefererName() {
       
    $this->referer_name = $this->referer_base_name.'_'.$this->module.'_'.$this->action;
    
  }  
  
/**
 * saves the current HTTP or a default referer, if there is no referer yet
 * @param action        optional  name of the symfony action, for which a referer should be saved
 * @param module        optional  name of the symfony module, for which a referer should be saved
 * @param blacklist     TODO: refactor
 * @return none
 */ 
    
  public function initialize($action = '', $module = '', $ignore_blacklist = false) {
    

    if ($action) {
      $this->action = $action;
    }
    
    if ($module) {
      $this->module = $module;
    }
    
    if ($ignore_blacklist) {
      $blacklist = array();  
    } else {
      $blacklist = $this->blacklist;
    }
    
    $this->buildRefererName();
    
    // == referer handling
    if (!$this->strstrArray($this->user->getAttribute('referer'), $blacklist)) {
      
      // use HTTP referer, if no module-specific referer specified 
      if (!$this->user->hasAttribute($this->referer_name)) {
        $this->user->setAttribute(
          $this->referer_name, 
          $this->user->getAttribute('referer')
        );
      }
  
      // if there is still no module-specific referer (direct link) use the index action
      if (!$this->user->hasAttribute($this->referer_name)) {
        $this->user->setAttribute(
          $this->referer_name, 
          $this->module.'/index'
        );
      }
    }
  }
  

/**
 * check if there is a referer URI for the given symfony action
 * @param action        optional  name of the symfony action
 * @return boolean
 */   
  public function hasReferer($action = '', $module = '') {
    
    if ($action) {
      $this->action = $action;
    }
    
    if ($module) {
      $this->module = $module;
    }
    
    $this->buildRefererName();
    
    if ($this->user->hasAttribute($this->referer_name)) {
      return true;
    }
    
  }  
  
  
/**
 * get the referer URI for the given symfony action
 * @param action        optional  name of the symfony action
 * @return string       referer URI
 */ 
  public function getReferer($action = '', $module = '') {

    if ($action) {
      $this->action = $action;
    }
    
    if ($module) {
      $this->module = $module;
    }
    
    $this->buildRefererName();
    
    return $this->user->getAttribute($this->referer_name);
  }

  
/**
 * get the referer URI for the given symfony action, but delete it as well from the user session
 * @param action        optional  name of the symfony action
 * @return string       referer URI
 */    
  public function getRefererAndDelete($action = '', $module = '') {

    if ($action) {
      $this->action = $action;
    }
    
    if ($module) {
      $this->module = $module;
    }
    
    $referer = $this->getReferer($this->action, $this->module);
    $this->delete($this->action, $this->module);
    return $referer;
  }
  
  
/**
 * delete the referer from the user session
 * @param action        optional  name of the symfony action
 * @return none
 */    
  public function delete($action = '', $module = '') {
    
    if ($action) {
      $this->action = $action;
    }
    
    if ($module) {
      $this->module = $module;
    }
    
    $this->buildRefererName();
    
    if ($this->user->hasAttribute($this->referer_name)) {
      $this->user->getAttributeHolder()->remove($this->referer_name);  
    }    
  }
  
/**
 * like strstr(), but checks if any of the strings in $needle_array is in
 *   $haystack
 * @param haystack string
 * @param needle_array array              
 * @return string boolean
 */   
  // 
  public function strstrArray($haystack, $needle_array) {
    
    foreach ($needle_array as $needle) {
      if (strstr($haystack, $needle)) {
         return true;
      }
    }
    
  }

  
}

?>
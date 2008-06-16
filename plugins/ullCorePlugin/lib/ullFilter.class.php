<?php

/**
 * Managing result list filter settings
 *
 * @package    ullright
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class ullFilter
{
  protected
    $filters = array()
  ;
  
/**
 * gets symfony context objects
 * @param none
 * @return none
 */ 
  public function __construct() {    
  }  
  

/**
 * Add a filter setting
 * @param request_param 
 * @param name          name of the link to be displayed
 * @param internal_uri  optional, internal symfony url
 * @return none
 */ 
  public function add($request_param, $name) {
    $this->filters[$request_param] = $name;
  }
  

/**
 * Return the array
 * @return array
 */ 
  public function getFilters() {
    return $this->filters;
  }
  
/**
 * Return html filter status row
 * @return string
 */
  public function getHtml() {
    
    if ($this->filters) {
      $return = '<div class="ull_filter">';
      
      $return .= __('Filter settings') . ': ';
      
      $return .= "<ol>\n";
      
//      ullCoreTools::printR($this->filters);
      
      foreach ($this->filters as $request_param => $filter) {
          $return .= '<li>';
          $return .= $filter;
          $return .= ' ';
          $return .= ull_reqpass_icon(array($request_param => ''), 'delete');
          $return .= '</li>'; 
      }
      $return .= '</ol>';
      $return .= '</div>';
      
      return $return;
    }
  }   
  
}

?>
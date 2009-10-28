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
 * Add a filter setting
 * @param request_param 
 * @param name          name of the link to be displayed
 * @return none
 */ 
  public function add($request_param, $name) 
  {
    $this->filters[$request_param] = $name;
  }
  

/**
 * Return the array of filters
 * @return array
 */ 
  public function getFilters() 
  {
    return $this->filters;
  }

  
/**
 * Return html filter status row
 * @return string
 */
  public function getHtml() 
  {
    $return = '';
    
    if ($this->filters) 
    {
      $return .= '<div class="ull_filter">';
      
      $return .= __('Filter settings', null, 'common') . ': ';
      
      $return .= "<ul>\n";
      
      foreach ($this->filters as $request_param => $filter) 
      {
        $return .= '<li class="color_light_bg">';
        $return .= $filter;
        $return .= ' ';
        $return .= ull_link_to(ull_image_tag('delete', array(), 12, 12), array($request_param => ''));
        $return .= '</li>'; 
      }
      $return .= '</ul>';
      $return .= '</div>';
    }
    
    return $return;
  }   
  
  
  /**
   * String representation
   * 
   * @return string
   */
  public function __toString()
  {
    return $this->getHtml();
  }  
  
}

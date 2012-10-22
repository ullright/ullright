<?php
class ullWebResponse extends sfWebResponse
{
  
  /** 
   * Remove invalid (in html5) meta name="title" tag
   * 
   * @see sfWebResponse::getMetas()
   */
  public function getMetas()
  {
    $metas = parent::getMetas();
    
    if (array_key_exists('title', $metas))
    {
      unset($metas['title']);
    }
    
    return $metas;
  }
  
}
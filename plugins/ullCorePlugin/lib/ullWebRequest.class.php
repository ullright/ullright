<?php


class ullWebRequest extends sfWebRequest
{
  
  /**
   * Loads GET, PATH_INFO and POST data into the parameter list.
   *
   */
  protected function loadParameters()
  {
    parent::loadParameters();
    
    foreach ($this->parameterHolder->getAll() as $key => $value)
    {
      $cleanedKey = urldecode($key);
      
      //find "array type" keys, and extract it
      //example: filter[search] => filter, search      
      // currently only on level down (so filter[search][foobar] doesn't work)
      if (preg_match('/([^\[]+)[\[]([^\]]+)[\]]/', $cleanedKey, $matches)) 
      {
        $this->parameterHolder->remove($key);
        $array[$matches[1]][$matches[2]]= $value;
      }
    }
    
    if (isset($array)) 
    {
      foreach($array as $key => $value)
      {
        $this->setParameter($key, $value);
      }  
    }
    
    if (sfConfig::get('sf_logging_enabled'))
    {
      $this->dispatcher->notify(new sfEvent($this, 'application.log', array(sprintf('Request parameters %s', str_replace("\n", '', var_export($this->getParameterHolder()->getAll(), true))))));
    }

  }
  
}
<?php

class ullWebRequest extends sfWebRequest
{
  /**
   * Loads GET, PATH_INFO and POST data into the parameter list.
   *
   */
  protected function fixParameters()
  {
    parent::fixParameters();
    
    $this->decryptSecureParameters();
    
    $this->fixDotCharacter();
    
    $this->parseSubmitName();
    
    $this->parseSquareBracketKeys();

    if (sfConfig::get('sf_logging_enabled'))
    {
      $this->dispatcher->notify(new sfEvent($this, 'application.log', array(sprintf('Request parameters %s', str_replace("\n", '', var_export($this->getParameterHolder()->getAll(), true))))));
    }

  }

  /**
   * Applies ullCoreTools:urlDotDecode to all parameters, which
   * is needed by symfony's default routing
   * 
   * Only works for top and first parameter level (array in array is not supported)
   */
  protected function fixDotCharacter()
  {
    $parameterHolder = $this->getParameterHolder();
    foreach ($parameterHolder->getAll() as $paramName => $paramValue)
    {
      if (is_array($paramValue))
      {
        array_walk($paramValue, 'ullCoreTools::urlDotDecode');
        $parameterHolder->set($paramName, $paramValue);
      }
      else
      {
        $parameterHolder->set($paramName, ullCoreTools::urlDotDecode($paramValue));
      }
    }
  }
  
  /**
   * Decrypts parameter values if their name fulfills certain requirements,
   * tested by ullSecureParameter::isSecureParameter().
   */
  protected function decryptSecureParameters()
  {
    $parameters = $this->getParameterHolder()->getAll();
    
    foreach ($parameters as $paramName => $paramValue)
    {
      if (ullSecureParameter::isSecureParameter($paramName))
      {
        $this->getParameterHolder()->set($paramName,
          ullSecureParameter::decryptParameter($paramValue));
      }
    }
  }
  
  
  /**
   * Parses payload in the name attribute of the clicked submit tag
   * and sets the request params accordingly
   * 
   * The payload must be declared by a leading "submit|" string,
   * followed by key=value pairs separated by "|"
   * 
   * The allowed chars for key and value are 0-9, a-z, A-Z and '_'
   * 
   * Example:
   * 
   * $request = array(
   *   'submit|save_mode=save_only|external=upload|external_field=10' => 'Save'
   * );
   * 
   * is transformed to 
   * 
   * $request = array(
   *   'save_mode'         => 'save_only',
   *   'external'          => 'upload',
   *   'external_field'    => 10,
   * ); 
   *
   */ 
  protected function parseSubmitName()
  {
    foreach ($this->parameterHolder->getAll() as $key => $value)
    {
      if (substr($key,0,7) == 'submit|')
      {
        $this->parameterHolder->remove($key);
        if ($value)
        {
          $payload = str_replace('|', ' ', substr($key, 7));
          $payloadParams = sfToolkit::stringToArray($payload);
          foreach ($payloadParams as $pKey => $pValue)
          {
            $this->parameterHolder->set($pKey, $pValue);
          }
        }
      }
    }
  }
  
  /**
   * Find "array type" keys, and extract it
   * 
   * Example: array('filter[search]' => $value) is tranformed to 
   *   array('filter' => array('search' => $value))
   * 
   * Currently only on level down (so filter[search][foobar] doesn't work)
   */
  protected function parseSquareBracketKeys()
  {
    $array = array();
    
    foreach ($this->parameterHolder->getAll() as $key => $value)
    {
      $cleanedKey = urldecode($key);
      
      if (preg_match('/([^\[]+)[\[]([^\]]+)[\]]/', $cleanedKey, $matches)) 
      {
        $this->parameterHolder->remove($key);
        $array[$matches[1]][$matches[2]]= $value;
      }
    }    
  
    if ($array) 
    {
      foreach($array as $key => $value)
      {
        $this->setParameter($key, $value);
      }  
    }
  }
  
}
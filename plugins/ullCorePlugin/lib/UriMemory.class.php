<?php

/**
 * URI memory class
 * 
 * Allows saving and retrieval of URIs per given symfony module/action
 *
 * @package    ullright
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 * 
 */

class UriMemory
{
  
  protected
    $defaultURI = '@homepage',
    $module,
    $action,
    $uriName,
    $user
  ;
    
  /**
   * Constructor
   * 
   * @param none
   * @return none
   */ 
  public function __construct() 
  {
    $this->user = sfContext::getInstance()->getUser();
  }

  /**
   * Gets the default URI
   * 
   * @return string
   */
  public function getDefault()
  {
    return $this->defaultURI;
  }

  /**
   * Sets the default URI
   * 
   * @return string
   */
  public function setDefault($uri)
  {
    $this->defaultURI = $uri;
  }    
  
  /**
   * Gets an URI
   * 
   * @param string $action            optional  name of a symfony action
   * @param string $module            optional  name of a symfony module
   * @return string
   */ 
  public function get($action = null, $module = null) 
  {
    $this->setModuleAndAction($module, $action);
    
    if ($this->getUser()->hasAttribute($this->uriName))
    {
      return $this->getUser()->getAttribute($this->uriName);
    }
    else
    {
      return $this->getDefault();
    }
  }  

  /**
   * Sets an URI
   * 
   * @param string $action            optional  name of a symfony action
   * @param string $module            optional  name of a symfony module
   * @param boolean $overwrite        optional  overwrite saved uri?
   * @param sting $uri                optional  set a given uri (mainly for testing)
   */ 
  public function setUri($action = null, $module = null, $overwrite = true, $uri = null) 
  {
    $this->setModuleAndAction($module, $action);
    
    // use current URI
    if (!$uri)
    {
      $uri = sfContext::getInstance()->getRequest()->getUri();
    }
    
    // don't overwrite if the URI exists and overwriting is not allowed
    if (!($this->getUser()->hasAttribute($this->uriName) && !$overwrite))
    {
      $this->getUser()->setAttribute($this->uriName, $uri);
    }
  }    
  
  /**
   * Sets an URI, uses the current HTTP referer by default
   * 
   * @param string $action            optional  name of a symfony action
   * @param string $module            optional  name of a symfony module
   * @param boolean $overwrite        optional  overwrite saved uri?
   * @param sting $uri                optional  set a given uri (mainly for testing)
   */ 
  public function setReferer($action = null, $module = null, $overwrite = true, $uri = null) 
  {
    // use current referer
    if (!$uri)
    {
      if (isset($_SERVER['HTTP_REFERER']))
      {
        $uri = $_SERVER['HTTP_REFERER'];
      }
    }
    
    $this->setUri($action, $module, $overwrite, $uri);
  } 
  

  /**
   * Checks if a saved URI exists for a symfony module/action
   * 
   * @param string $action            optional  name of a symfony action
   * @param string $module            optional  name of a symfony module
   * @return boolean
   */   
  public function has($action = null, $module = null) 
  {
    $this->setModuleAndAction($module, $action);
    
    if ($this->user->hasAttribute($this->uriName))
    {
      return true;
    }
  }  
  
  
  /**
   * Deletes a saved URI for a symfony module / action
   * 
   * @param string $action            optional  name of a symfony action
   * @param string $module            optional  name of a symfony module
   * @return none
   */    
  public function delete($action = null, $module = null) 
  {
    $this->setModuleAndAction($module, $action);

    if ($this->user->hasAttribute($this->uriName)) 
    {
      $this->user->getAttributeHolder()->remove($this->uriName);  
    }    
  }
  
  /**
   * Gets the URI for a symfony module/action, and deletes it
   *   
   * @param string $action            optional  name of a symfony action
   * @param string $module            optional  name of a symfony module
   * @return string
   */    
  public function getAndDelete($action = null, $module = null) 
  {
    $uri = $this->get($action, $module);
    $this->delete($action, $module);
    
    return $uri;
  }  

  
  /**
   * Set module and action name or use current request module/action
   * 
   * @param string $module    optional  a symfony module name
   * @param string $action    optional a A symfony action name
   * @return none
   */
  protected function setModuleAndAction($module = null, $action = null)
  {
    if ($module) 
    {
      $this->module = $module;
    }
    else
    {
      $this->module = sfContext::getInstance()->getRequest()->getParameter('module');
    }     
    
    if ($action) 
    {
      $this->action = $action;
    }
    else
    {
      $this->action = sfContext::getInstance()->getRequest()->getParameter('action');
    }
    
    $this->uriName = 'uri_' . $this->module . '_' . $this->action;
  }

  
  /**
   * Get user object
   * 
   * @return sfUser
   */
  protected function getUser()
  {
    return $this->user;
  }

}

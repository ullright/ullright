<?php

/**
 * Offers basic functionality for the ullGenerators and ColumnConfigCollections
 
 * @author klemens.ullmann-marx@ull.at
 */

class ullGeneratorBase
{
  protected
    $defaultAccess,
    $requestAction
  ;

  /**
   * Constructor
   *
   * @param string $defaultAccess "r" or "w" for read or write
   * @param string $requestAction 
   */
  public function __construct($defaultAccess = null, $requestAction = null)
  {
    if ($requestAction === null)
    {
      if (sfContext::getInstance()->getRequest()->hasParameter('action'))
      {
        $this->setRequestAction(sfContext::getInstance()->getRequest()->getParameter('action'));
      }
      else
      {
        $this->setRequestAction('list');
      }
    }
    else
    {
      $this->setRequestAction($requestAction);
    }
    
    if ($defaultAccess == null)
    {
      $this->setDefaultAccess($this->guessDefaultAccess($this->getRequestAction()));
    }
    else
    {
      $this->setDefaultAccess($defaultAccess);
    }
  }
  
  /**
   * Guesses the default access depending on the request action
   * 
   * @param $requestAction string
   * @return string
   */
  public function guessDefaultAccess($requestAction)
  {
    if ($requestAction == 'list')
    {
      return 'r';
    }
    else
    {
      return 'w';
    }
  }

  /**
   * Sets default access
   * 
   * @param string $access can be "r" or "w" for read or write
   * @throws InvalidArgumentException
   */
  // what about search 's' ? -> ahh, separate generator -> unify?
  public function setDefaultAccess($access = 'r')
  {
    if (!in_array($access, array('r', 'w')))
    {
      throw new InvalidArgumentException('Invalid access type "'. $access .'. Has to be either "r" or "w"'); 
    }

    $this->defaultAccess = $access;
  }  

  /**
   * get default access
   *
   * @return string can be "r" or "w" for read or write
   */
  public function getDefaultAccess()
  {
    return $this->defaultAccess;
  }  
  
  /**
   * set request action and update the default access accordingly
   * 
   * @param string $reqestAction
   */
  public function setRequestAction($requestAction)
  {
    $this->requestAction = $requestAction;
    $this->setDefaultAccess($this->guessDefaultAccess($requestAction)); 
  }
  
  /**
   * get request action
   *
   * @return string can be "list" or "edit"
   */
  public function getRequestAction()
  {
    return $this->requestAction;
  } 
  
  /**
   * Test for the current request action
   * 
   * @param $action mixed string or array of actions
   * @return boolean
   */
  public function isAction($action)
  {
    if(!is_array($action))
    {
      $action = array($action);
    }
    
    if (in_array($this->requestAction, $action))
    {
      return true;
    }
  }
  
  /**
   * Returns true if the current requestAction is 'list'
   * 
   * @return boolean
   */
  public function isListAction()
  {
    return $this->isAction('list');
  }
  
  /**
   * Returns true if the current requestAction is 'show'
   * 
   * @return boolean
   */
  public function isShowAction()
  {
    return $this->isAction('show');
  }  
  
  /**
   * Returns true if the current requestAction is 'create'
   * 
   * @return boolean
   */  
  public function isCreateAction()
  {
    return $this->isAction('create');
  }  

  /**
   * Returns true if the current requestAction is 'edit'
   * 
   * @return boolean
   */
  public function isEditAction()
  {
    return $this->isAction('edit');
  }  

  /**
   * Returns true if the current requestAction is 'create' or 'edit'
   * 
   * @return boolean
   */  
  public function isCreateOrEditAction()
  {
    return $this->isAction(array('create', 'edit'));
  }  
  
}
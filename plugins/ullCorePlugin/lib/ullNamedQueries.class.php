<?php

/**
 * A collection of ullNamedQuery objects usually for one particular
 * model/module e.g. ullUser.
 * 
 * This class supports the rendering of a list of custom queries
 * for the index action and the handling of ullFilter and query
 * modification for the list action.
 * 
 * Use the configure method to set the baseUri
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
abstract class ullNamedQueries extends ullNamedQueryCommon
{
  protected
    $namedQueries = array()
  ;
  
  /**
   * Constructor
   * 
   * @param string $baseUri
   * @return none
   */
  public function __construct()
  {
    parent::__construct();
    
    if (!$this->getBaseUri())
    {
      throw new RuntimeException('baseUri not defined');
    }
  }
  
  /**
   * Add a namedQuery
   * 
   * @param string $namedQueryClassName
   * @return self
   */
  public function add($namedQueryClassName)
  {
    if (class_exists($namedQueryClassName))
    {
      $namedQuery = new $namedQueryClassName;
      $namedQuery->setBaseUri($this->getBaseUri());
      $namedQuery->setI18NCatalogue($this->getI18nCatalogue());
      $this->namedQueries[$namedQuery->getIdentifier()] = $namedQuery;
      
      return $this;
    }
    else
    {
      throw new InvalidArgumentException('Invalid namedQuery class name ' . $namedQueryClassName);
    }
  }
  
  
  /**
   * Get a namedQuery by its identifier
   * 
   * @param string $identifier
   * @return ullNamedQuery
   */
  public function get($identifier)
  {
    if (isset($this->namedQueries[$identifier]))
    {
      return $this->namedQueries[$identifier];
    }
    else
    {
      throw new InvalidArgumentException('Invalid identifier ' . $identifier);
    }  
  }
  
  
  /**
   * Returns an array with all existing ullNamedQuery objects
   * 
   * @return array
   */
  public function getAll()
  {
    return $this->namedQueries;
  }
  
  /**
   * Renders the list as a simple <ul> html list
   * @return string
   */
  public function renderList()
  {
    $html = "<ul>\n";
    foreach ($this->namedQueries as $namedQuery)
    {
      $html .= '<li>' . link_to($namedQuery->getName(), $namedQuery->getUri()) . "</li>\n";
    }
    $html .= "</ul>\n\n";

    return $html;
  }
  

  /**
   * Handle Filter for list action
   * 
   * Add a ullFilter entry and modify the query
   * 
   * @param Doctrine_Query $q
   * @param ullFilter $ullFilter
   * @param sfWebRequest $request
   * @return none
   */
  public function handleFilter($q, ullFilter $ullFilter, sfWebRequest $request)
  {
    $identifier = $request->getParameter('query');

    // 'custom' is reserved for the advanced search
    if ($identifier && $identifier != 'custom')
    {
      $namedQuery = $this->get($identifier);
    
      $namedQuery->modifyQuery($q);
      $ullFilter->add('query', $namedQuery->getName());
    }
  }
  
  /**
   * Set the base uri, also for existing queries
   * 
   * @param string $uri
   * @return self
   */
  public function setBaseUriForExisting($uri)
  {
    $this->setBaseUri($uri);
    
    foreach ($this->namedQueries as $namedQuery)
    {
      $namedQuery->setBaseUri($this->baseUri);
    }

    return $this;
  }
  
  /**
   * Set the I18n catalogue, also for existing queries
   * 
   * @param string $i18n
   * @return self
   */
  public function setI18nCatalogueForExisting($i18n)
  {
    $this->setI18nCatalogue($i18n);
    
    foreach ($this->namedQueries as $namedQuery)
    {
      $namedQuery->setI18nCatalogue($this->i18nCatalogue);
    }

    return $this;
  }
  
  
  /**
   * String representation
   * 
   * @return string
   */
  public function __toString()
  {
    return (string) $this->renderList();
  }
}
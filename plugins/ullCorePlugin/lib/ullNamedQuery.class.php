<?php

/**
 * Represents a custom "named" query to be used 
 * in the index and list action of a certain module.
 * 
 * Use the configure() method to configure name and identifier
 *  
 * @author klemens.ullmann-marx@ull.at
 *
 */
abstract class ullNamedQuery extends ullNamedQueryCommon
{
  protected
    /**
     * Name of the query
     * Example: All inactive users
     */
    $name,
    /**
     * Identifier of the query used in the uri
     * Example: "inactive_users" 
     */
    $identifier
  ;

  
  /**
   * Constructor
   * 
   * Check that name and identifier are defined
   * 
   * @return none
   */
  public function __construct()
  {
    parent::__construct();
    
    if(!$this->getName())
    {
      throw new RuntimeException('name not defined');
    }
    
    if(!$this->getIdentifier())
    {
      throw new RuntimeException('identifier not defined');
    }    
  }
  
  
  /**
   * Set name
   * 
   * @param string $name
   * @return self
   */
  public function setName($name)
  {
    $this->name = $name;
    
    return $this;
  } 
  
  
  /**
   * Get the name of the query
   * e.g. "All entries assigend to me"
   * 
   * @return string
   */
  public function getName()
  {
    return __($this->name, null, $this->getI18nCatalogue());
  }
  
  
  /**
   * Set the identifier of the query
   * 
   * @param string $identifier
   * @return self
   */
  public function setIdentifiert($identifier)
  {
    $this->identifier = $identifier;
    
    return $this;
  }
  
  /**
   * Get the identifier of the query
   * e.g. "to_me"
   * 
   * @return string
   */
  public function getIdentifier()
  {
    return $this->identifier;
  }  

  
  /**
   * Get the uri for the query
   * 
   * @return string
   */
  public function getUri()
  {
    if (!$this->getBaseUri())
    {
      throw new RuntimeException('baseUri not defined');
    }
    $operator = (strstr($this->getBaseUri(), '?')) ? '&' : '?';
    
    return $this->getBaseUri() . $operator . 'query=' . $this->getIdentifier();
  }
  
  
  /**
   * Modifies a given ullQuery or Doctrine_Query (=deprecated) with the desired clauses 
   * To be defined by child classes
   * 
   * @param ullQuery $q
   * @return ullQuery
   */
  abstract public function modifyQuery($q);
  
}
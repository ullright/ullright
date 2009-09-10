<?php

/**
 * Common functionality for ullNamedQuery and ullNamedQueries
 *  
 * @author klemens.ullmann-marx@ull.at
 *
 */
abstract class ullNamedQueryCommon
{
  protected
    /**
     * Internal URI where the named query is appended
     * Example: ullUser/list?orderby=created_at
     */
    $baseUri,
    /**
     * Name of a custom i18n catalogue 
     * Example: ullUserMessages
     */
    $i18nCatalogue = null
  ;
  
  /**
   * Constructor
   * 
   * @return none
   */  
  public function __construct()
  {
    $this->configure();
  }
  
  /**
   * Child classes use this method for configuration
   * 
   * @return none
   */
  abstract public function configure();

  
  /**
   * Set the base uri
   * 
   * @param string $uri
   * @return self
   */
  public function setBaseUri($uri)
  {
    $this->baseUri = $uri;
    
    return $this;
  }
  
  
  /**
   * Get the base uri
   * 
   * @return string
   */
  public function getBaseUri()
  {
    return $this->baseUri;  
  }

  
  /**
   * Set the i18n catalogue
   * 
   * @param string $catalogue
   * @return self
   */
  public function setI18nCatalogue($catalogue)
  {
    $this->i18nCatalogue = $catalogue;
    
    return $this;
  }

  
  /**
   * Get the i18n catalogue
   * 
   * @return string
   */
  public function getI18nCatalogue()
  {
    return $this->i18nCatalogue;
  }
  
}